<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cita;

class PdfController extends Controller
{
    public function citas()
    {
        $citas = Cita::with('mascota')->orderBy('fecha', 'desc')->get();

        if ($citas->isEmpty()) {
            // Solo para verificar si trae datos
            return response('⚠️ No hay citas registradas en la base de datos.');
        }

        $pdf = Pdf::loadView('pdf.citas', compact('citas'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('citas.pdf');
    }
}
