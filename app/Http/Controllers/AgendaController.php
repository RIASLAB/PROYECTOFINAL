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

        $veterinarios = User::where('role','veterinario')->where('status','activo')->orderBy('name')->get(['id','name']);

        return view('agenda.index', compact('citas','veterinarios','estado','vetId','desde','hasta','q'));
    }

    public function confirmar(Request $request, Cita $cita)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista']), 403);

        if ($cita->estado !== 'pendiente') {
            return back()->withErrors(['estado' => 'Solo se pueden confirmar citas pendientes.']);
        }
        $cita->update(['estado' => 'confirmada']);
        return back()->with('ok','Cita confirmada.');
    }

    /** La recepción cancela una cita (HU10). */
    public function cancelar(Request $request, Cita $cita)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista']), 403);

        if ($cita->estado === 'completada') {
            return back()->withErrors(['estado' => 'No se puede cancelar una cita que ya fue atendida.']);
        }

        $cita->update(['estado' => 'cancelada']);
        return back()->with('ok','Cita cancelada.');
    }

    public function asignarVet(Request $request, Cita $cita)
    {
        $user = Auth::user();
        abort_unless(in_array($user->role, ['admin','recepcionista']), 403);

        $data = $request->validate([
            // Solo se puede asignar a un usuario con rol veterinario y activo
            'vet_id' => ['required','integer', \Illuminate\Validation\Rule::exists('users','id')->where(fn ($q) => $q->where('role','veterinario')->where('status','activo'))]
        ], [
            'vet_id.exists' => 'Debes elegir un veterinario activo.',
        ]);

        // Evita choque del vet en ese horario
        $ocupado = Cita::where('vet_id',$data['vet_id'])
            ->whereDate('fecha',$cita->fecha)
            ->where('hora',$cita->hora)
            ->where('id','<>',$cita->id)
            ->where('estado','!=','cancelada')
            ->exists();

        if ($ocupado) {
            return back()->withErrors(['vet_id'=>'Ese veterinario ya tiene una cita en ese horario.']);
        }

        $cita->update($data);
        return back()->with('ok','Veterinario asignado.');
    }
}
