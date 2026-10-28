<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @php
        $link = fn($name) => \Illuminate\Support\Facades\Route::has($name) ? route($name) : '#';
        $has  = fn($name) => \Illuminate\Support\Facades\Route::has($name);
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        .dash {
            --ink:#182a2a; --ink-soft:#5a6f6e;
            --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
            --coral:#ff7a59; --coral-dark:#ef5c39; --amber:#f4b740;
            --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.12);
            --shadow-sm:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 14px 34px rgba(13,84,76,.14);
            --side:262px; --radius:18px;
            --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
            font-family:var(--fb); color:var(--ink);
            display:flex; min-height:100vh; background:var(--cream);
        }
        .dash * { box-sizing:border-box; }

        /* ---------- Sidebar ---------- */
        .dash-side {
            width:var(--side); flex-shrink:0; background:linear-gradient(165deg,var(--teal-700),var(--teal-900));
            color:#fff; padding:24px 16px; display:flex; flex-direction:column; gap:6px;
            position:sticky; top:0; height:100vh; transition:width .28s ease;
        }
        .dash-logo { display:flex; align-items:center; gap:10px; font-family:var(--fd); font-size:1.4rem; font-weight:600; padding:6px 10px 22px; white-space:nowrap; overflow:hidden; }
        .dash-logo .paw { font-size:1.5rem; }
        .dash-nav { display:flex; flex-direction:column; gap:4px; }
        .dash-nav a {
            display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:12px;
            color:rgba(255,255,255,.82); text-decoration:none; font-weight:600; font-size:.95rem; white-space:nowrap;
            transition:background .2s ease, color .2s ease;
        }
        .dash-nav a i { width:20px; text-align:center; font-size:1.05rem; }
        .dash-nav a:hover { background:rgba(255,255,255,.12); color:#fff; }
        .dash-nav a.active { background:#fff; color:var(--teal-900); box-shadow:var(--shadow-sm); }
        .dash-nav a.active i { color:var(--coral); }
        .dash-side .spacer { flex:1; }
        .dash-logout button {
            width:100%; display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:12px;
            background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); color:#fff; cursor:pointer;
            font-family:var(--fb); font-weight:600; font-size:.95rem; transition:background .2s ease;
        }
        .dash-logout button:hover { background:rgba(255,122,89,.85); border-color:transparent; }

        /* Colapsado (solo iconos) */
        .dash.collapsed .dash-side { width:78px; }
        .dash.collapsed .dash-logo span, 
        .dash.collapsed .dash-nav a span, 
        .dash.collapsed .dash-logout span { display:none; }
        .dash.collapsed .dash-nav a, .dash.collapsed .dash-logout button { justify-content:center; }

        /* ---------- Main ---------- */
        .dash-main { flex:1; min-width:0; padding:26px 34px 48px; }

        .dash-top { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:30px; flex-wrap:wrap; }
        .dash-top .greet h1 { font-family:var(--fd); font-weight:600; font-size:1.7rem; letter-spacing:-.02em; color:var(--teal-900); margin:0; }
        .dash-top .greet p { margin:2px 0 0; color:var(--ink-soft); font-size:.95rem; }
        .dash-top .tools { display:flex; align-items:center; gap:14px; }
        .dash-burger { background:var(--white); border:1px solid var(--line); width:42px; height:42px; border-radius:12px; cursor:pointer; color:var(--teal-700); font-size:1.1rem; box-shadow:var(--shadow-sm); }
        .dash-clock { text-align:right; }
        .dash-clock .t { font-family:var(--fd); font-weight:600; font-size:1.15rem; color:var(--teal-900); }
        .dash-clock .d { font-size:.82rem; color:var(--ink-soft); text-transform:capitalize; }
        .dash-avatar { width:46px; height:46px; border-radius:50%; background:linear-gradient(135deg,var(--coral),var(--coral-dark)); color:#fff; display:grid; place-items:center; font-weight:700; box-shadow:var(--shadow-sm); }

        /* KPIs */
        .kpi-row { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:30px; }
        .kpi-card {
            position:relative; color:#fff; border-radius:var(--radius); padding:24px 26px; overflow:hidden;
            box-shadow:var(--shadow-sm); transition:transform .25s ease, box-shadow .25s ease;
            background:linear-gradient(135deg,var(--teal-600),var(--teal-900));
        }
        .kpi-card.c2 { background:linear-gradient(135deg,var(--teal-500),var(--teal-700)); }
        .kpi-card.c3 { background:linear-gradient(135deg,var(--coral),var(--coral-dark)); }
        .kpi-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-md); }
        .kpi-card .ic { position:absolute; right:18px; top:18px; font-size:2.4rem; opacity:.22; }
        .kpi-card .num { font-family:var(--fd); font-size:2.7rem; font-weight:600; line-height:1; }
        .kpi-card .lbl { margin-top:6px; font-weight:500; opacity:.95; }

        /* Quick actions */
        .qa-row { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:34px; }
        .qa-btn {
            position:relative; overflow:hidden; display:inline-flex; align-items:center; gap:10px;
            padding:13px 20px; border-radius:999px; border:none; cursor:pointer; text-decoration:none;
            font-family:var(--fb); font-weight:700; font-size:.95rem; transition:transform .2s ease, box-shadow .2s ease;
        }
        .qa-btn.primary { background:var(--coral); color:#fff; box-shadow:0 10px 22px rgba(255,122,89,.32); }
        .qa-btn.ghost { background:var(--white); color:var(--teal-700); border:1px solid var(--line); box-shadow:var(--shadow-sm); }
        .qa-btn:hover { transform:translateY(-2px); }

        /* Módulos */
        .mod-head { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:18px; flex-wrap:wrap; }
        .mod-head h2 { font-family:var(--fd); font-weight:600; font-size:1.25rem; color:var(--teal-900); margin:0; }
        .mod-search { position:relative; }
        .mod-search input {
            width:260px; max-width:60vw; padding:11px 14px 11px 40px; border-radius:999px; border:1px solid var(--line);
            background:var(--white); font-family:var(--fb); font-size:.92rem; color:var(--ink); outline:none;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        .mod-search input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }
        .mod-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--ink-soft); }

        .mod-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:20px; }
        .mod-card {
            position:relative; overflow:hidden; display:flex; flex-direction:column; gap:6px;
            background:var(--white); border:1px solid var(--line); border-radius:var(--radius); padding:24px;
            text-decoration:none; color:var(--ink); box-shadow:var(--shadow-sm);
            transition:transform .25s ease, box-shadow .25s ease;
        }
        .mod-card::before { content:""; position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg,var(--teal-500),var(--coral)); transform:scaleX(0); transform-origin:left; transition:transform .3s ease; }
        .mod-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-md); }
        .mod-card:hover::before { transform:scaleX(1); }
        .mod-card .mi { width:48px; height:48px; border-radius:14px; display:grid; place-items:center; font-size:1.3rem; color:var(--teal-700); background:var(--teal-50); margin-bottom:8px; transition:background .25s ease, color .25s ease; }
        .mod-card:hover .mi { background:var(--coral); color:#fff; }
        .mod-card .mt { font-weight:700; font-size:1.05rem; }
        .mod-card .md { font-size:.85rem; color:var(--ink-soft); }
        .mod-empty { color:var(--ink-soft); padding:20px 4px; display:none; }

        .ripple { position:absolute; border-radius:50%; transform:scale(0); background:rgba(255,255,255,.45); animation:rip .6s linear; pointer-events:none; }
        @keyframes rip { to { transform:scale(3.5); opacity:0; } }

        /* Backdrop móvil */
        .dash-backdrop { display:none; position:fixed; inset:0; background:rgba(8,40,37,.45); z-index:40; }

        @media (max-width:900px) {
            .dash-side { position:fixed; left:0; top:0; z-index:50; transform:translateX(-100%); transition:transform .28s ease; }
            .dash.open .dash-side { transform:translateX(0); }
            .dash.open .dash-backdrop { display:block; }
            .dash-main { padding:20px 18px 40px; }
            .dash-burger { display:inline-block; }
        }
        @media (min-width:901px) { .dash-burger { display:none; } }
        @media (prefers-reduced-motion:reduce) { .dash *, .ripple { animation:none !important; transition:none !important; } }
    </style>

    <div class="dash" id="dash">
        {{-- ===== Sidebar ===== --}}
        <aside class="dash-side">
            <div class="dash-logo"><span class="paw">🐾</span><span>Lugo Vet</span></div>
            <nav class="dash-nav">
                <a href="{{ $link('admin.dashboard') }}" class="active"><i class="fas fa-gauge-high"></i><span>Inicio</span></a>
                @if($has('admin.users.index'))<a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i><span>Usuarios</span></a>@endif
                @if($has('citas.index'))<a href="{{ route('citas.index') }}"><i class="fas fa-calendar-check"></i><span>Citas</span></a>@endif
                @if($has('agenda.index'))<a href="{{ route('agenda.index') }}"><i class="fas fa-calendar-day"></i><span>Agenda</span></a>@endif
                @if($has('mascotas.index'))<a href="{{ route('mascotas.index') }}"><i class="fas fa-paw"></i><span>Mascotas</span></a>@endif
                @if($has('historias.index'))<a href="{{ route('historias.index') }}"><i class="fas fa-file-medical"></i><span>Historias</span></a>@endif
                @if($has('caja.index'))<a href="{{ route('caja.index') }}"><i class="fas fa-cash-register"></i><span>Caja</span></a>@endif
            </nav>
            <div class="spacer"></div>
            <div class="dash-logout">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"><i class="fas fa-arrow-right-from-bracket"></i><span>Cerrar sesión</span></button>
                </form>
            </div>
        </aside>

        <div class="dash-backdrop" id="dashBackdrop"></div>

        {{-- ===== Main ===== --}}
        <main class="dash-main">
            <div class="dash-top">
                <div style="display:flex;align-items:center;gap:14px;">
                    <button class="dash-burger" id="dashBurger" aria-label="Menú"><i class="fas fa-bars"></i></button>
                    <div class="greet">
                        <h1 id="greetText">Hola, {{ auth()->user()->name }}</h1>
                        <p>Panel de administración · Lugo Vet</p>
                    </div>
                </div>
                <div class="tools">
                    <div class="dash-clock">
                        <div class="t" id="clockTime">--:--</div>
                        <div class="d" id="clockDate">—</div>
                    </div>
                    <div class="dash-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </div>

            {{-- KPIs --}}
            <section class="kpi-row">
                <div class="kpi-card c1"><i class="fas fa-users ic"></i><div class="num" data-count="{{ (int) $usuariosRegistrados }}">0</div><div class="lbl">Usuarios registrados</div></div>
                <div class="kpi-card c2"><i class="fas fa-user-check ic"></i><div class="num" data-count="{{ (int) $clientesActivos }}">0</div><div class="lbl">Clientes activos</div></div>
                <div class="kpi-card c3"><i class="fas fa-calendar-check ic"></i><div class="num" data-count="{{ (int) $citasProgramadas }}">0</div><div class="lbl">Citas programadas</div></div>
                <div class="kpi-card c1"><i class="fas fa-sack-dollar ic"></i><div class="num" style="font-size:2rem">${{ number_format($cobradoMes ?? 0, 0, ',', '.') }}</div><div class="lbl">Cobrado este mes ({{ $facturasPagadasMes ?? 0 }} facturas)</div></div>
            </section>

            {{-- Acciones rápidas --}}
            <section class="qa-row">
                @if($has('citas.create'))<a class="qa-btn primary ripple-host" href="{{ route('citas.create') }}"><i class="fas fa-plus"></i> Nueva cita</a>@endif
                @if($has('mascotas.create'))<a class="qa-btn ghost ripple-host" href="{{ route('mascotas.create') }}"><i class="fas fa-paw"></i> Nueva mascota</a>@endif
                @if($has('admin.users.index'))<a class="qa-btn ghost ripple-host" href="{{ route('admin.users.index') }}"><i class="fas fa-user-plus"></i> Gestionar usuarios</a>@endif
            </section>

            {{-- Módulos --}}
            <div class="mod-head">
                <h2>Módulos</h2>
                <div class="mod-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="modSearch" placeholder="Buscar módulo...">
                </div>
            </div>
            <section class="mod-grid" id="modGrid">
                @if($has('admin.users.index'))
                <a class="mod-card ripple-host" data-name="usuarios gestionar" href="{{ route('admin.users.index') }}">
                    <div class="mi"><i class="fas fa-users"></i></div><div class="mt">Gestionar Usuarios</div><div class="md">Crear, editar y eliminar cuentas.</div></a>
                @endif
                @if($has('citas.index'))
                <a class="mod-card ripple-host" data-name="citas agenda" href="{{ route('citas.index') }}">
                    <div class="mi"><i class="fas fa-calendar-check"></i></div><div class="mt">Citas</div><div class="md">Programa y consulta citas.</div></a>
                @endif
                @if($has('agenda.index'))
                <a class="mod-card ripple-host" data-name="agenda dia" href="{{ route('agenda.index') }}">
                    <div class="mi"><i class="fas fa-calendar-day"></i></div><div class="mt">Agenda</div><div class="md">Vista diaria de la clínica.</div></a>
                @endif
                @if($has('mascotas.index'))
                <a class="mod-card ripple-host" data-name="mascotas pacientes" href="{{ route('mascotas.index') }}">
                    <div class="mi"><i class="fas fa-paw"></i></div><div class="mt">Mascotas</div><div class="md">Pacientes registrados.</div></a>
                @endif
                @if($has('historias.index'))
                <a class="mod-card ripple-host" data-name="historias clinicas" href="{{ route('historias.index') }}">
                    <div class="mi"><i class="fas fa-file-medical"></i></div><div class="mt">Historias</div><div class="md">Historias clínicas.</div></a>
                @endif
                @if($has('caja.index'))
                <a class="mod-card ripple-host" data-name="caja facturacion pagos" href="{{ route('caja.index') }}">
                    <div class="mi"><i class="fas fa-cash-register"></i></div><div class="mt">Caja</div><div class="md">Facturación y pagos.</div></a>
                @endif
            </section>
            <p class="mod-empty" id="modEmpty">No se encontraron módulos con ese nombre.</p>
        </main>
    </div>

    <script>
        (function () {
            const dash = document.getElementById('dash');

            // --- Saludo según la hora ---
            const h = new Date().getHours();
            const saludo = h < 12 ? 'Buenos días' : (h < 19 ? 'Buenas tardes' : 'Buenas noches');
            const nombre = @json(auth()->user()->name);
            document.getElementById('greetText').textContent = `${saludo}, ${nombre}`;

            // --- Reloj en vivo ---
            const tEl = document.getElementById('clockTime');
            const dEl = document.getElementById('clockDate');
            function tick() {
                const now = new Date();
                tEl.textContent = now.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });
                dEl.textContent = now.toLocaleDateString('es-CO', { weekday: 'long', day: 'numeric', month: 'long' });
            }
            tick(); setInterval(tick, 1000);

            // --- Conteo animado de KPIs ---
            function countUp(el) {
                const target = parseInt(el.dataset.count, 10) || 0;
                const dur = 1100; const start = performance.now();
                function step(now) {
                    const p = Math.min((now - start) / dur, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('es-CO');
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }
            document.querySelectorAll('.kpi-card .num').forEach(countUp);

            // --- Buscador de módulos ---
            const search = document.getElementById('modSearch');
            const cards = Array.from(document.querySelectorAll('#modGrid .mod-card'));
            const empty = document.getElementById('modEmpty');
            search.addEventListener('input', () => {
                const q = search.value.trim().toLowerCase();
                let visible = 0;
                cards.forEach(c => {
                    const txt = (c.dataset.name + ' ' + c.textContent).toLowerCase();
                    const show = txt.includes(q);
                    c.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                empty.style.display = visible ? 'none' : 'block';
            });

            // --- Sidebar (colapsar en escritorio, drawer en móvil) ---
            const burger = document.getElementById('dashBurger');
            const backdrop = document.getElementById('dashBackdrop');
            burger.addEventListener('click', () => {
                if (window.innerWidth <= 900) dash.classList.toggle('open');
                else dash.classList.toggle('collapsed');
            });
            backdrop.addEventListener('click', () => dash.classList.remove('open'));

            // --- Efecto ripple ---
            document.querySelectorAll('.ripple-host').forEach(host => {
                host.addEventListener('click', e => {
                    const r = document.createElement('span');
                    r.className = 'ripple';
                    const rect = host.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    r.style.width = r.style.height = size + 'px';
                    r.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    r.style.top = (e.clientY - rect.top - size / 2) + 'px';
                    host.appendChild(r);
                    setTimeout(() => r.remove(), 600);
                });
            });
        })();
    </script>
</x-app-layout>