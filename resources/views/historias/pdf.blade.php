<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Historia clínica #{{ $historia->id }}</title>
<style>
  *{ font-family: DejaVu Sans, sans-serif; }
  body{ font-size: 12px; color:#111; }
  h1{ font-size:18px; margin:0 0 4px }
  h2{ font-size:14px; margin:18px 0 6px; border-bottom:1px solid #ccc; padding-bottom:3px }
  .muted{ color:#666; font-size:11px }
  table{ width:100%; border-collapse: collapse; }
  th,td{ border:1px solid #ddd; padding:6px 8px; vertical-align: top; text-align:left }
  th{ background:#f2f4f8; width:28% }
  p{ margin:4px 0 }
</style>
</head>
<body>
  <h1>Lugo Vet · Historia clínica #{{ $historia->id }}</h1>
  <div class="muted">Generado el {{ $generado }}</div>

  @php
    $cita = $historia->cita;
  @endphp
  <h2>Datos de la consulta</h2>
  <table>
    <tr><th>Mascota</th><td>{{ $cita->mascota->nombre ?? '—' }} ({{ $cita->mascota->especie ?? '—' }}{{ !empty($cita->mascota->raza) ? ', '.$cita->mascota->raza : '' }})</td></tr>
    <tr><th>Fecha y hora</th><td>{{ $cita ? \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') : '—' }} · {{ $cita->hora ?? '' }}</td></tr>
    <tr><th>Servicio</th><td>{{ $cita->servicio_nombre ?? '—' }}</td></tr>
    <tr><th>Veterinario</th><td>{{ $cita->vet->name ?? '—' }}</td></tr>
  </table>

  <h2>Registro clínico</h2>
  <table>
    <tr><th>Motivo</th><td>{{ $historia->motivo }}</td></tr>
    <tr><th>Anamnesis</th><td>{!! nl2br(e($historia->anamnesis ?? '—')) !!}</td></tr>
    <tr><th>Diagnóstico</th><td>{!! nl2br(e($historia->diagnostico ?? '—')) !!}</td></tr>
    <tr><th>Tratamiento</th><td>{!! nl2br(e($historia->tratamiento ?? '—')) !!}</td></tr>
    <tr><th>Recomendaciones</th><td>{!! nl2br(e($historia->recomendaciones ?? '—')) !!}</td></tr>
  </table>

  <h2>Recetas</h2>
  @forelse($historia->recetas as $r)
    <p><strong>{{ optional($r->fecha)->format('d/m/Y') }}:</strong> {!! nl2br(e($r->indicaciones)) !!}</p>
    @foreach($r->items as $it)
      <p>&nbsp;&nbsp;• {{ $it->medicamento }}{{ $it->dosis ? ' · '.$it->dosis : '' }}{{ $it->frecuencia ? ' · '.$it->frecuencia : '' }}{{ $it->duracion ? ' · '.$it->duracion : '' }}{{ $it->via ? ' · vía '.$it->via : '' }}</p>
    @endforeach
    @if($r->notas)<p class="muted">Notas: {{ $r->notas }}</p>@endif
  @empty
    <p class="muted">Esta historia no tiene recetas.</p>
  @endforelse
</body>
</html>
