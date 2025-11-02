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

      <a href="{{ $safe('profile.edit') }}" class="menu-item">
        <i class="fas fa-user-edit"></i>
        <span>Editar mi perfil</span>
      </a>
      <a href="{{ route('client.historias.mine') }}" class="block">
  <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100 p-6 text-center">
    <div class="mx-auto mb-3 w-12 h-12 flex items-center justify-center rounded-full bg-indigo-50">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M19.5 14.25v2.25A2.25 2.25 0 0 1 17.25 18.75H6.75A2.25 2.25 0 0 1 4.5 16.5V7.5A2.25 2.25 0 0 1 6.75 5.25h5.25L16.5 9v.75" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
          d="M8.25 12.75h7.5M8.25 15.75h4.5" />
      </svg>
    </div>
    <h3 class="font-semibold text-teal-700">Historias</h3>
    <p class="text-sm text-gray-500 mt-1">De mis mascotas.</p>
  </div>
</a>



    </section>

    <footer class="client-footer">
      &copy; {{ now()->year }} Lugo Vet. Todos los derechos reservados.
    </footer>
  </div>
</x-app-layout>
