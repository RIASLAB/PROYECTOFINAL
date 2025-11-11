<x-app-layout>
  <style>
    :root{
      --ink:#0f172a; --text:#334155; --muted:#64748b;
      --line:#e5e7eb; --card:#ffffff; --bg:#f7faff;
      --emer:#10b981; --emer-700:#047857;
      --amber:#f59e0b; --amber-700:#b45309;
      --sky:#0ea5e9; --sky-700:#0369a1;
      --shadow:0 8px 24px rgba(2,6,12,.08);
      --r-xl:22px; --r-md:14px;
    }

    body{background:var(--bg);font-family:'Inter',ui-sans-serif,system-ui;}

    .cx-wrap{max-width:1200px;margin:30px auto;padding:0 18px}
    .cx-card{background:var(--card);border:1px solid var(--line);border-radius:var(--r-xl);box-shadow:var(--shadow);overflow:hidden}

    /* === Header === */
    .cx-head{
      display:flex;align-items:center;justify-content:space-between;
      padding:22px 28px;
      background:linear-gradient(135deg,#f0f9ff,#ffffff);
      border-bottom:1px solid var(--line)
    }
    .cx-left{display:flex;align-items:center;gap:14px}
    .cx-ico{
      width:52px;height:52px;border-radius:16px;
      background:linear-gradient(135deg,#38bdf8,#0ea5e9);
      display:flex;align-items:center;justify-content:center;
      font-size:26px;color:#fff;box-shadow:0 4px 14px rgba(14,165,233,.3)
    }
    .cx-title{margin:0;font:800 22px/1.2 "Inter",ui-sans-serif;color:var(--ink)}
    .cx-sub{margin-top:3px;font:500 13px/1.3 "Inter",ui-sans-serif;color:var(--muted)}
    .btn{
      border:0;border-radius:var(--r-md);
      font:700 13px/1 "Inter",ui-sans-serif;
      display:inline-flex;align-items:center;gap:8px;
      padding:10px 16px;text-decoration:none;
      cursor:pointer;transition:all .2s ease;
    }
    .btn-back{
      background:var(--sky);color:#fff;box-shadow:0 4px 10px rgba(14,165,233,.3);
    }
    .btn-back:hover{background:var(--sky-700);transform:translateY(-1px)}

    /* === KPIs === */
    .cx-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;padding:20px;background:#f9fbff;border-bottom:1px solid var(--line)}
    .cx-kpi{
      background:#fff;border:1px solid var(--line);border-radius:16px;padding:14px 16px;
      box-shadow:0 2px 8px rgba(2,6,12,.06);transition:transform .15s;
    }
    .cx-kpi:hover{transform:translateY(-3px)}
    .cx-kpi .n{font:800 26px/1.1 "Inter";color:var(--ink)}
    .cx-kpi .l{font:600 13px/1.2 "Inter";color:var(--muted)}

    /* === Cards === */
    .cx-grid{padding:22px;display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:16px}
    .cx-item{
      background:#fff;border:1px solid var(--line);border-radius:18px;padding:18px 20px;
      box-shadow:0 4px 14px rgba(2,6,12,.06);
      transition:all .2s ease;display:flex;flex-direction:column;gap:10px
    }
    .cx-item:hover{transform:translateY(-4px);box-shadow:0 8px 22px rgba(2,6,12,.1)}
    .cx-meta{display:flex;align-items:center;gap:10px;color:#64748b;font-size:13px}
    .cx-badge{
      display:inline-flex;align-items:center;gap:6px;
      border-radius:999px;padding:5px 10px;
      font:800 11px/1 "Inter";border:1px solid #fde68a;background:#fef9c3;color:#b45309;
    }
    .cx-badge.pagada{border-color:#86efac;background:#dcfce7;color:#065f46}

    .cx-title2{margin:4px 0 2px;font:700 17px/1.3 "Inter";color:var(--ink)}
    .cx-rows{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px}
    .cx-row{font:500 13px/1.4 "Inter";color:var(--text)}
    .cx-row b{color:var(--ink)}

    .cx-actions{margin-top:10px;display:flex;justify-content:flex-end;gap:10px}
    .btn-emer{background:var(--emer);color:#fff}
    .btn-emer:hover{background:var(--emer-700);transform:translateY(-1px)}
    .btn-amber{background:#fff7ed;color:var(--amber-700);border:1px solid #fed7aa}
    .btn-amber:hover{filter:brightness(0.97)}
    .btn-disabled{background:#f1f5f9;color:#94a3b8;cursor:not-allowed;border:1px solid #e2e8f0}

    .cx-empty{margin:24px;padding:50px;border:2px dashed var(--line);
      border-radius:18px;text-align:center;background:#fff;color:#64748b;font:600 14px "Inter"}
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
        <a href="{{ route('reception.dashboard') ?? '#' }}" class="btn btn-back">Volver al panel</a>
      </div>

      @php
        $pendientes = $historias->filter(fn($h) => ($h->facturas_pagadas_count ?? 0) == 0);
        $kpiTotal = $pendientes->count();
        $kpiRecetas = $pendientes->sum('recetas_count');
        $kpiHoy = $pendientes->filter(fn($h)=>optional($h->cita)?->fecha && \Carbon\Carbon::parse($h->cita->fecha)->isToday())->count();
      @endphp

      <div class="cx-kpis">
        <div class="cx-kpi"><div class="n">{{ $kpiTotal }}</div><div class="l">Historias pendientes</div></div>
        <div class="cx-kpi"><div class="n">{{ $kpiRecetas }}</div><div class="l">Recetas involucradas</div></div>
        <div class="cx-kpi"><div class="n">{{ $kpiHoy }}</div><div class="l">Pendientes de hoy</div></div>
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
                    <button class="btn btn-amber">Retirar de caja</button>
                  </form>
                @endif

                @if($pagadas > 0)
                  <button class="btn btn-disabled" disabled>Facturar historia</button>
                @else
                  <a href="{{ route('facturas.createFromHistoria', $h->id) }}" class="btn btn-emer">
                    Facturar historia
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
</x-app-layout>
