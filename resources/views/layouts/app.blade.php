<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
  // Fondo claro para todas las vistas (sin modo oscuro)
  $bodyClasses = 'font-sans antialiased bg-gray-100 text-gray-900';
@endphp


<body class="{{ $bodyClasses }}">

@php
  $hideNav = $hideNav ?? (
    request()->routeIs('admin.*') ||
    request()->routeIs('vet.*')   ||
    request()->routeIs('client.*')
  );
@endphp


  {{-- Mensajes de confirmación y error (heurística 1: visibilidad del estado del sistema).
       Las vistas que ya muestran el mensaje en su propio diseño marcan app('flash.inline'). --}}
  @php
    $flashInline = app()->bound('flash.inline') ? app('flash.inline') : [];
    $flashes = [];
    foreach (['ok' => 'ok', 'success' => 'ok', 'error' => 'error'] as $key => $type) {
        if (session($key) && !in_array($key, $flashInline, true)) {
            $flashes[] = [$type, session($key)];
        }
    }
  @endphp
  @if($flashes)
    <div id="lv-toasts" style="position:fixed;top:18px;right:18px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:380px">
      @foreach($flashes as [$type, $msg])
        <div role="{{ $type === 'error' ? 'alert' : 'status' }}"
             style="display:flex;gap:10px;align-items:flex-start;padding:14px 16px;border-radius:14px;font:500 15px/1.4 system-ui,sans-serif;box-shadow:0 10px 28px rgba(0,0,0,.14);{{ $type === 'error' ? 'background:#fdece8;color:#9a2d12;border:1px solid #ff7a59' : 'background:#e7f5f2;color:#0a4f48;border:1px solid #1aa394' }}">
          <span aria-hidden="true" style="font-weight:800">{{ $type === 'error' ? '!' : '✓' }}</span>
          <span style="flex:1">{{ $msg }}</span>
          <button type="button" onclick="this.parentElement.remove()" aria-label="Cerrar" style="background:none;border:0;cursor:pointer;font-size:18px;line-height:1;color:inherit">×</button>
        </div>
      @endforeach
    </div>
    <script>setTimeout(function () { var t = document.getElementById('lv-toasts'); if (t) t.remove(); }, 6000);</script>
  @endif

<div class="min-h-screen">
  @unless($hideNav)
    @include('layouts.navigation')
  @endunless

  {{-- Page Heading opcional --}}
  @isset($header)
    @unless($hideNav)
      <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endunless
  @endisset

  {{-- Page Content (UN SOLO MAIN) --}}
  <main class="min-h-[calc(100vh-65px)]">
    @hasSection('content')
      @yield('content')
    @else
      {{ $slot ?? '' }}
    @endif
  </main>
</div>
</body>
</html>
