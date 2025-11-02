<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:wght@700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/vet-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Barra superior --}}
    <div class="nav">
        <div class="brand">Lugo Vet</div>
        <div class="userbox">
            <span>(Veterinario)</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:transparent;border:0;color:#fff;cursor:pointer;padding:8px 14px;border-radius:8px;background:rgba(255,255,255,.2)">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="wrap">
        {{-- Bienvenida --}}
        <div class="welcome">
            <h2>Bienvenido, {{ auth()->user()->name }}!</h2>
            <p>Rol: <strong>Veterinario</strong></p>
        </div>

        {{-- Alerta si no hay citas programadas --}}
        @if($citasProgramadas === 0)
            <div class="alert">¡No tienes citas programadas por el momento!</div>
        @endif

        {{-- KPIs --}}
        <div class="kpis">
            <div class="kpi">
                <div class="n">{{ $citasProgramadas }}</div>
                <div class="l">Citas Programadas</div>
            </div>
            <div class="kpi">
                <div class="n">{{ $clientesAtendidos }}</div>
                <div class="l">Clientes Atendidos</div>
            </div>
            <div class="kpi">
                <div class="n">{{ $mascotasAtendidas }}</div>
                <div class="l">Mascotas Atendidas</div>
            </div>
        </div>

        {{-- Menú --}}
        <div class="sectionTitle">Opciones Disponibles</div>
        @php
            // Si alguna ruta aún no existe, mandamos a '#'
            $safe = fn($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
        @endphp
        <div class="menu">
            <div class="opt">
                <i class="fas fa-calendar-alt"></i>
                {{-- Tarjeta: Mis Citas (lleva a la Agenda filtrada por el vet) --}}
                <a href="{{ route('agenda.index') }}"
                   class="block rounded-2xl border border-gray-200 bg-white p-6 shadow hover:shadow-md transition">
                  <div class="text-2xl mb-2"></div>
                    <div class="text-lg font-semibold">Mis Citas</div>
                    <p class="text-sm text-gray-500 mt-1">Ver y atender mis citas asignadas.</p>
                     </a>
             </div>
             <div class="opt">
                <i class="fas fa-paw"></i>
                <a href="{{ $safe('mascotas.index') }}">Historial de Mascotas</a>
             </div>
             <div class="opt">
                <i class="fas fa-user-circle"></i>
                <a href="{{ $safe('clientes.index') }}">Mis Clientes</a>
             </div>
             <div class="opt">
  <i class="fas fa-file-prescription"></i>
  <a href="{{ route('vet.recetas.mine') }}">Recetas</a>
</div>

               <div class="opt">
        <i class="fas fa-file-medical"></i>
        <a href="{{ \Illuminate\Support\Facades\Route::has('vet.historias.mine') ? route('vet.historias.mine') : '#' }}">
            Mis Historias
        </a>
    </div>
    </div>
</x-app-layout>
