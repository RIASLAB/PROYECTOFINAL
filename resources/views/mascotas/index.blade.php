<x-app-layout>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .mc {
      --ink:#182a2a; --ink-soft:#5a6f6e;
      --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
      --coral:#ff7a59; --coral-dark:#ef5c39; --amber:#f4b740;
      --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.12);
      --shadow-sm:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 14px 34px rgba(13,84,76,.14);
      --radius:18px; --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
      font-family:var(--fb); color:var(--ink);
    }
    body { background:var(--cream) !important; }
    .mc * { box-sizing:border-box; }
    .mc-wrap { max-width:1200px; margin:28px auto; padding:0 20px 48px; }

    .mc-head {
      display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
      background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                 linear-gradient(135deg,var(--teal-700),var(--teal-900));
      color:#fff; border-radius:var(--radius); padding:24px 28px; box-shadow:var(--shadow-sm); margin-bottom:24px;
    }
    .mc-head .l { display:flex; align-items:center; gap:16px; }
    .mc-head .ic { width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,.16); display:grid; place-items:center; font-size:1.6rem; }
    .mc-head h1 { font-family:var(--fd); font-weight:600; font-size:1.7rem; letter-spacing:-.02em; margin:0; }
    .mc-head p { margin:3px 0 0; opacity:.9; font-size:.92rem; }

    .btn { appearance:none; border:none; border-radius:999px; padding:11px 18px; font-family:var(--fb); font-weight:700; font-size:.92rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:transform .2s, background .2s, box-shadow .2s; }
    .btn-add { background:var(--coral); color:#fff; box-shadow:0 8px 18px rgba(255,122,89,.32); }
    .btn-add:hover { background:var(--coral-dark); transform:translateY(-2px); }

    .mc-kpis { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:16px; margin-bottom:22px; }
    .mc-kpi { background:var(--white); border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow-sm); display:flex; align-items:center; gap:14px; }
    .mc-kpi .kic { width:44px; height:44px; border-radius:12px; display:grid; place-items:center; font-size:1.2rem; color:#fff; flex-shrink:0; }
    .kic-all { background:linear-gradient(135deg,var(--teal-500),var(--teal-900)); }
    .kic-dog { background:linear-gradient(135deg,var(--coral),var(--coral-dark)); }
    .kic-cat { background:linear-gradient(135deg,var(--amber),#d99413); }
    .mc-kpi .n { font-family:var(--fd); font-size:1.6rem; font-weight:600; line-height:1; color:var(--teal-900); }
    .mc-kpi .l { font-size:.8rem; color:var(--ink-soft); font-weight:600; margin-top:2px; }

    .mc-toolbar { display:flex; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
    .mc-search { position:relative; flex:1; min-width:240px; }
    .mc-search i { position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--ink-soft); }
    .mc-search input { width:100%; padding:11px 14px 11px 40px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-family:var(--fb); font-size:.92rem; color:var(--ink); outline:none; transition:border-color .2s, box-shadow .2s; }
    .mc-search input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }

    .mc-tablecard { background:var(--white); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
    .mc-scroll { overflow-x:auto; }
    table.mc-table { width:100%; border-collapse:collapse; font-size:.9rem; min-width:740px; }
    .mc-table thead th { background:var(--teal-50); color:var(--teal-700); font-weight:700; font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; text-align:left; padding:14px; }
    .mc-table tbody td { padding:13px 14px; border-bottom:1px solid var(--line); color:var(--ink); vertical-align:middle; }
    .mc-table tbody tr { transition:background .15s; }
    .mc-table tbody tr:hover { background:#f6fbf9; }

    .mc-pet { display:flex; align-items:center; gap:11px; }
    .mc-ava { width:40px; height:40px; border-radius:12px; display:grid; place-items:center; font-size:1.15rem; color:#fff; flex-shrink:0; background:linear-gradient(135deg,var(--teal-500),var(--teal-700)); }
    .mc-name { font-weight:700; }
    .mc-id { font-size:.74rem; color:var(--ink-soft); }

    .tag { display:inline-flex; align-items:center; gap:6px; border-radius:999px; padding:4px 11px; font-size:.76rem; font-weight:700; background:var(--teal-50); color:var(--teal-700); text-transform:capitalize; }
    .tag::before { content:""; width:7px; height:7px; border-radius:50%; background:currentColor; opacity:.6; }

    .ico-btn { width:34px; height:34px; border-radius:10px; border:1px solid var(--line); background:var(--white); cursor:pointer; display:inline-grid; place-items:center; transition:transform .15s, background .15s, color .15s; text-decoration:none; }
    .ico-btn.edit { color:var(--teal-700); } .ico-btn.edit:hover { background:var(--teal-600); color:#fff; transform:translateY(-2px); }
    .ico-btn.del { color:var(--coral-dark); } .ico-btn.del:hover { background:var(--coral); color:#fff; transform:translateY(-2px); }
    .mc-actions { display:flex; gap:8px; justify-content:flex-end; }
    .mc-readonly { font-size:.78rem; color:#9bb0ad; font-style:italic; }

    .mc-empty { text-align:center; color:var(--ink-soft); padding:40px 20px; }
    .mc-empty span { font-size:2.4rem; display:block; margin-bottom:8px; }
    .mc-pag { padding:14px 18px; }

    @media (max-width:600px) { .mc-head { padding:20px; } .mc-head h1 { font-size:1.4rem; } }
  </style>

  @php
    $totalMascotas = method_exists($mascotas, 'total') ? $mascotas->total() : $mascotas->count();
  @endphp

  <div class="mc">
    <div class="mc-wrap">
      <div class="mc-head">
        <div class="l">
          <div class="ic"><i class="fas fa-paw"></i></div>
          <div>
            <h1>Lista de Mascotas</h1>
            <p>Administra tus mascotas, sus datos y sus dueños.</p>
          </div>
        </div>
        <a href="{{ route('mascotas.create') }}" class="btn btn-add"><i class="fas fa-plus"></i> Nueva Mascota</a>
      </div>

      {{-- KPIs --}}
      <div class="mc-kpis">
        <div class="mc-kpi">
          <div class="kic kic-all"><i class="fas fa-paw"></i></div>
          <div><div class="n" data-count="{{ (int) $totalMascotas }}">0</div><div class="l">Mascotas registradas</div></div>
        </div>
        <div class="mc-kpi">
          <div class="kic kic-dog"><i class="fas fa-dog"></i></div>
          <div><div class="n" data-count="{{ (int) ($perros ?? 0) }}">0</div><div class="l">Perros</div></div>
        </div>
        <div class="mc-kpi">
          <div class="kic kic-cat"><i class="fas fa-cat"></i></div>
          <div><div class="n" data-count="{{ (int) ($gatos ?? 0) }}">0</div><div class="l">Gatos</div></div>
        </div>
      </div>

      {{-- Buscador --}}
      <div class="mc-toolbar">
        <div class="mc-search">
          <i class="fas fa-search"></i>
          <input type="text" id="mcSearch" placeholder="Buscar por nombre, especie, raza o dueño...">
        </div>
      </div>

      {{-- Tabla --}}
      @if($mascotas->isEmpty())
        <div class="mc-tablecard">
          <div class="mc-empty"><span>📭</span> No hay mascotas registradas todavía.</div>
        </div>
      @else
        <div class="mc-tablecard">
          <div class="mc-scroll">
            <table class="mc-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Mascota</th>
                  <th>Especie</th>
                  <th>Raza</th>
                  <th>Edad</th>
                  <th>Dueño</th>
                  <th style="text-align:right">Acciones</th>
                </tr>
              </thead>
              <tbody id="mcBody">
                @foreach($mascotas as $mascota)
                  @php
                    $index = ($mascotas->firstItem() ?? 0) + $loop->index;
                    $dueno = optional($mascota->owner)->name ?? $mascota->dueno ?? '—';

                    $user     = auth()->user();
                    $role     = $user->role ?? null;
                    $isOwner  = $mascota->perteneceA($user);
                    $canManage = in_array($role, ['admin','recepcionista']) || (in_array($role,['user','cliente']) && $isOwner);

                    $especie = strtolower($mascota->especie ?? '');
                    $icon = $especie === 'perro' ? 'fa-dog' : ($especie === 'gato' ? 'fa-cat' : 'fa-paw');
                  @endphp
                  <tr data-search="{{ strtolower($mascota->nombre.' '.($mascota->especie ?? '').' '.($mascota->raza ?? '').' '.$dueno) }}">
                    <td>{{ $index }}</td>
                    <td>
                      <div class="mc-pet">
                        <div class="mc-ava"><i class="fas {{ $icon }}"></i></div>
                        <div>
                          <div class="mc-name">{{ $mascota->nombre }}</div>
                          <div class="mc-id">ID: {{ $mascota->id }}</div>
                        </div>
                      </div>
                    </td>
                    <td><span class="tag">{{ $mascota->especie ?? '—' }}</span></td>
                    <td>{{ $mascota->raza ?? '—' }}</td>
                    <td>{{ $mascota->edad ?? '—' }}</td>
                    <td>{{ $dueno }}</td>
                    <td>
                      <div class="mc-actions">
                        @if($canManage)
                          <a href="{{ route('mascotas.edit', $mascota) }}" class="ico-btn edit" title="Editar"><i class="fas fa-pen"></i></a>
                          <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST"
                                onsubmit="return confirm('¿Seguro que deseas eliminar esta mascota?');" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="ico-btn del" title="Eliminar"><i class="fas fa-trash"></i></button>
                          </form>
                        @else
                          <span class="mc-readonly">Solo lectura</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mc-empty" id="mcNoResults" style="display:none">No se encontraron mascotas con ese criterio.</div>
          <div class="mc-pag">{{ $mascotas->links() }}</div>
        </div>
      @endif
    </div>
  </div>

  <script>
    document.querySelectorAll('.mc-kpi .n[data-count]').forEach(el => {
      const target = parseInt(el.dataset.count,10) || 0; const dur=900; const t0=performance.now();
      (function step(now){ const p=Math.min((now-t0)/dur,1); el.textContent=Math.round(target*(1-Math.pow(1-p,3))); if(p<1) requestAnimationFrame(step); })(performance.now());
    });

    const rows = Array.from(document.querySelectorAll('#mcBody tr[data-search]'));
    const search = document.getElementById('mcSearch');
    const noRes = document.getElementById('mcNoResults');
    if (search) search.addEventListener('input', () => {
      const q = search.value.trim().toLowerCase();
      let visible = 0;
      rows.forEach(tr => { const show = tr.dataset.search.includes(q); tr.style.display = show ? '' : 'none'; if (show) visible++; });
      if (noRes) noRes.style.display = (rows.length && !visible) ? 'block' : 'none';
    });
  </script>
</x-app-layout>