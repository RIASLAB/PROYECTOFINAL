<x-app-layout>
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Historia clínica #{{ $historia->id }}</h1>

    <div class="bg-white shadow rounded-lg p-5 space-y-3">
      <div><span class="font-semibold">Mascota:</span> {{ $historia->cita->mascota->nombre ?? '—' }}</div>
      <div><span class="font-semibold">Veterinario:</span> {{ $historia->cita->vet->name ?? '—' }}</div>
      <div><span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($historia->cita->fecha)->format('d/m/Y') ?? '—' }}</div>
      <div><span class="font-semibold">Hora:</span> {{ $historia->cita->hora ?? '—' }}</div>
      <div><span class="font-semibold">Motivo:</span> {{ $historia->motivo }}</div>
      <div><span class="font-semibold">Anamnesis:</span> {{ $historia->anamnesis ?? '—' }}</div>
      <div><span class="font-semibold">Diagnóstico:</span> {{ $historia->diagnostico ?? '—' }}</div>
      <div><span class="font-semibold">Tratamiento:</span> {{ $historia->tratamiento ?? '—' }}</div>
      <div><span class="font-semibold">Recomendaciones:</span> {{ $historia->recomendaciones ?? '—' }}</div>
    </div>

    <div class="mt-6">
      <a href="{{ url()->previous() }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Volver</a>
    </div>
  </div>
</x-app-layout>
