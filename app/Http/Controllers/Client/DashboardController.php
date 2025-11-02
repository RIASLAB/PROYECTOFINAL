<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Si luego quieres contadores (citas del cliente, etc.), los pasamos aquí.
        return view('client.dashboard');
    }
}
