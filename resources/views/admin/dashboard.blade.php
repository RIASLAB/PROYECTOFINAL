<x-app-layout>
    {{-- CSS del panel admin (no interfiere con Breeze) --}}
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">

    <div class="admin-wrap">
        {{-- Barra azul superior --}}
        <div class="admin-container">
            <div class="admin-topbar">
                <div class="admin-topbar__row">
                    <div class="admin-brand">Lugo Vet</div>
                    <div class="badge-session">
                        (administrador)
                        <form method="POST" action="{{ route('logout') }}" style="display:inline">
                            @csrf
                            <button type="submit">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-container">
            {{-- Bienvenida --}}
            <div class="box-welcome">
                <div class="title">¡Bienvenido, Administrador {{ auth()->user()->name }} Lugo Vet!</div>
                <div class="sub">Rol: Administrador</div>
            </div>

            {{-- KPIs (3 tarjetas azules) --}}
            <div class="kpi-grid">
                <div class="kpi k1">
                    <div class="num">{{ $usuariosRegistrados }}</div>
                    <div class="label">Usuarios Registrados</div>
                </div>
                <div class="kpi k2">
                    <div class="num">{{ $clientesActivos }}</div>
                    <div class="label">Clientes Activos</div>
                </div>
                <div class="kpi k3">
                    <div class="num">{{ $citasProgramadas }}</div>
                    <div class="label">Citas Programadas</div>
                </div>
            </div>

            {{-- Opciones disponibles (8 items) --}}
            <div class="section-title">Opciones Disponibles</div>
            @php
                $link = fn($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
            @endphp
            <div class="grid-opts">
                <a class="opt" href="{{ $link('admin.users.index') }}">
                    <img src="{{ asset('images/icons/users.png') }}" alt="">
                    <div class="t">Gestionar Usuarios</div>
                </a>
                <a class="opt" href="{{ $link('citas.index') }}">
                    <img src="{{ asset('images/icons/calendar.png') }}" alt="">
                    <div class="t">Citas</div>
                </a>
                <a class="opt" href="{{ $link('clientes.index') }}">
                    <img src="{{ asset('images/icons/clients.png') }}" alt="">
                    <div class="t">Clientes</div>
                </a>
                <a class="opt" href="{{ $link('mascotas.index') }}">
                    <img src="{{ asset('images/icons/paw.png') }}" alt="">
                    <div class="t">Mascotas</div>
                </a>
                <a class="opt" href="{{ $link('citas.pdf') }}">
                    <img src="{{ asset('images/icons/report.png') }}" alt="">
                    <div class="t">Reportes</div>
                </a>
                <a class="opt" href="{{ $link('servicios.index') }}">
                    <img src="{{ asset('images/icons/services.png') }}" alt="">
                    <div class="t">Servicios</div>
                </a>
                <a class="opt" href="{{ $link('veterinarios.index') }}">
                    <img src="{{ asset('images/icons/stethoscope.png') }}" alt="">
                    <div class="t">Veterinarios</div>
                </a>
                <a class="opt" href="{{ $link('facturas.index') }}">
                    <img src="{{ asset('images/icons/invoice.png') }}" alt="">
                    <div class="t">Facturas</div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
