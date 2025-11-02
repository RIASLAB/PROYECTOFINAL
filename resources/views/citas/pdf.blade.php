<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte de Citas</title>
<style>
  *{ font-family: DejaVu Sans, sans-serif; }
  body{ font-size: 12px; color:#111; }
  h1{ font-size:18px; margin:0 0 4px }
  .muted{ color:#666; font-size:11px }
  table{ width:100%; border-collapse: collapse; }
  th,td{ border:1px solid #ddd; padding:6px 8px; }
  th{ background:#f2f4f8; text-align:left; }
  .right{ text-align:right }
  .center{ text-align:center }
  .small{ font-size:11px }
  .chip{ padding:2px 6px; border-radius:10px; border:1px solid #ccc; font-size:11px }
  .chip.pendiente{ background:#fff7d6; border-color:#f0d98a }
  .chip.confirmada{ background:#dcfce7; border-color:#86efac }
  .chip.cancelada{ background:#fee2e2; border-color:#fca5a5 }
  .chip.completada{ background:#e0e7ff; border-color:#a5b4fc }
</style>
</head>
<body>
  <table style="border:none; margin-bottom:10px">
    <tr>
      <td style="border:none">
        <h1>Reporte de Citas</h1>
        <div class="muted">
          Usuario: <strong>{{ $user->name }}</strong><br>
          Generado: {{ $generado }}
          @if($desde || $hasta)
            <br>Rango: {{ $desde ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '—' }}
            – {{ $hasta ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '—' }}
          @endif
          @if(!empty($q)) <br>Búsqueda: “{{ $q }}” @endif
        </div>
      </td>
      <td class="right small" style="border:none">
        {{ config('app.name') }}<br>
        {{ config('app.url') }}
      </td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
        <th style="width:18%">Mascota</th>
        <th style="width:20%">Motivo</th>
        <th style="width:14%">Fecha</th>
        <th style="width:10%">Hora</th>
        <th style="width:26%">Observaciones</th>
        <th style="width:12%">Estado</th>
      </tr>
    </thead>
    <tbody>
      @forelse($citas as $c)
        @php
          $fecha = $c->fecha ? \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') : '—';
          $hora  = $c->hora  ? substr($c->hora,0,5) : '—';
          $estado = $c->estado ?? 'pendiente';
        @endphp
        <tr>
          <td>{{ optional($c->mascota)->nombre ?? '—' }}</td>
          <td>{{ $c->motivo }}</td>
          <td>{{ $fecha }}</td>
          <td class="center">{{ $hora }}</td>
          <td>{{ $c->observaciones ?: '—' }}</td>
          <td class="center">
            <span class="chip {{ $estado }}">{{ ucfirst($estado) }}</span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="center muted">No hay citas para los filtros aplicados.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
