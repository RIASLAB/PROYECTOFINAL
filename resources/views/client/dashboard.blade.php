<x-app-layout>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/client-dashboard.css') }}">

  {{-- Barra superior --}}
  <nav class="client-nav">
    <h1 class="brand">Lugo Vet</h1>
    <div class="user-info">
      <span>{{ auth()->user()->name }} (Cliente)</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
      </form>
    </div>
  </nav>

  <div class="client-wrap">
    {{-- Bienvenida --}}
    <section class="welcome-card">
      <h2>¡Bienvenido, {{ auth()->user()->name }}!</h2>
      <p>
        Nos alegra tenerte en Lugo Vet. Aquí podrás gestionar tus citas, ver tus mascotas y
        actualizar tu información.
      </p>
    </section>

    {{-- Menú --}}
    @php
      $safe = fn($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
    @endphp
    <section class="menu-grid">
      <a href="{{ $safe('citas.index') }}" class="menu-item">
        <i class="fas fa-calendar-check"></i>
        <span>Ver mis citas</span>
      </a>

      <a href="{{ $safe('citas.create') }}" class="menu-item">
        <i class="fas fa-plus-circle"></i>
        <span>Agendar nueva cita</span>
      </a>

      <a href="{{ $safe('mascotas.index') }}" class="menu-item">
        <i class="fas fa-paw"></i>
        <span>Mis mascotas</span>
      </a>

      
      


    </section>

    <footer class="client-footer">
      &copy; {{ now()->year }} Lugo Vet. Todos los derechos reservados.
    </footer>
  </div>
</x-app-layout>
