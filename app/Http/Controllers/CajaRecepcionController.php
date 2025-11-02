<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Historia;


class CajaRecepcionController extends Controller
{
    public function index(Request $r)
    {
        $desde = $r->input('desde');
        $hasta = $r->input('hasta');

        $historias = Historia::query()
            ->where('pendiente_cobro', true)
            // Traemos conteo de recetas (para KPIs)…
            ->withCount('recetas')
            // …y cuántas facturas pagadas tiene cada historia
            ->withCount([
                'facturas as facturas_pagadas_count' => function ($q) {
                    $q->where('estado', 'pagada');
                }
            ])
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('caja.index', compact('historias', 'desde', 'hasta'));
    }
}
