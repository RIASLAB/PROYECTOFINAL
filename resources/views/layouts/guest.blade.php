<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lugo Vet') }}</title>

    <link rel="icon" href="{{ asset('images/logo.ico') }}" type="image/x-icon">

    {{-- Tipografías de marca --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Scripts de Breeze --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ============================================================
         ESTILOS DE AUTENTICACIÓN (incrustados para no depender de archivos)
         ============================================================ --}}
    <style>
        :root {
            --ink: #182a2a; --ink-soft: #44595a;
            --teal-900: #0a4f48; --teal-700: #0d6e63; --teal-600: #14857a; --teal-500: #1aa394; --teal-50: #e7f5f2;
            --coral: #ff7a59; --coral-dark: #ef5c39; --cream: #faf6ef; --white: #fff;
            --line: rgba(20,60,56,.14);
            --font-display: "Fraunces", Georgia, serif;
            --font-body: "Plus Jakarta Sans", system-ui, sans-serif;
        }
        * { box-sizing: border-box; }
        body.auth-body { margin: 0; font-family: var(--font-body); color: var(--ink); background: var(--cream); -webkit-font-smoothing: antialiased; }

        .auth-shell { display: grid; grid-template-columns: 1.05fr 1fr; min-height: 100vh; }

        .auth-brand {
            position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: center;
            padding: 56px; color: #fff;
            background:
                radial-gradient(900px 420px at 20% 10%, rgba(255,122,89,.35), transparent 55%),
                linear-gradient(150deg, var(--teal-700), var(--teal-900));
        }
        .auth-brand__inner { position: relative; z-index: 2; max-width: 420px; }
        .auth-logo { display: inline-flex; align-items: center; gap: 8px; font-family: var(--font-display); font-size: 1.6rem; font-weight: 600; color: #fff; text-decoration: none; margin-bottom: 40px; }
        .auth-brand__title { font-family: var(--font-display); font-weight: 600; font-size: clamp(1.8rem,3vw,2.7rem); line-height: 1.1; letter-spacing: -.02em; margin: 0 0 16px; }
        .auth-brand__text { font-size: 1.05rem; line-height: 1.6; color: rgba(255,255,255,.9); margin: 0; }
        .auth-brand__paw { position: absolute; right: -40px; bottom: -50px; font-size: 18rem; opacity: .08; transform: rotate(-18deg); z-index: 1; user-select: none; }

        .auth-panel { display: flex; align-items: center; justify-content: center; padding: 48px 24px; }
        .auth-card { width: 100%; max-width: 420px; }
        .auth-card__logo { display: none; font-family: var(--font-display); font-size: 1.4rem; font-weight: 600; color: var(--teal-700); text-decoration: none; margin-bottom: 24px; }

        .auth-title { font-family: var(--font-display); font-weight: 600; font-size: 2rem; letter-spacing: -.02em; color: var(--teal-900); margin: 0 0 6px; }
        .auth-subtitle { font-size: 1rem; color: var(--ink-soft); margin: 0 0 30px; }

        .auth-form { display: flex; flex-direction: column; gap: 18px; }
        .auth-field { display: flex; flex-direction: column; gap: 7px; }
        .auth-field label { display: block; font-size: .9rem; font-weight: 600; color: var(--ink); }

        .auth-form input:not([type="checkbox"]) {
            width: 100%; padding: 12px 15px;
            font-family: var(--font-body); font-size: .98rem; color: var(--ink);
            background: var(--white); border: 1.5px solid var(--line); border-radius: 12px;
            box-shadow: none; transition: border-color .2s ease, box-shadow .2s ease;
        }
        .auth-form input:not([type="checkbox"])::placeholder { color: #9bb0ad; }
        .auth-form input:not([type="checkbox"]):focus { outline: none; border-color: var(--teal-500); box-shadow: 0 0 0 4px rgba(26,163,148,.16); }

        .auth-error { color: var(--coral-dark); font-size: .85rem; margin: 2px 0 0; padding: 0; list-style: none; }
        .auth-error li { margin: 0; }
        .auth-status { background: var(--teal-50); color: var(--teal-700); font-size: .9rem; font-weight: 600; padding: 10px 14px; border-radius: 10px; margin-bottom: 18px; }

        .auth-row { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-top: -2px; }
        .auth-remember { display: inline-flex; align-items: center; gap: 8px; font-size: .9rem; color: var(--ink-soft); cursor: pointer; }
        .auth-remember input { width: 16px; height: 16px; accent-color: var(--teal-600); }
        .auth-link { font-size: .9rem; font-weight: 600; color: var(--teal-600); text-decoration: none; transition: color .2s ease; }
        .auth-link:hover { color: var(--coral); }

        .auth-btn {
            margin-top: 6px; width: 100%; padding: 13px 20px;
            font-family: var(--font-body); font-weight: 700; font-size: 1rem; color: #fff;
            background: var(--teal-600); border: none; border-radius: 12px; cursor: pointer;
            box-shadow: 0 10px 24px rgba(13,110,99,.28);
            transition: transform .2s ease, background .2s ease, box-shadow .2s ease;
        }
        .auth-btn:hover { background: var(--teal-700); transform: translateY(-2px); box-shadow: 0 16px 30px rgba(13,110,99,.34); }
        .auth-btn:active { transform: translateY(0); }

        .auth-foot { text-align: center; font-size: .92rem; color: var(--ink-soft); margin: 8px 0 0; }

        @media (max-width: 880px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
            .auth-card__logo { display: inline-block; }
            .auth-panel { padding: 40px 22px; min-height: 100vh; }
        }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>
<body class="auth-body">
    <div class="auth-shell">
        {{-- Panel de marca --}}
        <aside class="auth-brand">
            <div class="auth-brand__inner">
                <a href="/" class="auth-logo">🐾 Lugo Vet</a>
                <h2 class="auth-brand__title">Cuidamos lo que más quieres</h2>
                <p class="auth-brand__text">
                    Gestiona clientes, mascotas, citas y servicios de tu clínica
                    desde un solo lugar, de forma ágil y organizada.
                </p>
            </div>
            <div class="auth-brand__paw" aria-hidden="true">🐾</div>
        </aside>

        {{-- Panel de formulario --}}
        <main class="auth-panel">
            <div class="auth-card">
                <a href="/" class="auth-card__logo">🐾 Lugo Vet</a>
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>