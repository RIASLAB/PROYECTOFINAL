{{-- resources/views/facturas/index.blade.php --}}
<x-app-layout>
  <style>
    :root{--bg:#f6f9fc;--card:#fff;--ink:#0f172a;--ink2:#475569;--muted:#94a3b8;--ring:#e9eef6;--sky:#0ea5e9;--shadow:0 12px 38px rgba(2,6,12,.08)}
    body{background:var(--bg)}
    .wrap{max-width:1120px;margin:28px auto;padding:0 16px}
    .card{background:var(--card);border:1px solid var(--ring);border-radius:22px;box-shadow:var(--shadow)}
    .head{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border-bottom:1px solid var(--ring)}
    .title{margin:0;color:var(--ink);font:800 20px/1 ui-sans-serif,system-ui}
    .btn{appearance:none;border:1px solid #e5e7eb;border-radius:12px;padding:.55rem .9rem;font:800 14px ui-sans-serif;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;color:#0f172a;background:#fff}
    .btn:hover{background:#f8fafc}
    .body{padding:16px}
    table{width:100%;border-collapse:collapse}
    th{font:700 12px ui-sans-serif;color:#475569;text-align:left;padding:10px;border-bottom:1px solid #e9eef6}
    td{font:600 14px ui-sans-serif;color:#0f172a;padding:12px 10px;border-bottom:1px solid #eef2f7}
    .r{text-align:right}
    .chip{font:800 11px ui-sans-serif;border-radius:999px;padding:4px 8px;border:1px solid}
    .pendiente{background:#fef3c7;border-color:#fde68a;color:#b45309}
    .pagada{background:#dcfce7;border-color:#bbf7d0;color:#065f46}
    .anulada{background:#ffe4e6;border-color:#fecdd3;color:#be123c}
  </style>

  <div class="wrap">
    <div class="card">
      <div class="head">
        <h1 class="title">Facturas</h1>

        {{-- 🔙 Botón Volver a "Caja · Pendientes de cobro" --}}
        @if (Route::has('caja.pendientes'))
          <a href="{{ route('caja.pendientes') }}" class="btn">Volver</a>
        @else
          {{-- Fallback por si aún no existe la ruta --}}
          <a href="{{ url()->previous() }}" class="btn">Volver</a>
        @endif
      </div>

      <div class="body">
        <div style="overflow:auto">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th class="r">Items</th>
                <th class="r">Subtotal</th>
                <th class="r">Total</th>
                <th class="r">Fecha</th>
                <th class="r">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($facturas as $f)
                @php $st=strtolower($f->estado??'pendiente'); @endphp
                <tr>
                  <td>{{ $f->id }}</td>
                  <td>{{ $f->cliente ?: 'N/D' }}</td>
                  <td><span class="chip {{ $st }}">{{ ucfirst($f->estado ?? 'pendiente') }}</span></td>
                  <td class="r">{{ $f->items_count }}</td>
                  <td class="r">$ {{ number_format($f->subtotal ?? 0, 0, ',', '.') }}</td>
                  <td class="r">$ {{ number_format($f->total ?? 0, 0, ',', '.') }}</td>
                  <td class="r">{{ $f->created_at?->format('d/m/Y H:i') }}</td>
                  <td class="r"><a class="btn" href="{{ route('facturas.show',$f) }}">Ver</a></td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" style="text-align:center;color:#64748b">No hay facturas aún.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div style="margin-top:12px">
          {{ $facturas->links() }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
