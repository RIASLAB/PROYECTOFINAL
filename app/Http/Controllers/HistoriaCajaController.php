<?php
// app/Http/Controllers/HistoriaCajaController.php
namespace App\Http\Controllers;

use App\Models\Historia;
use Illuminate\Http\Request;

class HistoriaCajaController extends Controller
{
    public function enviar(Historia $historia)
    {
        // Autorización básica (ajusta a tu política/roles si quieres)
        $user = auth()->user();
        if (!in_array($user->role, ['veterinario','admin'])) {
            abort(403);
        }

        // Si quieres exigir que tenga recetas:
        // if ($historia->recetas()->count() === 0) {
        //     return back()->with('ok', 'No hay recetas en la historia para enviar a caja.');
        // }

        $historia->pendiente_cobro = true;
        $historia->save();

        return back()->with('ok', 'Historia enviada a caja. Ahora puede cobrarla recepción.');
    }

    public function retirar(Historia $historia)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['veterinario','admin'])) {
            abort(403);
        }

        $historia->pendiente_cobro = false;
        $historia->save();

        return back()->with('ok', 'Historia retirada de caja.');
    }
}
