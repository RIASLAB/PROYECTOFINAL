<x-app-layout>
  <style>
    :root{
      --cx-ink:#0f172a; --cx-text:#334155; --cx-muted:#64748b;
      --cx-line:#e5e7eb; --cx-card:#ffffff; --cx-bg:#f6f9fc;
      --shadow:0 8px 28px rgba(2,6,12,.08); --shadow-sm:0 6px 18px rgba(2,6,12,.06);
      --r-xl:22px; --r-lg:16px; --r-md:12px;
      --emer:#10b981; --emer-700:#047857;
      --amber:#f59e0b; --amber-700:#b45309;
      --sky:#0ea5e9; --sky-700:#0369a1;
    }
    .cx-wrap{max-width:1200px;margin:26px auto;padding:0 18px}
    .cx-card{background:var(--cx-card);border:1px solid var(--cx-line);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden}
    .cx-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--cx-line);background:linear-gradient(180deg,#fbfdff,#ffffff)}
    .cx-left{display:flex;align-items:center;gap:12px}
    .cx-ico{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#67e8f9,#0ea5e9);display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px}
    .cx-title{margin:0;font:800 20px/1.1 ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto;color:var(--cx-ink)}
    .cx-sub{margin:2px 0 0;font:500 12px/1.2 ui-sans-serif;color:var(--cx-muted)}

    .cx-kpis{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;padding:14px 16px;background:#fafcff;border-bottom:1px solid var(--cx-line)}
    .cx-kpi{background:#fff;border:1px solid var(--cx-line);border-radius:14px;padding:12px 14px;box-shadow:var(--shadow-sm)}
    .cx-kpi .n{font:800 22px/1 ui-sans-serif;color:var(--cx-ink)}
    .cx-kpi .l{font:600 12px/1.1 ui-sans-serif;color:var(--cx-muted)}

    .cx-grid{padding:16px;display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:14px}
    .cx-item{background:#fff;border:1px solid var(--cx-line);border-radius:16px;padding:14px 16px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:8px;transition:transform .18s, box-shadow .18s}
    .cx-item:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
    .cx-meta{display:flex;align-items:center;gap:10px;color:#64748b;font-size:13px}
    .cx-badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:4px 8px;font:800 11px/1 ui-sans-serif;border:1px solid #fed7aa;background:#fff7ed;color:var(--amber-700)}
    .cx-badge.pagada{border-color:#bbf7d0;background:#dcfce7;color:#065f46}
    .cx-title2{margin:2px 0 0;font:700 16px/1.2 ui-sans-serif;color:var(--cx-ink)}
    .cx-rows{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:6px}
    .cx-row{font:500 13px/1.35 ui-sans-serif;color:var(--cx-text)}
    .cx-row b{color:var(--cx-ink)}
    .cx-actions{margin-top:4px;display:flex;justify-content:flex-end;gap:10px}
    .btn{appearance:none;border:1px solid transparent;border-radius:12px;padding:9px 12px;font:800 13px/1 ui-sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
    .btn-back{background:#0f172a;color:#fff;border-color:#0f172a}.btn-back:hover{filter:brightness(1.05)}
    .btn-amber{background:#fff7ed;color:var(--amber-700);border-color:#fed7aa}.btn-amber:hover{filter:brightness(0.98)}
    .btn-emer{background:var(--emer);color:#fff;border-color:var(--emer)}.btn-emer:hover{background:var(--emer-700)}
    .btn-disabled{background:#f1f5f9;color:#94a3b8;border-color:#e2e8f0;cursor:not-allowed}
    .cx-empty{margin:18px;padding:40px;border:2px dashed var(--cx-line);border-radius:18px;text-align:center;background:#fff;color:#64748b}
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
        <a href="{{ route('reception.dashboard') ?? '#' }}" class="btn btn-back">Volver</a>
      </div>

      {{-- KPIs basados SOLO en historias SIN factura pagada --}}
      @php
        $pendientes = $historias->filter(fn($h) => ($h->facturas_pagadas_count ?? 0) == 0);
        $kpiTotal = $pendientes->count();
        $kpiRecetas = $pendientes->sum('recetas_count');
        $kpiHoy = $pendientes->filter(function($h){
          $fecha = optional(optional($h->cita)->fecha);
          return $fecha && \Carbon\Carbon::parse($fecha)->isToday();
        })->count();
      @endphp
      <div class="cx-kpis">
        <div class="cx-kpi"><div class="n">{{ $kpiTotal }}</div><div class="l">Historias pendientes</div></div>
        <div class="cx-kpi"><div class="n">{{ $kpiRecetas }}</div><div class="l">Recetas involucradas</div></div>
        <div class="cx-kpi"><div class="n">{{ $kpiHoy }}</div><div class="l">Pendientes de hoy</div></div>
      </div>

      {{-- Lista --}}
      @if($historias->isEmpty())
        <div class="cx-empty">
          <div style="font-size:26px">🗂️</div>
          <p>No hay historias pendientes de cobro.</p>
        </div>
      @else
        <div class="cx-grid">
          @foreach($historias as $h)
            @php
              $cita  = $h->cita ?? null;
              $pet   = optional(optional($cita)->mascota)->nombre ?? '—';
              $vet   = optional(optional($cita)->vet)->name ?? '—';
              $fecha = $cita && $cita->fecha ? \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') : \Carbon\Carbon::parse($h->created_at)->format('d/m/Y');
              $hora  = $cita->hora ?? '--:--';
              $pagadas = $h->facturas_pagadas_count ?? 0;
            @endphp

            <div class="cx-item">
              <div class="cx-meta">
                <span>{{ $fecha }} · {{ $hora }}</span>
                @if($pagadas > 0)
                  <span class="cx-badge pagada">Pagada</span>
                @else
                  <span class="cx-badge">Pendiente</span>
                @endif
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
                  <button class="btn btn-disabled" disabled title="Ya tiene factura pagada">
                    Facturar historia
                  </button>
                @else
                  <a href="{{ route('facturas.createFromHistoria', $h->id) }}" class="btn btn-emer">
                    Facturar historia
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <div class="px-4 pb-4">
          {{ $historias->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
