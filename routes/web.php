<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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

// ============================================================
// Dashboard: redirige a cada usuario a su panel según el rol
// ============================================================
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


// ============================================================
// Recursos generales (cualquier usuario autenticado)
// El control fino (qué mascotas/citas ve cada uno) lo hacen
// las Policies dentro de los controladores.
// ============================================================
Route::middleware(['auth'])->group(function () {
    // Perfil del usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // El PDF va antes del resource para que "pdf" no se tome como el id de una cita
    Route::get('/citas/pdf', [CitaController::class, 'pdf'])->name('citas.pdf');
    Route::resource('mascotas', MascotaController::class)->except(['show']);
    Route::resource('citas', CitaController::class)->except(['show']);

    // Agenda
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::patch('/agenda/{cita}/confirmar', [AgendaController::class, 'confirmar'])->name('agenda.confirmar');
    Route::patch('/agenda/{cita}/asignar-vet', [AgendaController::class, 'asignarVet'])->name('agenda.asignarVet');
    Route::patch('/agenda/{cita}/cancelar', [AgendaController::class, 'cancelar'])->name('agenda.cancelar');

    // Ver una historia (la Policy decide si puede)
    Route::get('/historias/{historia}', [HistoriaController::class, 'show'])->name('historias.show');
    Route::get('/historias/{historia}/pdf', [HistoriaController::class, 'pdf'])->name('historias.pdf');
});


// ============================================================
// ADMIN
// ============================================================
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});


// ============================================================
// VETERINARIO
// ============================================================
Route::middleware(['auth','role:veterinario'])->prefix('vet')->name('vet.')->group(function () {
    Route::get('/dashboard', [VetDashboardController::class, 'index'])->name('dashboard');

    // Mis historias / mis recetas
    Route::get('/historias', [HistoriaController::class, 'mineVet'])->name('historias.mine');
    Route::get('/recetas',   [RecetaController::class, 'mine'])->name('recetas.mine');

    // Historial completo de una mascota (incluye historias de otros veterinarios)
    Route::get('/mascotas/{mascota}/historias', [HistoriaController::class, 'deMascota'])->name('mascotas.historias');

    // Recetas dentro de una historia
    Route::get('/historias/{historia}/recetas',        [RecetaController::class, 'index'])->name('recetas.index');
    Route::get('/historias/{historia}/recetas/create', [RecetaController::class, 'create'])->name('recetas.create');
    Route::post('/historias/{historia}/recetas',       [RecetaController::class, 'store'])->name('recetas.store');
    Route::get('/recetas/{receta}',                    [RecetaController::class, 'show'])->name('recetas.show');

    // Marcar cita como atendida
    Route::patch('/citas/{cita}/completar', [CitaController::class, 'completar'])->name('citas.completar');
});

// Vet: guardar/editar historia clínica + enviar/retirar de caja
Route::middleware(['auth','role:veterinario'])->group(function () {
    Route::resource('historias', HistoriaController::class)->only(['store','update']);
    Route::post('/historias/save', [HistoriaController::class, 'save'])->name('historias.save');

    Route::post('/historias/{historia}/enviar-caja',  [HistoriaController::class, 'enviarACaja'])->name('historias.enviarCaja');
    Route::post('/historias/{historia}/retirar-caja', [HistoriaController::class, 'retirarDeCaja'])->name('historias.retirarCaja');
});


// ============================================================
// CLIENTE
// ============================================================
Route::middleware(['auth','role:user'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    Route::get('/historias', [HistoriaController::class, 'mineClient'])->name('historias.mine');
});


// ============================================================
// RECEPCIÓN
// ============================================================
Route::middleware(['auth','role:recepcionista'])->prefix('reception')->name('reception.')->group(function () {
    Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');
});


// ============================================================
// CAJA / FACTURACIÓN  (solo recepción y admin)
// ============================================================
Route::middleware(['auth','role:admin,recepcionista'])->group(function () {
    // Caja: historias pendientes de cobro
    Route::get('/caja',            [CajaRecepcionController::class, 'index'])->name('caja.index');
    Route::get('/caja/pendientes', [CajaRecepcionController::class, 'index'])->name('caja.pendientes');

    // Facturas
    Route::resource('facturas', FacturaController::class)->only(['index','create','store','show']);
    Route::get('/facturas/create-from-historia/{historia}', [FacturaController::class, 'createFromHistoria'])->name('facturas.createFromHistoria');
    Route::patch('/facturas/{factura}/pagar',  [FacturaController::class, 'pagar'])->name('facturas.pagar');
    Route::patch('/facturas/{factura}/anular', [FacturaController::class, 'anular'])->name('facturas.anular');
    Route::get('/facturas/{factura}/pdf',      [FacturaController::class, 'pdf'])->name('facturas.pdf');

    // Reporte de caja
    Route::get('/reportes/caja', [FacturaController::class, 'caja'])->name('reportes.caja');
});


require __DIR__.'/auth.php';