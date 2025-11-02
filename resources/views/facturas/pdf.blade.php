@php
  $fmt = fn($v) => '$ '.number_format((float)$v, 0, ',', '.');
  $estado = strtolower($factura->estado ?? 'pendiente');
  $chipBg = ['pendiente'=>'#FDE68A','pagada'=>'#BBF7D0','anulada'=>'#FECACA'][$estado] ?? '#E5E7EB';
  $chipColor = ['pendiente'=>'#B45309','pagada'=>'#065F46','anulada'=>'#991B1B'][$estado] ?? '#374151';
@endphp
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Factura #{{ $factura->id }}</title>
<style>
  @page { margin: 24px 28px; }
  *{ box-sizing:border-box; }
  body{ font-family: DejaVu Sans, Arial, sans-serif; color:#0f172a; font-size:12px; }
  .header{ display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px }
  .h-left{ display:flex; gap:10px; align-items:center }
  .ico{ width:34px; height:34px; border-radius:8px; background:#e0f2fe; color:#0369a1; display:flex; align-items:center; justify-content:center; font-size:16px }
  h1{ margin:0; font-size:18px }
  .muted{ color:#64748b; font-weight:600; font-size:11px }
  .chip{ display:inline-block; padding:3px 8px; border-radius:999px; font-weight:800; font-size:10px }
  .kv{ display:grid; grid-template-columns:1fr 1fr; gap:8px; margin:8px 0 14px }
  .k{ color:#475569; font-weight:700; font-size:11px }
  .v{ font-weight:800 }
  .card{ border:1px solid #e5e7eb; border-radius:10px; padding:10px }
  table{ width:100%; border-collapse:collapse }
  th{ text-align:left; font-size:11px; color:#475569; border-bottom:1px solid #e5e7eb; padding:8px 6px }
  td{ padding:8px 6px; border-bottom:1px solid #eef2f7 }
  .r{ text-align:right }
  .sum{ width:44%; margin-left:auto; margin-top:12px }
  .row{ display:flex; justify-content:space-between; margin:5px 0 }
  .row.total{ border-top:1px solid #e5e7eb; padding-top:8px; font-size:14px; font-weight:800 }
</style>
</head>
<body>
  <div class="header">
    <div class="h-left">
      <div class="ico">🧾</div>
      <div>
        <h1>Factura #{{ $factura->id }}</h1>
        <div class="muted">
          Historia #{{ $factura->historia_id ?? 'N/D' }} ·
          Estado: <span class="chip" style="background:{{ $chipBg }}; color:{{ $chipColor }}">{{ ucfirst($factura->estado ?? 'pendiente') }}</span>
        </div>
      </div>
    </div>
    <div style="text-align:right">
      <div style="font-weight:800">Fecha</div>
      <div>{{ $factura->created_at?->format('d/m/Y H:i') }}</div>
      @if($factura->estado === 'pagada' && $factura->paid_at)
        <div style="margin-top:6px">
          <div style="font-weight:800">Pagada el</div>
          <div>{{ \Carbon\Carbon::parse($factura->paid_at)->format('d/m/Y H:i') }}</div>
        </div>
      @endif
    </div>
  </div>

  <div class="kv">
    <div><div class="k">Cliente</div><div class="v">{{ $factura->cliente ?: 'N/D' }}</div></div>
    <div><div class="k">Mascota</div><div class="v">{{ $factura->mascota ?: 'N/D' }}</div></div>
    <div><div class="k">Atendido por</div><div class="v">
      @php $userName = optional(\App\Models\User::find($factura->user_id))->name; @endphp
      {{ $userName ?: 'N/D' }}
    </div></div>
    <div><div class="k">Impuesto aplicado</div><div class="v">{{ rtrim(rtrim(number_format($factura->impuesto ?? 0,2,',','.'), '0'), ',') }}%</div></div>
  </div>

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Descripción</th>
          <th class="r" style="width:80px">Cant.</th>
          <th class="r" style="width:120px">Precio</th>
          <th class="r" style="width:120px">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($factura->items as $it)
          <tr>
            <td>{{ $it->descripcion }}</td>
            <td class="r">{{ $it->cantidad }}</td>
            <td class="r">{{ $fmt($it->precio ?? 0) }}</td>
            <td class="r" style="font-weight:800">{{ $fmt($it->subtotal ?? 0) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @php $impVal = round(($factura->subtotal ?? 0) * (($factura->impuesto ?? 0)/100)); @endphp
    <div class="sum">
      <div class="row"><div>Subtotal</div><div>{{ $fmt($factura->subtotal ?? 0) }}</div></div>
      <div class="row"><div>Impuesto ({{ rtrim(rtrim(number_format($factura->impuesto ?? 0,2,',','.'), '0'), ',') }}%)</div><div>{{ $fmt($impVal) }}</div></div>
      <div class="row total"><div>Total</div><div>{{ $fmt($factura->total ?? 0) }}</div></div>
    </div>
  </div>
</body>
</html>
