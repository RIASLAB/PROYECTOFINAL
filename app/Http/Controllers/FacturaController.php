<?php

namespace App\Http\Controllers;

use App\Models\{Factura, FacturaItem, Historia, Receta, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class FacturaController extends Controller
{
    /* ========================
     * LISTADO (para Volver)
     * ======================== */
    public function index()
    {
        $facturas = Factura::withCount('items')->latest()->paginate(10);
        return view('facturas.index', compact('facturas'));
    }

    /* ========================
     * CREAR vacío (opcional)
     * ======================== */
    public function create()
    {
        $items = [
            ['descripcion' => 'Servicio', 'cantidad' => 1, 'precio' => 0, 'subtotal' => 0],
        ];
        return view('facturas.create', [
            'historia' => null,
            'cliente'  => null,
            'items'    => $items,
            'total'    => 0,
        ]);
    }

    /* ======================================================
     * CREAR desde Historia (prellenado con recetas)
     * ====================================================== */
    public function createFromHistoria(Historia $historia)
    {
        // ❗Bloquea si ya existe alguna factura pagada para esta historia
        $yaPagada = Factura::where('historia_id', $historia->id)
            ->where('estado', 'pagada')
            ->exists();

        if ($yaPagada) {
            return back()->with('error', 'Esta historia ya tiene una factura pagada. No puedes generar otra.');
        }

        $mascota = optional(optional($historia->cita)->mascota);
        $clienteNombre = optional($mascota)->dueno
            ?? optional(optional($historia->cita)->cliente)->name
            ?? null;

        $recetas = $historia->recetas()->latest()->get();

        if ($recetas->isEmpty()) {
            return back()->with('error', 'Esta historia no tiene recetas para facturar.');
        }

        $items = $recetas->map(function (Receta $r) {
            return [
                'descripcion' => trim(($r->titulo ?: 'Receta') . ($r->indicaciones ? ' – ' . strip_tags($r->indicaciones) : '')),
                'cantidad'    => 1,
                'precio'      => 0,
                'subtotal'    => 0,
                'receta_id'   => $r->id,
            ];
        })->values()->all();

        $total = collect($items)->sum('subtotal');

        return view('facturas.create', [
            'historia' => $historia,
            'cliente'  => $clienteNombre,
            'items'    => $items,
            'total'    => $total,
        ]);
    }

    /* ========================
     * GUARDAR
     * ======================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente'               => ['nullable','string','max:255'],
            'historia_id'           => ['nullable','integer'],
            'impuesto'              => ['required','numeric','min:0'],
            'items'                 => ['required','array','min:1'],
            'items.*.descripcion'   => ['required','string','max:255'],
            'items.*.cantidad'      => ['required','integer','min:1'],
            'items.*.precio'        => ['required','numeric','min:0'],
        ], [
            'items.required' => 'Agrega al menos un ítem con cantidad y precio.',
        ]);

        // Calcular subtotales
        $subtotal = 0;
        foreach ($data['items'] as &$it) {
            $it['subtotal'] = (float) $it['cantidad'] * (float) $it['precio'];
            $subtotal += $it['subtotal'];
        }
        $impuesto = (float) $data['impuesto'];
        $total    = $subtotal + ($subtotal * ($impuesto / 100));

        // Mascota desde historia (si viene)
        $mascotaNombre = null;
        if (!empty($data['historia_id'])) {
            $h = Historia::with('cita.mascota')->find($data['historia_id']);
            $mascotaNombre = optional(optional($h->cita)->mascota)->nombre;
        }

        // Crear factura + items (transacción)
        DB::transaction(function () use ($data, $subtotal, $impuesto, $total, $mascotaNombre, &$factura) {
            $factura = Factura::create([
                'historia_id' => $data['historia_id'] ?? null,
                'user_id'     => auth()->id(),
                'cliente'     => $data['cliente'] ?? null,
                'mascota'     => $mascotaNombre,
                'subtotal'    => $subtotal,
                'impuesto'    => $impuesto,
                'total'       => $total,
                'estado'      => 'pendiente',
            ]);

            foreach ($data['items'] as $it) {
                $factura->items()->create([
                    'descripcion' => $it['descripcion'],
                    'cantidad'    => (int) $it['cantidad'],
                    'precio'      => (float) $it['precio'],
                    'subtotal'    => (float) $it['subtotal'],
                ]);
            }
        });

        return redirect()->route('facturas.show', $factura)->with('ok', 'Factura guardada correctamente.');
    }

    /* ========================
     * VER
     * ======================== */
    public function show(Factura $factura)
    {
        $factura->load('items');
        return view('facturas.show', compact('factura'));
    }

    /* ========================
     * PAGAR / ANULAR
     * ======================== */
    public function pagar(Factura $factura)
    {
        if ($factura->estado === 'pagada') {
            return back()->with('ok','La factura ya estaba pagada.');
        }
        if ($factura->estado === 'anulada') {
            return back()->with('error','No puedes pagar una factura anulada.');
        }

        DB::transaction(function() use ($factura){
            $factura->update([
                'estado'  => 'pagada',
                'paid_at' => now(),
                'user_id' => auth()->id(),
            ]);
            // 👇 Ya NO retiramos de caja. Se queda listada pero marcada como "Pagada".
            // Historia::where('id', $factura->historia_id)->update(['enviado_caja_at' => null]);
        });

        return back()->with('ok','Factura marcada como pagada.');
    }

    public function anular(Factura $factura)
    {
        if ($factura->estado === 'anulada') {
            return back()->with('ok', 'La factura ya estaba anulada.');
        }

        $factura->update([
            'estado'  => 'anulada',
            // 'paid_at' => null,
            'user_id' => auth()->id(),
        ]);

        return back()->with('ok', 'Factura anulada.');
    }

    /* ========================
     * PDF
     * ======================== */
    public function pdf(Factura $factura)
    {
        $factura->load('items');

        $pdf = Pdf::loadView('facturas.pdf', compact('factura'))
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        return $pdf->stream('Factura-'.$factura->id.'.pdf');
    }

    /* =========================================
     * CAJA INDEX (pantalla principal de caja)
     * ========================================= */
    public function caja(Request $r)
    {
        // Rango de fechas (opcional)
        $desde = $r->input('desde', now()->toDateString());
        $hasta = $r->input('hasta', now()->toDateString());
        $start = Carbon::parse($desde)->startOfDay();
        $end   = Carbon::parse($hasta)->endOfDay();

        $q = trim($r->input('q', ''));

        $historias = Historia::query()
            ->with(['cita.mascota', 'cita.vet'])
            ->withCount('recetas') // $h->recetas_count
            // 🔥 CLAVE: Conteo de facturas pagadas para que la VISTA pinte "Pagada"
            ->withCount([
                'facturas as facturas_pagadas_count' => function ($q2) {
                    $q2->where('estado', 'pagada');
                }
            ])
            ->whereNotNull('enviado_caja_at')
            ->whereBetween('enviado_caja_at', [$start, $end])
            ->when($q, function ($qry) use ($q) {
                $qry->where('id', $q)
                    ->orWhereHas('cita.mascota', fn($m) => $m->where('nombre', 'like', "%{$q}%"))
                    ->orWhereHas('cita.vet', fn($v) => $v->where('name', 'like', "%{$q}%"));
            })
            ->orderByDesc('enviado_caja_at')
            ->paginate(10)
            ->withQueryString();

        return view('caja.index', compact('historias', 'desde', 'hasta', 'q'));
    }

    /* ================================
     * (Opcional) Helper para pendientes
     * ================================ */
    private function pendientesQuery()
    {
        return Historia::with(['cita.mascota','cita.vet','recetas'])
            ->whereNotNull('enviado_caja_at')
            ->whereNotExists(function($sub){
                $sub->select(DB::raw(1))
                    ->from('facturas')
                    ->whereColumn('facturas.historia_id','historias.id')
                    ->where('facturas.estado','pagada');
            });
    }
}
