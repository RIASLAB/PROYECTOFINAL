@extends('layouts.app')
@php($hideNav = true) {{-- ocultar nav y header del layout, igual que los otros --}}

@section('content')
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/reception-dashboard.css') }}">

  {{-- Topbar degradada --}}
  <nav class="topbar">
    <div class="brand">Lugo Vet</div>
    <div class="right">
      <span>(Recepcionista)</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="btn-logout">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
      </form>
    </div>
  </nav>

  <div class="wrap">
    {{-- Bienvenida --}}
    <section class="welcome">
      <h2>¡Bienvenido, {{ auth()->user()->name }}!</h2>
      <p>Gestiona la agenda, registra clientes y mascotas, y genera reportes.</p>
    </section>

    {{-- KPIs --}}
    <section class="kpis">
      <div class="kpi">
        <div class="n">{{ $citasHoy }}</div>
        <div class="l">Citas de Hoy</div>
      </div>
      <div class="kpi">
        <div class="n">{{ $clientesActivos }}</div>
        <div class="l">Clientes Activos</div>
      </div>
      <div class="kpi">
        <div class="n">{{ $mascotasRegistradas }}</div>
        <div class="l">Mascotas Registradas</div>
      </div>
    </section>

    {{-- Menú de opciones --}}
    <h3 class="sect-title">Opciones disponibles</h3>
    <section class="menu">

      {{-- Ver Agenda --}}
      <a href="{{ route('agenda.index') }}" class="opt">
        <i class="fas fa-calendar-day"></i><span>Ver Agenda</span>
      </a>

      {{-- Agendar Cita --}}
      <a href="{{ \Illuminate\Support\Facades\Route::has('citas.create') ? route('citas.create') : '#' }}" class="opt">
        <i class="fas fa-calendar-plus"></i><span>Agendar Cita</span>
      </a>

      {{-- Lista de Citas --}}
      <a href="{{ \Illuminate\Support\Facades\Route::has('citas.index') ? route('citas.index') : '#' }}" class="opt">
        <i class="fas fa-list"></i><span>Lista de Citas</span>
      </a>

      
      {{-- Mascotas --}}
      <a href="{{ \Illuminate\Support\Facades\Route::has('mascotas.index') ? route('mascotas.index') : '#' }}" class="opt">
        <i class="fas fa-paw"></i><span>Mascotas</span>
      </a>

      {{-- ✅ Reportes → Caja / Facturación (punto 6) --}}
      <a href="{{ route('caja.index') }}" class="opt">
  <i class="fas fa-file-invoice-dollar"></i><span>Caja / Facturación</span>
</a>


    </section>
  </div>
@endsection
