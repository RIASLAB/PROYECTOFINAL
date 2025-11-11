<x-app-layout>
  <style>
    :root{
      --rx-primary:#0ea5e9;
      --rx-indigo:#6366f1;
      --rx-amber:#f59e0b;
      --rx-emerald:#10b981;
      --rx-surface:#ffffff;
      --rx-border:#e5e7eb;
      --rx-text:#0f172a;
    }
    .rx-wrap{max-width:1200px;margin:24px auto;padding:0 16px;}
    .rx-card{background:var(--rx-surface);border:1px solid var(--rx-border);border-radius:20px;box-shadow:0 6px 18px rgba(2,6,12,.06);}
    .rx-header{display:flex;justify-content:space-between;align-items:center;padding:18px 20px;border-bottom:1px solid var(--rx-border);}
    .rx-header-left{display:flex;align-items:center;gap:12px}
    .rx-icon{width:40px;height:40px;border-radius:12px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;font-size:20px;color:#0284c7}
    .rx-title{font:600 20px/1.1 ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto;color:var(--rx-text)}
    .rx-sub{font:500 13px/1.2 ui-sans-serif;color:#64748b}

    /* GRID de tarjetas */
    .rx-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;padding:20px;}
    .rx-item{border:1px solid var(--rx-border);border-radius:18px;padding:16px;display:flex;flex-direction:column;min-height:160px;background:#fff}
    .rx-meta{display:flex;justify-content:space-between;font:500 12px/1.2 ui-sans-serif;color:#64748b}
    .rx-chip{background:#f1f5f9;border:1px solid #e2e8f0;border-radius:999px;padding:4px 8px;font-size:11px;color:#64748b}
    .rx-h{margin:6px 0 4px;font:700 16px/1.2 ui-sans-serif;color:#0f172a}
    .rx-body{font:400 13px/1.5 ui-sans-serif;color:#334155;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .rx-notas{font:400 12px/1.4 ui-sans-serif;color:#64748b;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}

    .rx-actions{margin-top:auto;display:flex;justify-content:flex-end;gap:8px}
    .btn{appearance:none;border:1px solid transparent;border-radius:12px;padding:8px 12px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
    .btn-sky{background:var(--rx-primary);color:#fff;border-color:var(--rx-primary)} .btn-sky:hover{background:#0284c7}
    .btn-indigo{background:var(--rx-indigo);color:#fff;border-color:var(--rx-indigo)} .btn-indigo:hover{background:#4f46e5}
    .btn-amber{background:var(--rx-amber);color:#111827;border-color:var(--rx-amber)} .btn-amber:hover{background:#d97706;color:#fff}
    .btn-emerald{background:var(--rx-emerald);color:#fff;border-color:var(--rx-emerald)} .btn-emerald:hover{background:#059669}



        .btn-gray{
      background:#f1f5f9;
      color:#0f172a;
      border-color:#e5e7eb;
    }
    .btn-gray:hover{
      background:#e5e7eb;
    }

    .rx-empty{margin:20px;padding:40px;border:1px dashed var(--rx-border);border-radius:16px;text-align:center;background:#fff}
    .rx-empty p{color:#475569;margin-top:6px}
    .rx-alert{margin:16px 20px 0;border:1px solid #86efac;background:#f0fdf4;color:#166534;padding:12px;border-radius:12px}
  </style>

  @php
    // Acciones con fallback por si Route::has(...) no detecta.
    $enviarCajaAction = \Illuminate\Support\Facades\Route::has('historias.enviarCaja')
      ? route('historias.enviarCaja', $historia)
      : url('/historias/'.$historia->id.'/enviar-caja');

    $retirarCajaAction = \Illuminate\Support\Facades\Route::has('historias.retirarCaja')
      ? route('historias.retirarCaja', $historia)
      : url('/historias/'.$historia->id.'/retirar-caja');

    $facturarAction = \Illuminate\Support\Facades\Route::has('facturas.createFromHistoria')
      ? route('facturas.createFromHistoria', $historia->id)
      : url('/facturas/crear-desde-historia/'.$historia->id);
  @endphp

  <div class="rx-wrap">
    <div class="rx-card">
      <div class="rx-header">
        <div class="rx-header-left">
          <div class="rx-icon">🧾</div>
          <div>
            <div class="rx-title">Recetas · Historia #{{ $historia->id }}</div>
            <div class="rx-sub">Listado de recetas creadas para esta historia clínica.</div>
          </div>
        </div>

        {{-- Botones a la derecha (misma estética) --}}
        <div class="flex items-center gap-2">
           {{-- 🔙 Volver a "Mis historias clínicas" según el rol --}}
          @php
              $role = auth()->user()->role ?? null;

              // Elegimos la ruta según el rol
              $backRouteName = match ($role) {
                  'veterinario' => 'vet.historias.mine',
                  'user'        => 'client.historias.mine',
                  default       => null,
              };
          @endphp

          @if($backRouteName)
            <a href="{{ route($backRouteName) }}" class="btn btn-gray">
              ← Volver a historias
            </a>
          @endif
          {{-- Vet/Admin: Enviar a caja / Retirar de caja --}}
          @if(auth()->check() && in_array(auth()->user()->role, ['veterinario','admin']))
            @if(!empty($historia->pendiente_cobro) && $historia->pendiente_cobro)
              <form method="POST" action="{{ $retirarCajaAction }}">
                @csrf
                <button type="submit" class="btn btn-amber" title="Quitar de caja y volver al estado normal">
                  Retirar de caja
                </button>
              </form>
            @else
              @if($recetas->count() > 0)
                <form method="POST" action="{{ $enviarCajaAction }}">
                  @csrf
                  <button type="submit" class="btn btn-emerald" title="Enviar todas las recetas de esta historia a caja">
                    Enviar a caja
                  </button>
                </form>
              @endif
            @endif
          @endif

          {{-- Recepción/Admin: Facturar historia completa --}}
          @if(auth()->check() && in_array(auth()->user()->role, ['recepcionista','admin']) && $recetas->count() > 0)
            <a href="{{ $facturarAction }}" class="btn btn-emerald">
              💰 Facturar historia
            </a>
          @endif

          {{-- Botón existente --}}
          <a href="{{ route('vet.recetas.create', $historia) }}" class="btn btn-sky">
            + Nueva receta
          </a>
        </div>
      </div>

      @if(session('ok'))
        <div class="rx-alert">{{ session('ok') }}</div>
      @endif

      @if($recetas->isEmpty())
        <div class="rx-empty">
          <div style="font-size:26px">🗂️</div>
          <p>No hay recetas aún.</p>
          <div style="margin-top:10px">
            <a href="{{ route('vet.recetas.create', $historia) }}" class="btn btn-sky">Crear la primera</a>
          </div>
        </div>
      @else
        <div class="rx-grid">
          @foreach($recetas as $r)
            <div class="rx-item">
              <div class="rx-meta">
                <span>{{ ($r->fecha ?? $r->created_at)->format('d/m/Y · H:i') }}</span>
                <span class="rx-chip">Receta</span>
              </div>

              <div class="rx-h">{{ $r->titulo ?: 'Sin título' }}</div>

              @if($r->indicaciones)
                <div class="rx-body">{{ $r->indicaciones }}</div>
              @endif

              @if(!empty($r->notas))
                <div class="rx-notas">{{ $r->notas }}</div>
              @endif

              <div class="rx-actions">
                <a href="{{ route('vet.recetas.show', $r) }}" class="btn btn-indigo">Ver</a>

                @if(Route::has('vet.recetas.edit'))
                  <a href="{{ route('vet.recetas.edit', $r) }}" class="btn btn-amber">Editar</a>
                @endif

                @if(Route::has('vet.recetas.pdf'))
                  <a href="{{ route('vet.recetas.pdf', $r) }}" class="btn btn-emerald">PDF</a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        @if(method_exists($recetas, 'links'))
          <div style="padding: 0 20px 20px;">{{ $recetas->links() }}</div>
        @endif
      @endif
    </div>
  </div>
</x-app-layout>
