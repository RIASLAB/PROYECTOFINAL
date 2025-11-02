{{-- resources/views/facturas/show.blade.php --}}
<x-app-layout>
  <style>
    :root{
      --bg:#f6f9fc; --card:#fff; --ink:#0f172a; --ink2:#475569; --muted:#94a3b8;
      --ring:#e9eef6; --ring2:#dfe7f2;
      --sky:#0ea5e9; --sky-100:#e0f2fe; --sky-700:#0369a1;
      --emerald:#10b981; --emerald-700:#059669;
      --rose-100:#ffe4e6; --rose-700:#be123c;
      --amber-100:#fef3c7; --amber-700:#b45309;
      --shadow:0 12px 38px rgba(2,6,12,.08);
    }
    body{background:var(--bg)}
    .fx-wrap{max-width:1120px;margin:28px auto;padding:0 16px}
    .fx-card{background:var(--card);border:1px solid var(--ring);border-radius:22px;box-shadow:var(--shadow)}

    .fx-header{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border-bottom:1px solid var(--ring)}
    .fx-brand{display:flex;align-items:center;gap:12px}
    .fx-ico{width:44px;height:44px;border-radius:14px;background:var(--sky-100);color:var(--sky-700);display:flex;align-items:center;justify-content:center;font-size:22px}
    .fx-title{margin:0;color:var(--ink);font:800 20px/1.1 ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto}
    .fx-sub{margin:0;color:var(--muted);font:600 12px/1 ui-sans-serif}

    .fx-btn{appearance:none;border:1px solid transparent;border-radius:12px;padding:.6rem 1rem;font:800 14px ui-sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem}
    .fx-btn-ghost{background:transparent;border-color:var(--ring2);color:var(--ink);}
    .fx-btn-ghost:hover{background:#f8fafc}
    .fx-btn-sky{background:var(--sky);color:#fff;border-color:var(--sky)}
    .fx-btn-sky:hover{background:#0284c7}
    .fx-btn-dark{background:#0f172a;color:#fff;border-color:#0f172a}
    .fx-btn-dark:hover{background:#1e293b}

    .fx-body{display:grid;grid-template-columns:1fr;gap:18px;padding:18px}
    @media(min-width:1024px){ .fx-body{grid-template-columns:2fr 1fr} }

    .fx-section-title{margin:0 0 8px;color:var(--ink);font:800 15px ui-sans-serif}
    .fx-meta{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:6px}
    @media(max-width:640px){ .fx-meta{grid-template-columns:1fr} }
    .fx-meta .k{color:var(--ink2);font:700 12px ui-sans-serif}
    .fx-meta .v{color:var(--ink);font:800 14px ui-sans-serif}

    .fx-chip{font:800 11px ui-sans-serif;border-radius:999px;padding:6px 10px;border:1px solid;display:inline-flex;align-items:center;gap:6px}
    .fx-chip.pendiente{background:var(--amber-100);border-color:#fde68a;color:var(--amber-700)}
    .fx-chip.pagada{background:#dcfce7;border-color:#bbf7d0;color:#065f46}
    .fx-chip.anulada{background:var(--rose-100);border-color:#fecdd3;color:var(--rose-700)}

    table.fx{width:100%;border-collapse:collapse}
    table.fx thead th{color:var(--ink2);font:700 12px ui-sans-serif;text-align:left;padding:10px;border-bottom:1px solid var(--ring)}
    table.fx tbody td{padding:12px 10px;border-bottom:1px solid var(--ring);color:var(--ink);font:600 14px ui-sans-serif}
    .fx-right{text-align:right}
    .fx-money{font-variant-numeric:tabular-nums}

    .fx-summary{background:#fff;border:1px solid var(--ring);border-radius:18px;padding:16px;box-shadow:var(--shadow);height:max-content}
    .fx-line{display:flex;justify-content:space-between;align-items:center;margin:8px 0}
    .fx-k{color:var(--ink2);font:600 14px ui-sans-serif}
    .fx-v{color:var(--ink);font:800 16px ui-sans-serif}

    /* Print */
    @media print{
      body{background:#fff}
      .fx-wrap{max-width:none;margin:0;padding:0}
      .fx-btn, .fx-btn-ghost, .fx-btn-dark, .fx-btn-sky{display:none !important}
      .fx-card{border:none;box-shadow:none}
    }
  </style>

  <div class="fx-wrap">
    @if(session('error'))
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:12px 14px;color:#991b1b;font:700 14px ui-sans-serif;margin-bottom:14px">
        {{ session('error') }}
      </div>
    @endif
    @if(session('ok'))
      <div style="background:#ecfdf5;border:1px solid #bbf7d0;border-radius:14px;padding:12px 14px;color:#065f46;font:700 14px ui-sans-serif;margin-bottom:14px">
        {{ session('ok') }}
      </div>
    @endif

    <div class="fx-card">
      {{-- HEADER --}}
      <div class="fx-header">
        <div class="fx-brand">
          <div class="fx-ico">🧾</div>
          <div>
            <h1 class="fx-title">Factura #{{ $factura->id }}</h1>
            <p class="fx-sub">
              Historia #{{ $factura->historia_id ?? 'N/D' }} ·
              Estado:
              @php $st = strtolower($factura->estado ?? 'pendiente'); @endphp
              <span class="fx-chip {{ $st }}">{{ ucfirst($factura->estado ?? 'pendiente') }}</span>
            </p>
          </div>
        </div>

        {{-- === ZONA DE BOTONES (CORREGIDA) === --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end">
          @if($factura->estado === 'pendiente')
            <form method="POST" action="{{ route('facturas.pagar', $factura) }}">
              @csrf @method('PATCH')
              <button class="fx-btn fx-btn-sky" onclick="return confirm('¿Marcar esta factura como pagada?')">
                Marcar como pagada
              </button>
            </form>
            <form method="POST" action="{{ route('facturas.anular', $factura) }}">
              @csrf @method('PATCH')
              <button class="fx-btn fx-btn-ghost" onclick="return confirm('¿Anular esta factura?')">
                Anular
              </button>
            </form>
          @elseif($factura->estado === 'pagada')
            <span class="fx-chip pagada">Pagada</span>
            <form method="POST" action="{{ route('facturas.anular', $factura) }}">
              @csrf @method('PATCH')
              <button class="fx-btn fx-btn-ghost" onclick="return confirm('¿Anular la factura pagada?')">
                Anular
              </button>
            </form>
          @elseif($factura->estado === 'anulada')
            <span class="fx-chip anulada">Anulada</span>
          @endif

          {{-- PDF / Imprimir / Volver --}}
          <a href="{{ route('facturas.pdf', $factura) }}" class="fx-btn fx-btn-sky">Descargar PDF</a>
          <button onclick="window.print()" class="fx-btn fx-btn-ghost">Imprimir</button>
          <a href="{{ route('facturas.index') }}" class="fx-btn fx-btn-dark">Volver</a>
        </div>
      </div>

      {{-- BODY --}}
      <div class="fx-body">
        {{-- Columna izquierda: Detalle --}}
        <div>
          <div class="fx-section-title">Detalle</div>

          {{-- Meta rápida --}}
          <div class="fx-meta">
            <div>
              <div class="k">Cliente</div>
              <div class="v">{{ $factura->cliente ?: 'N/D' }}</div>
            </div>
            <div>
              <div class="k">Mascota</div>
              <div class="v">{{ $factura->mascota ?: 'N/D' }}</div>
            </div>
            <div>
              <div class="k">Fecha de creación</div>
              <div class="v">{{ $factura->created_at?->format('d/m/Y H:i') }}</div>
            </div>
            <div>
              <div class="k">Atendido por</div>
              <div class="v">
                @php $userName = optional(\App\Models\User::find($factura->user_id))->name; @endphp
                {{ $userName ?: 'N/D' }}
              </div>
            </div>
          </div>

          {{-- Tabla de ítems --}}
          <div style="background:#fff;border:1px solid var(--ring);border-radius:18px;padding:8px">
            <table class="fx">
              <thead>
                <tr>
                  <th>Descripción</th>
                  <th class="fx-right" style="width:110px">Cant.</th>
                  <th class="fx-right" style="width:160px">Precio</th>
                  <th class="fx-right" style="width:160px">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach($factura->items as $it)
                  <tr>
                    <td>{{ $it->descripcion }}</td>
                    <td class="fx-right">{{ $it->cantidad }}</td>
                    <td class="fx-right fx-money">$ {{ number_format($it->precio ?? 0, 0, ',', '.') }}</td>
                    <td class="fx-right fx-money" style="font-weight:800">$ {{ number_format($it->subtotal ?? 0, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- Columna derecha: Resumen --}}
        <div class="fx-summary">
          <div class="fx-section-title" style="margin-bottom:10px">Resumen</div>

          <div class="fx-line">
            <div class="fx-k">Subtotal</div>
            <div class="fx-v fx-money">$ {{ number_format($factura->subtotal ?? 0, 0, ',', '.') }}</div>
          </div>

          <div class="fx-line">
            <div class="fx-k">Impuesto</div>
            <div class="fx-v">{{ rtrim(rtrim(number_format($factura->impuesto ?? 0,2,',','.'), '0'), ',') }}%</div>
          </div>

          @php
            $impVal = round(($factura->subtotal ?? 0) * (($factura->impuesto ?? 0)/100));
          @endphp
          <div class="fx-line" style="opacity:.9">
            <div class="fx-k">Valor impuesto</div>
            <div class="fx-v fx-money">$ {{ number_format($impVal, 0, ',', '.') }}</div>
          </div>

          <div class="fx-line" style="border-top:1px solid var(--ring);padding-top:10px">
            <div class="fx-k" style="font-weight:800;color:var(--ink)">Total</div>
            <div class="fx-v fx-money" style="font-size:18px">$ {{ number_format($factura->total ?? 0, 0, ',', '.') }}</div>
          </div>

          @if($factura->estado === 'pagada' && $factura->paid_at)
            <div class="fx-line" style="margin-top:10px">
              <div class="fx-k">Pagada el</div>
              <div class="fx-v">{{ \Carbon\Carbon::parse($factura->paid_at)->format('d/m/Y H:i') }}</div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
