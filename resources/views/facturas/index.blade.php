<x-app-layout>
  <style>
    :root {
      --bg: #f8fafc;
      --card: #ffffff;
      --ink: #0f172a;
      --ink2: #475569;
      --muted: #94a3b8;
      --ring: #e2e8f0;
      --sky: #0284c7;
      --shadow: 0 8px 26px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .wrap { max-width: 1150px; margin: 30px auto; padding: 0 18px; }
    .card {
      background: var(--card);
      border: 1px solid var(--ring);
      border-radius: 20px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid var(--ring);
      background: linear-gradient(135deg, #0ea5e9, #0369a1);
      color: #fff;
    }

    .title {
      margin: 0;
      font: 800 21px/1.2 "Inter", ui-sans-serif;
      letter-spacing: -0.5px;
    }

    .btn {
      appearance: none;
      border: none;
      border-radius: 12px;
      padding: 0.6rem 1rem;
      font: 700 14px "Inter", ui-sans-serif;
      color: #fff;
      background: #0ea5e9;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      box-shadow: 0 2px 6px rgba(14, 165, 233, .3);
      transition: all .2s ease;
    }

    .btn:hover { background: #0369a1; transform: translateY(-1px); }

    .body { padding: 18px 22px; background: var(--card); }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
    }

    th {
      background: #f1f5f9;
      font: 700 12px "Inter", ui-sans-serif;
      color: #475569;
      text-align: left;
      padding: 10px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      border-bottom: 1px solid #e2e8f0;
    }

    td {
      font: 500 14px "Inter", ui-sans-serif;
      color: #0f172a;
      padding: 12px 10px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }

    tr:hover td { background: #f9fafb; transition: background 0.2s; }

    .r { text-align: right; }

    .chip {
      font: 800 11px "Inter", ui-sans-serif;
      border-radius: 999px;
      padding: 4px 8px;
      border: 1px solid;
      display: inline-block;
      text-transform: capitalize;
    }

    .pendiente { background: #fef3c7; border-color: #fde68a; color: #b45309; }
    .pagada    { background: #dcfce7; border-color: #bbf7d0; color: #065f46; }
    .anulada   { background: #fee2e2; border-color: #fecaca; color: #b91c1c; }

    /* Paginación Laravel */
    .pagination {
      display: flex;
      justify-content: flex-end;
      margin-top: 16px;
      font: 600 13px "Inter", ui-sans-serif;
    }
    .pagination a, .pagination span {
      margin-left: 4px;
      padding: 6px 10px;
      border-radius: 8px;
      text-decoration: none;
      color: var(--ink2);
      border: 1px solid transparent;
      transition: all .2s ease;
    }
    .pagination a:hover {
      background: #e0f2fe;
      border-color: #bae6fd;
      color: #0369a1;
    }
    .pagination .active span {
      background: #0284c7;
      color: white;
      border-color: #0284c7;
    }

    @media (max-width: 768px) {
      th:nth-child(4), td:nth-child(4),
      th:nth-child(5), td:nth-child(5),
      th:nth-child(6), td:nth-child(6) { display: none; }
      .btn { padding: .5rem .8rem; font-size: 13px; }
    }
  </style>

  <div class="wrap">
    <div class="card">
      <div class="head">
        <h1 class="title">📜 Facturas</h1>
        @if (Route::has('caja.pendientes'))
          <a href="{{ route('caja.pendientes') }}" class="btn">Volver</a>
        @else
          <a href="{{ url()->previous() }}" class="btn">Volver</a>
        @endif
      </div>

      <div class="body">
        <div style="overflow-x:auto;">
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
                @php $st = strtolower($f->estado ?? 'pendiente'); @endphp
                <tr>
                  <td>{{ $f->id }}</td>
                  <td>{{ $f->cliente ?: 'N/D' }}</td>
                  <td><span class="chip {{ $st }}">{{ ucfirst($f->estado ?? 'pendiente') }}</span></td>
                  <td class="r">{{ $f->items_count }}</td>
                  <td class="r">$ {{ number_format($f->subtotal ?? 0, 0, ',', '.') }}</td>
                  <td class="r">$ {{ number_format($f->total ?? 0, 0, ',', '.') }}</td>
                  <td class="r">{{ $f->created_at?->format('d/m/Y H:i') }}</td>
                  <td class="r">
                    <a href="{{ route('facturas.show', $f) }}" class="btn" style="padding:.45rem .8rem;font-size:13px;">Ver</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" style="text-align:center;color:#64748b;padding:22px;">
                    No hay facturas registradas aún.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="pagination">
          {{ $facturas->links() }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
