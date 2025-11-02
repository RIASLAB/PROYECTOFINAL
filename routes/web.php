<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Vet\DashboardController as VetDashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Reception\DashboardController as ReceptionDashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CajaRecepcionController;
use App\Http\Controllers\HistoriaCajaController;


Route::get('/', function () {
    return view('welcome');
});

// Dashboard normal (usuarios logueados/verified)
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect()->route('login');

    return match ($user->role) {
        'admin'         => redirect()->route('admin.dashboard'),
        'veterinario'   => redirect()->route('vet.dashboard'),
        'recepcionista' => redirect()->route('reception.dashboard'),
        default         => redirect()->route('client.dashboard'),
    };
})->middleware(['auth','verified'])->name('dashboard');

// Rutas protegidas (usuario autenticado)
Route::middleware(['auth'])->group(function () {
    Route::resource('mascotas', MascotaController::class);
    Route::resource('citas', CitaController::class);
    Route::get('/citas/pdf', [CitaController::class, 'pdf'])->name('citas.pdf');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::patch('/agenda/{cita}/confirmar', [AgendaController::class, 'confirmar'])->name('agenda.confirmar');
    Route::patch('/agenda/{cita}/asignar-vet', [AgendaController::class, 'asignarVet'])->name('agenda.asignarVet');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/historias/{historia}', [HistoriaController::class, 'show'])
        ->name('historias.show');
});

    


Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    Route::get('/users', [UserManagementController::class,'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class,'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class,'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class,'destroy'])->name('users.destroy');
});


Route::middleware(['auth','role:veterinario'])
    ->prefix('vet')
    ->name('vet.')
    ->group(function () {
        Route::get('/dashboard', [VetDashboardController::class, 'index'])->name('dashboard');
         Route::get('/historias', [HistoriaController::class, 'mineVet'])->name('historias.mine');
            Route::get('/recetas', [RecetaController::class, 'mine'])->name('recetas.mine');
               Route::get('/historias/{historia}/recetas', [RecetaController::class, 'index'])
            ->name('recetas.index');

        Route::get('/historias/{historia}/recetas/create', [RecetaController::class, 'create'])
            ->name('recetas.create');

        Route::post('/historias/{historia}/recetas', [RecetaController::class, 'store'])
            ->name('recetas.store');

        Route::get('/recetas/{receta}', [RecetaController::class, 'show'])
            ->name('recetas.show');

         Route::get('/vet/recetas', [RecetaController::class,'mine'])
         ->name('vet.recetas');
    });
   

    Route::middleware(['auth','role:veterinario'])->prefix('vet')->name('vet.')->group(function () {

    Route::get('/historias/{historia}/recetas', [\App\Http\Controllers\RecetaController::class, 'index'])
        ->name('recetas.index');

    Route::get('/historias/{historia}/recetas/create', [\App\Http\Controllers\RecetaController::class, 'create'])
        ->name('recetas.create');

    Route::post('/historias/{historia}/recetas', [\App\Http\Controllers\RecetaController::class, 'store'])
        ->name('recetas.store');

    Route::get('/recetas/{receta}', [\App\Http\Controllers\RecetaController::class, 'show'])
        ->name('recetas.show');
});


    Route::middleware(['auth','role:user'])
    ->prefix('client')
    ->name('client.')
    ->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/historias', [HistoriaController::class, 'mineClient'])->name('historias.mine');
    });



Route::middleware(['auth','role:recepcionista'])   // añade 'no-back' si ya registraste ese alias
    ->prefix('reception')
    ->name('reception.')
    ->group(function () {
        Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])
            ->name('dashboard');
    });

// Vet: marcar cita como atendida

Route::middleware(['auth','role:veterinario'])->group(function () {
    Route::patch('/citas/{cita}/completar', [CitaController::class,'completar'])
        ->name('citas.completar');
});

// Vet: guardar/editar historia clínica
Route::middleware(['auth','role:veterinario'])->group(function () {
    Route::resource('historias', App\Http\Controllers\HistoriaController::class)
    ->only(['store','update']);

});

Route::post('/historias/save', [HistoriaController::class, 'save'])
    ->middleware(['auth','role:veterinario'])
    ->name('historias.save');

Route::middleware(['auth'])->group(function () {
    // Crear desde historia (recepción)
    Route::get('/facturas/create-from-historia/{historia}', [FacturaController::class,'createFromHistoria'])->name('facturas.createFromHistoria');

    // Guardar y ver
    Route::post('/facturas', [FacturaController::class,'store'])->name('facturas.store');
    Route::get('/facturas/{factura}', [FacturaController::class,'show'])->name('facturas.show');

    // Cobrar
    Route::patch('/facturas/{factura}/pagar', [FacturaController::class,'markPaid'])->name('facturas.pagar');

    // Reporte/Caja para recepcionista
    Route::get('/reportes/caja', [FacturaController::class,'caja'])->name('reportes.caja');
});


Route::middleware(['auth'])->group(function () {
    // Vet marca/ desmarca
    Route::post('/historias/{historia}/enviar-caja',  [HistoriaCajaController::class, 'enviar'])
        ->name('historias.enviarCaja');
    Route::post('/historias/{historia}/retirar-caja', [HistoriaCajaController::class, 'retirar'])
        ->name('historias.retirarCaja');

    // Vista de “Caja” para recepción/admin
    Route::get('/caja/pendientes', [CajaRecepcionController::class, 'index'])
        ->name('caja.pendientes');
});


Route::middleware(['auth'])->group(function () {
    Route::post('/historias/{historia}/enviar-caja',  [HistoriaController::class, 'enviarACaja'])->name('historias.enviarCaja');
    Route::post('/historias/{historia}/retirar-caja', [HistoriaController::class, 'retirarDeCaja'])->name('historias.retirarCaja');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/caja', [CajaRecepcionController::class, 'index'])->name('caja.index');
});


Route::middleware(['auth'])->group(function () {
    Route::resource('facturas', FacturaController::class)->only(['index','create','store','show']);

    // Acciones rápidas
    Route::patch('facturas/{factura}/pagar', [FacturaController::class, 'pagar'])->name('facturas.pagar');
    Route::patch('facturas/{factura}/anular', [FacturaController::class, 'anular'])->name('facturas.anular');
    
    // PDF
    Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])->name('facturas.pdf');
      Route::get('caja/pendientes', [FacturaController::class, 'caja'])->name('caja.pendientes');
      
});

require __DIR__.'/auth.php';

