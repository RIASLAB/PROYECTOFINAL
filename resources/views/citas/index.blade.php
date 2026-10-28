<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        .ct {
            --ink:#182a2a; --ink-soft:#5a6f6e;
            --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
            --coral:#ff7a59; --coral-dark:#ef5c39; --amber:#f4b740;
            --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.12);
            --shadow-sm:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 14px 34px rgba(13,84,76,.14);
            --radius:18px; --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
            font-family:var(--fb); color:var(--ink);
        }
        body { background:var(--cream) !important; }
        .ct * { box-sizing:border-box; }
        .ct-wrap { max-width:1200px; margin:28px auto; padding:0 20px 48px; }

        .ct-head {
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
            background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                       linear-gradient(135deg,var(--teal-700),var(--teal-900));
            color:#fff; border-radius:var(--radius); padding:24px 28px; box-shadow:var(--shadow-sm); margin-bottom:24px;
        }
        .ct-head .l { display:flex; align-items:center; gap:16px; }
        .ct-head .ic { width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,.16); display:grid; place-items:center; font-size:1.6rem; }
        .ct-head h1 { font-family:var(--fd); font-weight:600; font-size:1.7rem; letter-spacing:-.02em; margin:0; }
        .ct-head p { margin:3px 0 0; opacity:.9; font-size:.92rem; }

        .btn { appearance:none; border:none; border-radius:999px; padding:11px 18px; font-family:var(--fb); font-weight:700; font-size:.92rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:transform .2s, background .2s, box-shadow .2s; }
        .btn-add { background:var(--coral); color:#fff; box-shadow:0 8px 18px rgba(255,122,89,.32); }
        .btn-add:hover { background:var(--coral-dark); transform:translateY(-2px); }

        /* KPIs como filtros */
        .ct-kpis { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:16px; margin-bottom:22px; }
        .ct-kpi { position:relative; text-align:left; background:var(--white); border:1.5px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow-sm); cursor:pointer; transition:transform .2s, box-shadow .2s, border-color .2s; font-family:var(--fb); }
        .ct-kpi:hover { transform:translateY(-4px); box-shadow:var(--shadow-md); }
        .ct-kpi.active { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.14); }
        .ct-kpi .n { font-family:var(--fd); font-size:1.9rem; font-weight:600; line-height:1; color:var(--teal-900); }
        .ct-kpi .l { font-size:.82rem; color:var(--ink-soft); font-weight:600; margin-top:4px; display:flex; align-items:center; gap:6px; }
        .ct-kpi .l .dot { width:8px; height:8px; border-radius:50%; }
        .dot-all { background:var(--teal-500); } .dot-pend { background:var(--amber); } .dot-comp { background:var(--teal-600); } .dot-canc { background:var(--coral); }

        .ct-toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
        .ct-search { position:relative; flex:1; min-width:240px; }
        .ct-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--ink-soft); }
        .ct-search input { width:100%; padding:11px 14px 11px 40px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-family:var(--fb); font-size:.92rem; color:var(--ink); outline:none; transition:border-color .2s, box-shadow .2s; }
        .ct-search input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }

        .ct-tablecard { background:var(--white); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
        .ct-scroll { overflow-x:auto; }
        table.ct-table { width:100%; border-collapse:collapse; font-size:.9rem; min-width:720px; }
        .ct-table thead th { background:var(--teal-50); color:var(--teal-700); font-weight:700; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; text-align:left; padding:14px; }
        .ct-table tbody td { padding:13px 14px; border-bottom:1px solid var(--line); color:var(--ink); }
        .ct-table tbody tr { transition:background .15s; }
        .ct-table tbody tr:hover { background:#f6fbf9; }
        .ct-pet { display:flex; align-items:center; gap:10px; }
        .ct-pet .pico { width:34px; height:34px; border-radius:10px; background:var(--teal-50); color:var(--teal-700); display:grid; place-items:center; }
        .ct-pet .pn { font-weight:700; }

        .badge { padding:5px 12px; border-radius:999px; font-size:.74rem; font-weight:700; display:inline-flex; align-items:center; gap:6px; }
        .badge::before { content:""; width:7px; height:7px; border-radius:50%; background:currentColor; opacity:.65; }
        .st-pendiente { background:#fff6e2; color:#8a5a05; }
        .st-completada { background:var(--teal-50); color:var(--teal-700); }
        .st-cancelada { background:#fdece8; color:var(--coral-dark); }

        .ico-btn { width:34px; height:34px; border-radius:10px; border:1px solid var(--line); background:var(--white); cursor:pointer; display:inline-grid; place-items:center; transition:transform .15s, background .15s, color .15s; text-decoration:none; }
        .ico-btn.edit { color:var(--teal-700); } .ico-btn.edit:hover { background:var(--teal-600); color:#fff; transform:translateY(-2px); }
        .ico-btn.del { color:var(--coral-dark); } .ico-btn.del:hover { background:var(--coral); color:#fff; transform:translateY(-2px); }
        .ct-actions { display:flex; gap:8px; justify-content:flex-end; }

        .ct-empty { text-align:center; color:var(--ink-soft); padding:40px 20px; }
        .ct-empty span { font-size:2.4rem; display:block; margin-bottom:8px; }
        .ct-pag { padding:14px 18px; }
        .ct-pag a, .ct-pag span { font-family:var(--fb); }

        @media (max-width:600px) { .ct-head { padding:20px; } .ct-head h1 { font-size:1.4rem; } }
    </style>

    <div class="ct">
        <div class="ct-wrap">
            <div class="ct-head">
                <div class="l">
                    <div class="ic"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <h1>Lista de Citas</h1>
                        <p>Administra todas las citas y sus detalles.</p>
                    </div>
                </div>
                <a href="{{ route('citas.create') }}" class="btn btn-add"><i class="fas fa-plus"></i> Nueva Cita</a>
            </div>

            {{-- KPIs / filtros por estado --}}
            <div class="ct-kpis">
                <button type="button" class="ct-kpi active" data-filter="">
                    <div class="n" data-count="{{ (int) $citas->count() }}">0</div>
                    <div class="l"><span class="dot dot-all"></span> Citas totales</div>
                </button>
                <button type="button" class="ct-kpi" data-filter="pendiente">
                    <div class="n" data-count="{{ (int) $citas->where('estado','pendiente')->count() }}">0</div>
                    <div class="l"><span class="dot dot-pend"></span> Pendientes</div>
                </button>
                <button type="button" class="ct-kpi" data-filter="completada">
                    <div class="n" data-count="{{ (int) $citas->where('estado','completada')->count() }}">0</div>
                    <div class="l"><span class="dot dot-comp"></span> Completadas</div>
                </button>
                <button type="button" class="ct-kpi" data-filter="cancelada">
                    <div class="n" data-count="{{ (int) $citas->where('estado','cancelada')->count() }}">0</div>
                    <div class="l"><span class="dot dot-canc"></span> Canceladas</div>
                </button>
            </div>

            {{-- Buscador --}}
            <div class="ct-toolbar">
                <div class="ct-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="ctSearch" placeholder="Buscar por mascota, motivo o fecha...">
                </div>
            </div>

            {{-- Tabla --}}
            @if($citas->isEmpty())
                <div class="ct-tablecard">
                    <div class="ct-empty"><span>📭</span> No hay citas registradas todavía.</div>
                </div>
            @else
                <div class="ct-tablecard">
                    <div class="ct-scroll">
                        <table class="ct-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mascota</th>
                                    <th>Servicio</th>
                                    <th>Motivo</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th style="text-align:right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="ctBody">
                                @foreach($citas as $cita)
                                    @php $estado = strtolower($cita->estado); @endphp
                                    <tr data-estado="{{ $estado }}"
                                        data-search="{{ strtolower(($cita->mascota->nombre ?? '').' '.$cita->motivo.' '.$cita->fecha) }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="ct-pet">
                                                <div class="pico"><i class="fas fa-paw"></i></div>
                                                <div class="pn">{{ $cita->mascota->nombre ?? '—' }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $cita->servicio_nombre }}</td>
                                        <td>{{ $cita->motivo }}</td>
                                        <td>{{ $cita->fecha }}</td>
                                        <td>{{ $cita->hora ?? '—' }}</td>
                                        <td><span class="badge st-{{ $estado }}">{{ ucfirst($cita->estado) }}</span></td>
                                        <td>
                                            <div class="ct-actions">
                                                <a href="{{ route('citas.edit', $cita) }}" class="ico-btn edit" title="Editar"><i class="fas fa-pen"></i></a>
                                                <form action="{{ route('citas.destroy', $cita) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cita?')" style="display:inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ico-btn del" title="Eliminar"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="ct-empty" id="ctNoResults" style="display:none">No se encontraron citas con esos criterios.</div>
                    <div class="ct-pag">{{ $citas->links() }}</div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Conteo animado
        document.querySelectorAll('.ct-kpi .n[data-count]').forEach(el => {
            const target = parseInt(el.dataset.count,10) || 0; const dur=900; const t0=performance.now();
            (function step(now){ const p=Math.min((now-t0)/dur,1); el.textContent=Math.round(target*(1-Math.pow(1-p,3))); if(p<1) requestAnimationFrame(step); })(performance.now());
        });

        const rows = Array.from(document.querySelectorAll('#ctBody tr[data-estado]'));
        const search = document.getElementById('ctSearch');
        const kpis = Array.from(document.querySelectorAll('.ct-kpi'));
        const noRes = document.getElementById('ctNoResults');
        let activeFilter = '';

        function apply() {
            const q = (search?.value || '').trim().toLowerCase();
            let visible = 0;
            rows.forEach(tr => {
                const okText = tr.dataset.search.includes(q);
                const okEstado = !activeFilter || tr.dataset.estado === activeFilter;
                const show = okText && okEstado;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (noRes) noRes.style.display = (rows.length && !visible) ? 'block' : 'none';
        }
        if (search) search.addEventListener('input', apply);
        kpis.forEach(k => k.addEventListener('click', () => {
            kpis.forEach(x => x.classList.remove('active'));
            k.classList.add('active');
            activeFilter = k.dataset.filter;
            apply();
        }));
    </script>
</x-app-layout>