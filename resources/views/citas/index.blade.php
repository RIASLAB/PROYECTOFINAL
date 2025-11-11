<x-app-layout>
  <style>
    :root{
      --mx-ink:#0f172a; --mx-text:#334155; --mx-muted:#64748b;
      --mx-line:#e5e7eb; --mx-card:#ffffff; --mx-bg:#f6f9fc;
      --shadow:0 8px 28px rgba(15,23,42,.08);
      --shadow-sm:0 6px 18px rgba(15,23,42,.06);
      --r-xl:22px; --r-lg:16px; --r-md:12px;
      --emer:#10b981; --emer-700:#047857;
      --sky:#0ea5e9; --sky-700:#0369a1;
      --rose:#f97373;
    }

    .mx-wrap{max-width:1200px;margin:26px auto;padding:0 18px}
    .mx-card{background:var(--mx-card);border:1px solid var(--mx-line);border-radius:var(--r-xl);box-shadow:var(--shadow-sm);overflow:hidden}
    .mx-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--mx-line);background:linear-gradient(180deg,#fbfdff,#ffffff)}
    .mx-left{display:flex;align-items:center;gap:12px}
    .mx-ico{width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#f97316,#facc15);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px}
    .mx-title{margin:0;font:800 20px/1.1 ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto;color:var(--mx-ink)}
    .mx-sub{margin:3px 0 0;font:500 12px/1.2 ui-sans-serif;color:var(--mx-muted)}

    .mx-kpis{display:flex;flex-wrap:wrap;gap:10px;padding:10px 20px 16px;border-bottom:1px solid var(--mx-line);background:#fbfdff}
    .mx-kpi{min-width:160px;flex:1;background:#fff;border:1px solid var(--mx-line);border-radius:14px;padding:10px 14px;box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:2px}
    .mx-kpi .n{font:800 18px/1 ui-sans-serif;color:var(--mx-ink)}
    .mx-kpi .l{font:600 12px/1.1 ui-sans-serif;color:var(--mx-muted)}

    .mx-toolbar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between;padding:14px 20px 6px}
    .mx-search-wrap{flex:1;min-width:230px;position:relative}
    .mx-search{width:100%;border-radius:999px;border:1px solid var(--mx-line);padding:10px 14px 10px 34px;font-size:13px;color:var(--mx-text);background:#f9fafb;outline:none;transition:border-color .15s, box-shadow .15s, background .15s}
    .mx-search:focus{border-color:var(--sky);box-shadow:0 0 0 1px rgba(14,165,233,.25);background:#fff}
    .mx-search-ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:14px;color:#9ca3af}
    .btn{appearance:none;border-radius:999px;border:1px solid transparent;padding:9px 14px;font:800 13px/1 ui-sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap}
    .btn-primary{background:var(--sky);border-color:var(--sky);color:#fff}
    .btn-primary:hover{background:var(--sky-700);border-color:var(--sky-700)}

    .mx-table-wrap{padding:8px 20px 18px}
    .mx-table{width:100%;border-collapse:collapse;font-size:13px}
    .mx-table thead{background:#f9fafb}
    .mx-table th,
    .mx-table td{padding:10px 12px;border-bottom:1px solid #e5e7eb;text-align:left;color:var(--mx-text)}
    .mx-table th{font:700 11px/1.2 ui-sans-serif;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af}
    .mx-table tbody tr:hover{background:#f8fafc}
    .mx-tag{display:inline-flex;align-items:center;gap:4px;border-radius:999px;padding:3px 9px;font-size:11px;font-weight:600;background:#ecfdf5;color:#047857}
    .mx-tag-dot{width:6px;height:6px;border-radius:999px;background:#22c55e}

    .btn-link{background:transparent;border:none;padding:0;font:600 12px/1 ui-sans-serif;color:#0f172a;text-decoration:underline;cursor:pointer}
    .btn-link.red{color:#dc2626}
    .btn-link.red:hover{color:#b91c1c}

    .mx-empty{margin:14px 20px 20px;padding:30px;border:2px dashed var(--mx-line);border-radius:16px;text-align:center;background:#fff;color:#64748b}
    .mx-empty span{font-size:30px;display:block;margin-bottom:6px}
  </style>

  <div class="mx-wrap">
    <div class="mx-card">
      <div class="mx-head">
        <div class="mx-left">
          <div class="mx-ico">📅</div>
          <div>
            <h1 class="mx-title">Lista de Citas</h1>
            <p class="mx-sub">Administra todas las citas y sus detalles</p>
          </div>
        </div>
        <a href="{{ route('citas.create') }}" class="btn btn-primary">
          + Nueva Cita
        </a>
      </div>

      {{-- KPIs --}}
      <div class="mx-kpis">
        <div class="mx-kpi">
          <div class="n">{{ $citas->count() }}</div>
          <div class="l">Citas Totales</div>
        </div>
        <div class="mx-kpi">
          <div class="n">{{ $citas->where('estado', 'pendiente')->count() }}</div>
          <div class="l">Pendientes</div>
        </div>
        <div class="mx-kpi">
          <div class="n">{{ $citas->where('estado', 'completada')->count() }}</div>
          <div class="l">Completadas</div>
        </div>
        <div class="mx-kpi">
          <div class="n">{{ $citas->where('estado', 'cancelada')->count() }}</div>
          <div class="l">Canceladas</div>
        </div>
      </div>

      {{-- Buscador --}}
      <div class="mx-toolbar">
        <form class="mx-search-wrap" method="GET" action="{{ route('citas.index') }}">
          <span class="mx-search-ico">🔍</span>
          <input
            type="text"
            name="q"
            class="mx-search"
            value="{{ request('q') }}"
            placeholder="Buscar por mascota, motivo o fecha..."
          >
        </form>
      </div>

      {{-- Tabla --}}
      @if($citas->isEmpty())
        <div class="mx-empty">
          <span>📭</span>
          No hay citas registradas todavía.
        </div>
      @else
        <div class="mx-table-wrap">
          <table class="mx-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Mascota</th>
                <th>Motivo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th style="text-align:right">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($citas as $cita)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $cita->mascota->nombre ?? '—' }}</td>
                  <td>{{ $cita->motivo }}</td>
                  <td>{{ $cita->fecha }}</td>
                  <td>{{ $cita->hora ?? '—' }}</td>
                  <td>
                    <span class="mx-tag" style="background: {{ $cita->estado=='pendiente'?'#fef3c7':($cita->estado=='completada'?'#d1fae5':'#fee2e2') }};
                                                  color: {{ $cita->estado=='pendiente'?'#92400e':($cita->estado=='completada'?'#065f46':'#991b1b') }}">
                      {{ ucfirst($cita->estado) }}
                    </span>
                  </td>
                  <td style="text-align:right">
                    <a href="{{ route('citas.edit', $cita) }}" class="btn-link">Editar</a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" style="display:inline-block;margin-left:8px" onsubmit="return confirm('¿Eliminar esta cita?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-link red">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-4">
            {{ $citas->links() }}
          </div>
        </div>
      @endif

    </div>
  </div>
</x-app-layout>
