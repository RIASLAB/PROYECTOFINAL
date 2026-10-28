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
        .dash-side { width:var(--side); flex-shrink:0; background:linear-gradient(165deg,var(--teal-700),var(--teal-900)); color:#fff; padding:24px 16px; display:flex; flex-direction:column; gap:6px; position:sticky; top:0; height:100vh; transition:width .28s ease; }
        .dash-logo { display:flex; align-items:center; gap:10px; font-family:var(--fd); font-size:1.4rem; font-weight:600; padding:6px 10px 22px; white-space:nowrap; overflow:hidden; }
        .dash-nav { display:flex; flex-direction:column; gap:4px; }
        .dash-nav a { display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:12px; color:rgba(255,255,255,.82); text-decoration:none; font-weight:600; font-size:.95rem; white-space:nowrap; transition:background .2s, color .2s; }
        .dash-nav a i { width:20px; text-align:center; font-size:1.05rem; }
        .dash-nav a:hover { background:rgba(255,255,255,.12); color:#fff; }
        .dash-nav a.active { background:#fff; color:var(--teal-900); box-shadow:var(--shadow-sm); }
        .dash-nav a.active i { color:var(--coral); }
        .dash-side .spacer { flex:1; }
        .dash-logout button { width:100%; display:flex; align-items:center; gap:14px; padding:12px 14px; border-radius:12px; background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2); color:#fff; cursor:pointer; font-family:var(--fb); font-weight:600; font-size:.95rem; transition:background .2s; }
        .dash-logout button:hover { background:rgba(255,122,89,.85); border-color:transparent; }
        .dash.collapsed .dash-side { width:78px; }
        .dash.collapsed .dash-logo span, .dash.collapsed .dash-nav a span, .dash.collapsed .dash-logout span { display:none; }
        .dash.collapsed .dash-nav a, .dash.collapsed .dash-logout button { justify-content:center; }

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

        .welcome-banner { background:radial-gradient(600px 240px at 90% 0%, rgba(255,122,89,.18), transparent 60%), var(--white); border:1px solid var(--line); border-radius:var(--radius); padding:26px 28px; box-shadow:var(--shadow-sm); margin-bottom:30px; }
        .welcome-banner h2 { font-family:var(--fd); font-weight:600; font-size:1.4rem; color:var(--teal-900); margin:0 0 6px; }
        .welcome-banner p { margin:0; color:var(--ink-soft); max-width:640px; }

        .mod-head { display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:18px; flex-wrap:wrap; }
        .mod-head h2 { font-family:var(--fd); font-weight:600; font-size:1.25rem; color:var(--teal-900); margin:0; }
        .mod-search { position:relative; }
        .mod-search input { width:260px; max-width:60vw; padding:11px 14px 11px 40px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-family:var(--fb); font-size:.92rem; color:var(--ink); outline:none; transition:border-color .2s, box-shadow .2s; }
        .mod-search input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }
        .mod-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--ink-soft); }

        .mod-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:20px; }
        .mod-card { position:relative; overflow:hidden; display:flex; flex-direction:column; gap:6px; background:var(--white); border:1px solid var(--line); border-radius:var(--radius); padding:24px; text-decoration:none; color:var(--ink); box-shadow:var(--shadow-sm); transition:transform .25s, box-shadow .25s; }
        .mod-card::before { content:""; position:absolute; top:0; left:0; width:100%; height:4px; background:linear-gradient(90deg,var(--teal-500),var(--coral)); transform:scaleX(0); transform-origin:left; transition:transform .3s; }
        .mod-card:hover { transform:translateY(-6px); box-shadow:var(--shadow-md); }
        .mod-card:hover::before { transform:scaleX(1); }
        .mod-card .mi { width:48px; height:48px; border-radius:14px; display:grid; place-items:center; font-size:1.3rem; color:var(--teal-700); background:var(--teal-50); margin-bottom:8px; transition:background .25s, color .25s; }
        .mod-card:hover .mi { background:var(--coral); color:#fff; }
        .mod-card .mt { font-weight:700; font-size:1.05rem; }
        .mod-card .md { font-size:.85rem; color:var(--ink-soft); }
        .mod-empty { color:var(--ink-soft); padding:20px 4px; display:none; }
        .ripple { position:absolute; border-radius:50%; transform:scale(0); background:rgba(255,255,255,.45); animation:rip .6s linear; pointer-events:none; }
        @keyframes rip { to { transform:scale(3.5); opacity:0; } }
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
        <aside class="dash-side">
            <div class="dash-logo"><span>🐾</span><span>Lugo Vet</span></div>
            <nav class="dash-nav">
                <a href="{{ $link('client.dashboard') }}" class="active"><i class="fas fa-gauge-high"></i><span>Inicio</span></a>
                @if($has('citas.index'))<a href="{{ route('citas.index') }}"><i class="fas fa-calendar-check"></i><span>Mis citas</span></a>@endif
                @if($has('citas.create'))<a href="{{ route('citas.create') }}"><i class="fas fa-plus-circle"></i><span>Agendar cita</span></a>@endif
                @if($has('mascotas.index'))<a href="{{ route('mascotas.index') }}"><i class="fas fa-paw"></i><span>Mis mascotas</span></a>@endif
                @if($has('client.historias.mine'))<a href="{{ route('client.historias.mine') }}"><i class="fas fa-file-medical"></i><span>Historias</span></a>@endif
            </nav>
            <div class="spacer"></div>
            <div class="dash-logout">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit"><i class="fas fa-arrow-right-from-bracket"></i><span>Cerrar sesión</span></button>
                </form>
            </div>
        </aside>

        <div class="dash-backdrop" id="dashBackdrop"></div>

        <main class="dash-main">
            <div class="dash-top">
                <div style="display:flex;align-items:center;gap:14px;">
                    <button class="dash-burger" id="dashBurger" aria-label="Menú"><i class="fas fa-bars"></i></button>
                    <div class="greet">
                        <h1 id="greetText">Hola, {{ auth()->user()->name }}</h1>
                        <p>Panel del cliente · Lugo Vet</p>
                    </div>
                </div>
                <div class="tools">
                    <div class="dash-clock"><div class="t" id="clockTime">--:--</div><div class="d" id="clockDate">—</div></div>
                    <div class="dash-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                </div>
            </div>

            <section class="welcome-banner">
                <h2>¡Bienvenido, {{ auth()->user()->name }}! 🐾</h2>
                <p>Nos alegra tenerte en Lugo Vet. Aquí puedes gestionar tus citas, ver tus mascotas y mantener tu información al día.</p>
            </section>

            <div class="mod-head">
                <h2>¿Qué deseas hacer?</h2>
                <div class="mod-search"><i class="fas fa-search"></i><input type="text" id="modSearch" placeholder="Buscar..."></div>
            </div>
            <section class="mod-grid" id="modGrid">
                @if($has('citas.index'))
                <a class="mod-card ripple-host" data-name="citas ver mis" href="{{ route('citas.index') }}">
                    <div class="mi"><i class="fas fa-calendar-check"></i></div><div class="mt">Ver mis citas</div><div class="md">Consulta tus citas programadas.</div></a>
                @endif
                @if($has('citas.create'))
                <a class="mod-card ripple-host" data-name="agendar nueva cita" href="{{ route('citas.create') }}">
                    <div class="mi"><i class="fas fa-plus-circle"></i></div><div class="mt">Agendar nueva cita</div><div class="md">Reserva una cita para tu mascota.</div></a>
                @endif
                @if($has('mascotas.index'))
                <a class="mod-card ripple-host" data-name="mascotas mis" href="{{ route('mascotas.index') }}">
                    <div class="mi"><i class="fas fa-paw"></i></div><div class="mt">Mis mascotas ({{ $totalMascotas ?? 0 }})</div><div class="md">Revisa los datos de tus mascotas.</div></a>
                @endif
                @if($has('client.historias.mine'))
                <a class="mod-card ripple-host" data-name="historias clinicas" href="{{ route('client.historias.mine') }}">
                    <div class="mi"><i class="fas fa-file-medical"></i></div><div class="mt">Mis historias</div><div class="md">Historias clínicas de tus mascotas.</div></a>
                @endif
            </section>
            <p class="mod-empty" id="modEmpty">No se encontró nada con ese nombre.</p>

            {{-- Próximas citas del cliente (HU06) --}}
            <section class="welcome-banner" style="margin-top:24px">
                <h2>Próximas citas</h2>
                @forelse(($proximasCitas ?? collect()) as $c)
                    <p>
                        <strong>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }} · {{ substr($c->hora, 0, 5) }}</strong>
                        — {{ $c->mascota->nombre ?? '—' }} · {{ $c->servicio_nombre }} · {{ ucfirst($c->estado) }}{{ $c->vet ? ' con '.$c->vet->name : '' }}
                    </p>
                @empty
                    <p>No tienes citas próximas.</p>
                @endforelse
            </section>
        </main>
    </div>

    <script>
        (function () {
            const dash = document.getElementById('dash');
            const h = new Date().getHours();
            const saludo = h < 12 ? 'Buenos días' : (h < 19 ? 'Buenas tardes' : 'Buenas noches');
            const nombre = @json(auth()->user()->name);
            document.getElementById('greetText').textContent = `${saludo}, ${nombre}`;

            const tEl = document.getElementById('clockTime'), dEl = document.getElementById('clockDate');
            function tick(){ const n=new Date(); tEl.textContent=n.toLocaleTimeString('es-CO',{hour:'2-digit',minute:'2-digit'}); dEl.textContent=n.toLocaleDateString('es-CO',{weekday:'long',day:'numeric',month:'long'}); }
            tick(); setInterval(tick,1000);

            const search=document.getElementById('modSearch'), cards=Array.from(document.querySelectorAll('#modGrid .mod-card')), empty=document.getElementById('modEmpty');
            search.addEventListener('input',()=>{ const q=search.value.trim().toLowerCase(); let v=0; cards.forEach(c=>{const s=(c.dataset.name+' '+c.textContent).toLowerCase();const sh=s.includes(q);c.style.display=sh?'':'none';if(sh)v++;}); empty.style.display=v?'none':'block'; });

            const burger=document.getElementById('dashBurger'), backdrop=document.getElementById('dashBackdrop');
            burger.addEventListener('click',()=>{ if(window.innerWidth<=900) dash.classList.toggle('open'); else dash.classList.toggle('collapsed'); });
            backdrop.addEventListener('click',()=>dash.classList.remove('open'));

            document.querySelectorAll('.ripple-host').forEach(host=>{ host.addEventListener('click',e=>{ const r=document.createElement('span');r.className='ripple';const rect=host.getBoundingClientRect();const size=Math.max(rect.width,rect.height);r.style.width=r.style.height=size+'px';r.style.left=(e.clientX-rect.left-size/2)+'px';r.style.top=(e.clientY-rect.top-size/2)+'px';host.appendChild(r);setTimeout(()=>r.remove(),600); }); });
        })();
    </script>
</x-app-layout>