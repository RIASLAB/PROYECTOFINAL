<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        // Solo admin/recepcionista/veterinario usan esta vista
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista','veterinario']), 403);

        $estado = $request->get('estado');          // pendiente|confirmada|cancelada|completada
        $vetId  = $request->get('vet_id');
        $desde  = $request->date('desde');
        $hasta  = $request->date('hasta');
        $q      = trim((string)$request->get('q'));

        $citas = Cita::with(['mascota','vet'])
            ->when($desde, fn($q2) => $q2->whereDate('fecha','>=',$desde))
            ->when($hasta, fn($q2) => $q2->whereDate('fecha','<=',$hasta))
            ->when($estado, fn($q2) => $q2->where('estado',$estado))
            ->when($vetId,  fn($q2) => $q2->where('vet_id',$vetId))
            ->when($q !== '', function($qb) use ($q) {
                $qb->where(function($s) use ($q){
                    $s->where('motivo','like',"%{$q}%")
                      ->orWhereHas('mascota', fn($m)=>$m->where('nombre','like',"%{$q}%"));
                });
            })
            ->when($user->role === 'veterinario', fn($qb) => $qb->where('vet_id', $user->id))
            ->orderBy('fecha')->orderBy('hora')
            ->paginate(12)->withQueryString();

        $veterinarios = User::where('role','veterinario')->orderBy('name')->get(['id','name']);

        return view('agenda.index', compact('citas','veterinarios','estado','vetId','desde','hasta','q'));
    }

    public function confirmar(Request $request, Cita $cita)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista']), 403);

        $cita->update(['estado' => 'confirmada']);
        return back()->with('ok','Cita confirmada.');
    }

    public function asignarVet(Request $request, Cita $cita)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista']), 403);

        $data = $request->validate([
            'vet_id' => ['required','integer','exists:users,id']
        ]);

        // Evita choque del vet en ese horario
        $ocupado = Cita::where('vet_id',$data['vet_id'])
            ->whereDate('fecha',$cita->fecha)
            ->where('hora',$cita->hora)
            ->where('id','<>',$cita->id)
            ->exists();

        if ($ocupado) {
            return back()->withErrors(['vet_id'=>'Ese veterinario ya tiene una cita en ese horario.']);
        }

        $cita->update($data);
        return back()->with('ok','Veterinario asignado.');
    }
}
