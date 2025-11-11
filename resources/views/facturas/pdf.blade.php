@php
  $fmt = fn($v) => '$ '.number_format((float)$v, 0, ',', '.');
  $estado = strtolower($factura->estado ?? 'pendiente');
  $colores = [
    'pendiente' => ['#FEF3C7', '#92400E', '#FDE68A'],
    'pagada'    => ['#D1FAE5', '#065F46', '#86EFAC'],
    'anulada'   => ['#FEE2E2', '#991B1B', '#FECACA'],
  ];
  [$bg, $color, $border] = $colores[$estado] ?? ['#E5E7EB', '#374151', '#D1D5DB'];
@endphp

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Factura #{{ str_pad($factura->id, 6, '0', STR_PAD_LEFT) }}</title>
<style>
  body { font-family: DejaVu Sans, sans-serif; color:#1e293b; font-size:11px; margin:20px; }
  .header { background:#0284c7; color:white; padding:15px 20px; border-radius:10px; }
  .header h1 { margin:0; font-size:20px; }
  .banner { margin-top:10px; display:flex; justify-content:space-between; }
  .status { background:{{ $bg }}; color:{{ $color }}; border:2px solid {{ $border }};
            border-radius:15px; padding:3px 10px; font-weight:700; font-size:10px; }
  .section { margin-top:20px; }
  .table { width:100%; border-collapse:collapse; margin-top:10px; }
  th, td { border:1px solid #e5e7eb; padding:8px; font-size:10.5px; }
  th { background:#1e293b; color:white; text-transform:uppercase; font-size:9px; }
  tr:nth-child(even) { background:#f9fafb; }
  .summary { margin-top:15px; width:50%; float:right; }
  .summary td { padding:6px 8px; }
  .total { background:#bfdbfe; border:2px solid #3b82f6; font-weight:900; }
  .footer { text-align:center; font-size:9px; color:#64748b; margin-top:40px; border-top:1px solid #e5e7eb; padding-top:10px; }
</style>
</head>
<body>

  {{-- ENCABEZADO --}}
  <div class="header">
    <h1>VetApp Clínica</h1>
    <small>Sistema de Gestión Veterinaria</small>
    <div class="banner">
      <div>
        <strong>Factura #{{ str_pad($factura->id,6,'0',STR_PAD_LEFT) }}</strong><br>
        Historia: {{ $factura->historia_id ?? 'N/D' }}
      </div>
      <div>
        <div class="status">{{ ucfirst($estado) }}</div>
        <small>{{ $factura->created_at?->format('d/m/Y H:i') }}</small>
      </div>
    </div>
  </div>

  {{-- CLIENTE --}}
  <div class="section">
    <h3>Cliente</h3>
    <table class="table">
      <tr><td><b>Cliente:</b></td><td>{{ $factura->cliente ?? 'No especificado' }}</td></tr>
      <tr><td><b>Mascota:</b></td><td>{{ $factura->mascota ?? 'No especificada' }}</td></tr>
      <tr><td><b>Atendido por:</b></td><td>{{ optional(\App\Models\User::find($factura->user_id))->name ?? 'No especificado' }}</td></tr>
      <tr><td><b>Impuesto:</b></td><td>{{ rtrim(rtrim(number_format($factura->impuesto ?? 0,2,',','.'),'0'),',') }}%</td></tr>
    </table>
  </div>

  {{-- ITEMS --}}
  <div class="section">
    <h3>Servicios y Productos</h3>
    <table class="table">
      <thead>
        <tr><th>Descripción</th><th>Cant.</th><th>Precio Unit.</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        @forelse($factura->items as $it)
          <tr>
            <td>{{ $it->descripcion }}</td>
            <td style="text-align:center">{{ $it->cantidad }}</td>
            <td style="text-align:right">{{ $fmt($it->precio) }}</td>
            <td style="text-align:right">{{ $fmt($it->subtotal) }}</td>
          </tr>
        @empty
          <tr><td colspan="4" style="text-align:center; color:#9ca3af">No hay items registrados</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- RESUMEN --}}
  @php $impVal = round(($factura->subtotal ?? 0) * (($factura->impuesto ?? 0)/100)); @endphp
  <table class="summary">
    <tr><td>Subtotal</td><td style="text-align:right">{{ $fmt($factura->subtotal ?? 0) }}</td></tr>
    <tr><td>Impuesto ({{ $factura->impuesto ?? 0 }}%)</td><td style="text-align:right">{{ $fmt($impVal) }}</td></tr>
    <tr class="total"><td>Total a pagar</td><td style="text-align:right">{{ $fmt($factura->total ?? 0) }}</td></tr>
  </table>

  {{-- ESTADO --}}
  @if($factura->estado === 'pagada' && $factura->paid_at)
    <div style="margin-top:10px; text-align:center; color:#065f46;">
      ✅ Pagada el {{ \Carbon\Carbon::parse($factura->paid_at)->format('d/m/Y H:i') }}
    </div>
  @elseif($factura->estado === 'anulada')
    <div style="margin-top:10px; text-align:center; color:#991b1b;">
      ⚠️ Factura anulada — sin validez
    </div>
  @endif

  {{-- FOOTER --}}
  <div class="footer">
    VetApp - {{ now()->format('d/m/Y H:i:s') }}<br>
    soporte@vetapp.com · www.vetapp.com
  </div>

</body>
</html>
