<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lugo Vet - Sistema de Gestión</title>

  <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">

  {{-- Tipografías --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/estilos.css') }}">
  <link rel="stylesheet" href="{{ asset('css/modooscuro.css') }}">
</head>
<body>
  <!-- Header -->
  <header id="site-header">
    <nav class="navbar">
      <h1 class="logo">🐾 Lugo Vet</h1>

      <ul class="nav-links">
        <li><a href="#inicio">Inicio</a></li>
        <li><a href="#quienes">Quiénes Somos</a></li>
        <li><a href="#acerca">Acerca</a></li>
        <li><a href="#servicios">Servicios</a></li>
        <li><a href="#equipo">Equipo</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>

      <div style="display:flex; align-items:center; gap:12px;">
        {{-- Botón modo oscuro --}}
        <button id="darkModeToggle" aria-label="Cambiar tema">🌙</button>

        {{-- Zona de sesión --}}
        <div class="session-area">
          @guest
            <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
          @endguest

          @auth
            @if(auth()->user()->role === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="btn-login">Panel Admin</a>
            @else
              <a href="{{ route('dashboard') }}" class="btn-login">Mi Panel</a>
            @endif
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:inline">
              @csrf
              <button type="submit" class="btn-register">Cerrar sesión</button>
            </form>
          @endauth
        </div>
      </div>
    </nav>
  </header>

  <!-- Hero -->
  <section id="inicio" class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <span class="eyebrow">Clínica veterinaria · Florida, Valle del Cauca</span>
      <h2>Cuidamos lo que más quieres 🐾</h2>
      <p>Tu mascota merece lo mejor. En Lugo Vet ofrecemos consulta, vacunación, ayudas diagnósticas y cirugía con profesionales dedicados.</p>

      <div class="botones-hero">
        <a href="#servicios" class="btn">Ver servicios</a>

        @guest
          <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
          <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
        @endguest

        @auth
          @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="btn-login">Ir al Panel Admin</a>
          @else
            <a href="{{ route('dashboard') }}" class="btn-login">Ir a mi Panel</a>
          @endif
        @endauth
      </div>
    </div>
    <div class="scroll-cue" aria-hidden="true"></div>
  </section>

  <!-- Quiénes somos -->
  <section id="quienes" class="section reveal">
    <span class="eyebrow-label">Quiénes somos</span>
    <h2>Pasión por el bienestar animal</h2>
    <p>
      En Lugo Vet nos apasiona el bienestar animal. Somos una clínica veterinaria ubicada en Florida, Valle del Cauca,
      con un equipo comprometido en ofrecer un servicio humano, profesional y de calidad para cada mascota.
    </p>
  </section>

  <!-- Servicios -->
  <section id="servicios" class="section alt reveal">
    <span class="eyebrow-label">Lo que hacemos</span>
    <h2>Nuestros Servicios</h2>
    <div class="cards">
      <div class="card">
        <img src="{{ asset('images/servicios/consulta.svg') }}" alt="Consulta general">
        <h3>Consulta general</h3>
        <p>Valoración médica de tu mascota por nuestros veterinarios, por turnos y con historia clínica.</p>
      </div>
      <div class="card">
        <img src="{{ asset('images/servicios/vacunacion.svg') }}" alt="Vacunación">
        <h3>Vacunación</h3>
        <p>Planes de vacunación y desparasitación para perros y gatos en cada etapa de su vida.</p>
      </div>
      <div class="card">
        <img src="{{ asset('images/servicios/diagnostico.svg') }}" alt="Ecografía y radiografía">
        <h3>Ecografía y radiografía</h3>
        <p>Ayudas diagnósticas para conocer a tiempo el estado de salud de tu mascota.</p>
      </div>
      <div class="card">
        <img src="{{ asset('images/servicios/cirugia.svg') }}" alt="Cirugía">
        <h3>Cirugía</h3>
        <p>Procedimientos quirúrgicos con valoración previa y seguimiento posoperatorio.</p>
      </div>
    </div>
  </section>

  <!-- Acerca -->
  <section id="acerca" class="section reveal">
    <span class="eyebrow-label">El proyecto</span>
    <h2>Acerca de Nosotros</h2>
    <p>
      Nuestro proyecto integrador busca modernizar la gestión de la veterinaria mediante un sistema web
      que permite administrar clientes, mascotas, citas y servicios de forma ágil y organizada.
    </p>
  </section>

  <!-- Equipo -->
  <section id="equipo" class="section alt reveal">
    <span class="eyebrow-label">Conoce a</span>
    <h2>Nuestro Equipo</h2>
    <div class="cards">
      <div class="card">
        <img src="{{ asset('images/dueño1.png') }}" alt="Luis Fernando Majin">
        <h3>Luis Fernando Majin</h3>
        <p>Estudiante de la UNIAJC</p>
      </div>
      <div class="card">
        <img src="{{ asset('images/dueño2.jpeg') }}" alt="Jhon Edinson Riascos">
        <h3>Jhon Edinson Riascos</h3>
        <p>Estudiante de la UNIAJC</p>
      </div>
      <div class="card">
        <img src="{{ asset('images/dueño3.jpeg') }}" alt="Ruben Darley Mina">
        <h3>Ruben Darley Mina</h3>
        <p>Estudiante de la UNIAJC</p>
      </div>
    </div>
  </section>

  <!-- Mapa -->
  <section id="mapa" class="section reveal">
    <span class="eyebrow-label">Visítanos</span>
    <h2>¿Cómo llegar?</h2>
    <p>Estamos ubicados en Florida, Valle del Cauca. ¡Ven y visítanos!</p>
    <div class="map-container">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d995.7435019678264!2d-76.30015115286761!3d3.356511102408202!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2sco!4v1756695466644!5m2!1ses-419!2sco"
        width="100%" height="400" style="border:0;"
        allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </section>

  <!-- Contacto -->
  <footer id="contacto">
    <h2>Contacto</h2>
    <p>📍 Cl. 10 #18-37, Florida, Valle del Cauca</p>
    <p>📧 contacto@lugovet.com &nbsp;·&nbsp; 📞 +57 314 551 7341</p>
    <p>&copy; 2025 Lugo Vet — Proyecto Integrador</p>
  </footer>

  {{-- Header sólido al hacer scroll + animaciones de aparición --}}
  <script>
    (function () {
      const header = document.getElementById('site-header');
      const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 40);
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });

      const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); } });
      }, { threshold: 0.12 });
      document.querySelectorAll('.reveal').forEach(el => io.observe(el));
    })();
  </script>

  <script src="{{ asset('js/script.js') }}"></script>
  <script src="{{ asset('js/modooscuro.js') }}"></script>
</body>
</html>