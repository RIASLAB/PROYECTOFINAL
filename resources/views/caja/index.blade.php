<x-app-layout>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    :root{
      --ink:#182a2a; --text:#44595a; --muted:#7a8d8b;
      --line:rgba(20,60,56,.12); --card:#ffffff; --bg:#faf6ef;
      --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
      --coral:#ff7a59; --coral-dark:#ef5c39; --amber:#f4b740;
      --shadow:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 14px 34px rgba(13,84,76,.14);
      --r-xl:20px; --r-md:12px;
      --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
    }

    body{background:var(--bg);font-family:var(--fb);}

    .cx-wrap{max-width:1200px;margin:30px auto;padding:0 18px 48px}
    .cx-card{background:var(--card);border:1px solid var(--line);border-radius:var(--r-xl);box-shadow:var(--shadow);overflow:hidden}

    /* === Header (banda de marca) === */
    .cx-head{
      display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;
      padding:24px 28px;
      background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                 linear-gradient(135deg,var(--teal-700),var(--teal-900));
      color:#fff;
    }
    .cx-left{display:flex;align-items:center;gap:16px}
    .cx-ico{
      width:54px;height:54px;border-radius:16px;
      background:rgba(255,255,255,.16);
      display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff;
    }
    .cx-title{margin:0;font-family:var(--fd);font-weight:600;font-size:1.7rem;letter-spacing:-.02em;color:#fff}
    .cx-sub{margin-top:3px;font-size:.92rem;color:rgba(255,255,255,.9)}
    .btn{
      border:0;border-radius:999px;
      font-family:var(--fb);font-weight:700;font-size:13px;
      display:inline-flex;align-items:center;gap:8px;
      padding:11px 18px;text-decoration:none;cursor:pointer;transition:all .2s ease;
    }
    .btn-back{background:rgba(255,255,255,.16);color:#fff;border:1px solid rgba(255,255,255,.3)}
    .btn-back:hover{background:rgba(255,255,255,.28);transform:translateY(-2px)}

    /* === KPIs === */
    .cx-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;padding:22px;background:#f3ece0;border-bottom:1px solid var(--line)}
    .cx-kpi{
      background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px 20px;
      box-shadow:var(--shadow);transition:transform .2s, box-shadow .2s;
    }
    .cx-kpi:hover{transform:translateY(-4px);box-shadow:var(--shadow-md)}
    .cx-kpi .n{font-family:var(--fd);font-weight:600;font-size:1.9rem;line-height:1.1;color:var(--teal-900)}
    .cx-kpi .l{font-weight:600;font-size:13px;color:var(--muted);margin-top:2px}

    /* === Cards === */
    .cx-grid{padding:22px;display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:16px}
    .cx-item{
      position:relative;overflow:hidden;
      background:#fff;border:1px solid var(--line);border-radius:18px;padding:20px;
      box-shadow:var(--shadow);transition:transform .2s, box-shadow .2s;display:flex;flex-direction:column;gap:10px
    }
    .cx-item::before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(90deg,var(--teal-500),var(--coral));transform:scaleX(0);transform-origin:left;transition:transform .3s}
    .cx-item:hover{transform:translateY(-5px);box-shadow:var(--shadow-md)}
    .cx-item:hover::before{transform:scaleX(1)}
    .cx-meta{display:flex;align-items:center;gap:10px;color:var(--muted);font-size:13px}
    .cx-badge{
      display:inline-flex;align-items:center;gap:6px;
      border-radius:999px;padding:5px 12px;
      font-weight:800;font-size:11px;border:1px solid var(--amber);background:#fff6e2;color:#8a5a05;
    }
    .cx-badge::before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor;opacity:.6}
    .cx-badge.pagada{border-color:var(--teal-500);background:var(--teal-50);color:var(--teal-700)}

    .cx-title2{margin:4px 0 2px;font-family:var(--fd);font-weight:600;font-size:1.15rem;color:var(--ink)}
    .cx-rows{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
    .cx-row{font-weight:500;font-size:13px;line-height:1.4;color:var(--text)}
    .cx-row b{color:var(--ink)}

    .cx-actions{margin-top:10px;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap}
    .btn-emer{background:var(--teal-600);color:#fff;box-shadow:0 8px 18px rgba(13,110,99,.26)}
    .btn-emer:hover{background:var(--teal-700);transform:translateY(-2px)}
    .btn-amber{background:#fff6e2;color:#8a5a05;border:1px solid var(--amber)}
    .btn-amber:hover{filter:brightness(0.98);transform:translateY(-2px)}
    .btn-disabled{background:#eef1f0;color:#9bb0ad;cursor:not-allowed;border:1px solid var(--line)}

    .cx-empty{margin:24px;padding:50px;border:2px dashed var(--line);
      border-radius:18px;text-align:center;background:#fff;color:var(--muted);font-weight:600;font-size:14px}

    @media (max-width:600px){ .cx-head{padding:20px} .cx-title{font-size:1.4rem} .cx-grid{grid-template-columns:1fr} }
  </style>

  <div class="cx-wrap">
    <div class="cx-card">
      <div class="cx-head">
        <div class="cx-left">
          <div class="cx-ico">💳</div>
          <div>
            <h1 class="cx-title">Caja · Pendientes de cobro</h1>
            <p class="cx-sub">Historias enviadas por los veterinarios para facturar.</p>
          </div>
        </div>
        <a href="{{ route('reception.dashboard') ?? '#' }}" class="btn btn-back"><i class="fas fa-arrow-left"></i> Volver al panel</a>
      </div>

      @php
        $pendientes = $historias->filter(fn($h) => ($h->facturas_pagadas_count ?? 0) == 0);
        $kpiTotal = $pendientes->count();
        $kpiRecetas = $pendientes->sum('recetas_count');
        $kpiHoy = $pendientes->filter(fn($h)=>optional($h->cita)?->fecha && \Carbon\Carbon::parse($h->cita->fecha)->isToday())->count();
      @endphp

      <div class="cx-kpis">
        <div class="cx-kpi"><div class="n" data-count="{{ (int) $kpiTotal }}">0</div><div class="l">Historias pendientes</div></div>
        <div class="cx-kpi"><div class="n" data-count="{{ (int) $kpiRecetas }}">0</div><div class="l">Recetas involucradas</div></div>
        <div class="cx-kpi"><div class="n" data-count="{{ (int) $kpiHoy }}">0</div><div class="l">Pendientes de hoy</div></div>
      </div>

      @if($historias->isEmpty())
        <div class="cx-empty">
          <div style="font-size:28px;margin-bottom:6px">🗂️</div>
          No hay historias pendientes de cobro.
        </div>
      @else
        <div class="cx-grid">
          @foreach($historias as $h)
            @php
              $cita  = $h->cita ?? null;
              $pet   = optional(optional($cita)->mascota)->nombre ?? '—';
              $vet   = optional(optional($cita)->vet)->name ?? '—';
              $fecha = $cita?->fecha ? \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') : \Carbon\Carbon::parse($h->created_at)->format('d/m/Y');
              $hora  = $cita?->hora ?? '--:--';
              $pagadas = $h->facturas_pagadas_count ?? 0;
            @endphp

            <div class="cx-item">
              <div class="cx-meta">
                <span>{{ $fecha }} · {{ $hora }}</span>
                <span class="cx-badge {{ $pagadas > 0 ? 'pagada' : '' }}">
                  {{ $pagadas > 0 ? 'Pagada' : 'Pendiente' }}
                </span>
              </div>

              <h3 class="cx-title2">Historia #{{ $h->id }}</h3>

              <div class="cx-rows">
                <div class="cx-row"><b>Mascota:</b> {{ $pet }}</div>
                <div class="cx-row"><b>Veterinario:</b> {{ $vet }}</div>
                <div class="cx-row"><b>Recetas:</b> {{ $h->recetas_count ?? $h->recetas()->count() }}</div>
              </div>

              <div class="cx-actions">
                @if(Route::has('historias.retirarCaja'))
                  <form method="POST" action="{{ route('historias.retirarCaja', $h) }}">
                    @csrf
                    <button class="btn btn-amber"><i class="fas fa-rotate-left"></i> Retirar de caja</button>
                  </form>
                @endif

                @if($pagadas > 0)
                  <button class="btn btn-disabled" disabled><i class="fas fa-check"></i> Facturar historia</button>
                @else
                  <a href="{{ route('facturas.createFromHistoria', $h->id) }}" class="btn btn-emer">
                    <i class="fas fa-file-invoice-dollar"></i> Facturar historia
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div class="px-4 pb-5">
          {{ $historias->links() }}
        </div>
      @endif
    </div>
  </div>

  <script>
    document.querySelectorAll('.cx-kpi .n[data-count]').forEach(el => {
      const target = parseInt(el.dataset.count,10) || 0; const dur=900; const t0=performance.now();
      (function step(now){ const p=Math.min((now-t0)/dur,1); el.textContent=Math.round(target*(1-Math.pow(1-p,3))); if(p<1) requestAnimationFrame(step); })(performance.now());
    });
  </script>
</x-app-layout>