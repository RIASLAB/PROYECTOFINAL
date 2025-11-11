@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 pt-24 pb-16"> {{-- Padding top y bottom ajustado --}}

  {{-- BANDA SUPERIOR --}}
  <div role="banner" class="fixed top-0 left-0 w-full !bg-blue-700 !text-white shadow-lg z-40">
    <div class="max-w-3xl mx-auto px-6 py-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        {{-- Ícono editar --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
             class="w-9 h-9 !text-white" fill="none" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.862 4.487l1.651 1.651m-6.015 6.015l1.651 1.651m2.713-8.666a2.333 2.333 0 1 1 3.3 3.3L9.75 19.5l-4.5 1.2 1.2-4.5 10.575-10.713z"/>
        </svg>
        <div>
          <h1 class="text-2xl font-semibold leading-tight !text-white">Editar Cita</h1>
          <p class="text-sm !text-white/90">Actualiza la información de la cita</p>
        </div>
      </div>

      <a href="{{ route('citas.index') }}"
         class="inline-flex items-center gap-2 rounded-full bg-white/20 hover:bg-white/40 px-4 py-2 text-sm font-semibold text-white border border-white/30 transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver
      </a>
    </div>
  </div>

  {{-- TARJETA FLOTANTE --}}
  <div class="max-w-3xl mx-auto px-4 relative z-10">
    <div class="bg-white shadow-2xl ring-1 ring-black/5 rounded-2xl overflow-hidden border border-gray-100 transition-transform transform hover:-translate-y-1 hover:shadow-2xl">

      <div class="px-6 pt-6 pb-2">
        <h2 class="text-lg font-semibold text-gray-800">Datos de la Cita</h2>
        <p class="text-sm text-gray-500">Edita los campos necesarios.</p>
      </div>
      <div class="h-px bg-gray-100 mx-6"></div>

      <div class="p-6 sm:p-8">
        @if ($errors->any())
          <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 flex items-start gap-2">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
            </svg>
            <div>
              <p class="font-medium mb-1">Revisa estos campos:</p>
              <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
              </ul>
            </div>
          </div>
        @endif

        <form action="{{ route('citas.update', $cita) }}" method="POST" class="space-y-8">
          @csrf @method('PUT')

          {{-- Mascota --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mascota <span class="text-red-500">*</span></label>
            <select name="mascota_id"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition"
                    required>
              @foreach($mascotas as $m)
                <option value="{{ $m->id }}" @selected(old('mascota_id', $cita->mascota_id)==$m->id)>{{ $m->nombre }}</option>
              @endforeach
            </select>
          </div>

          {{-- Motivo --}}
          <div>
            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo <span class="text-red-500">*</span></label>
            <input id="motivo" name="motivo" type="text" value="{{ old('motivo', $cita->motivo) }}"
                   placeholder="Describe el motivo de la cita"
                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" required>
          </div>

          {{-- Fecha y Hora --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
              <input id="fecha" name="fecha" type="date" value="{{ old('fecha', $cita->fecha) }}"
                     class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition" required>
            </div>
            <div>
              <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
              <input id="hora" name="hora" type="time" value="{{ old('hora', $cita->hora) }}"
                     class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
            </div>
          </div>

          {{-- Observaciones --}}
          <div>
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="4"
                      placeholder="Agrega detalles adicionales si lo deseas"
                      class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition resize-vertical">{{ old('observaciones', $cita->observaciones) }}</textarea>
          </div>

          <input type="hidden" name="estado" value="{{ old('estado', $cita->estado ?? 'pendiente') }}">

          {{-- Botones --}}
          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6">
            <a href="{{ route('citas.index') }}"
               class="inline-flex justify-center items-center rounded-xl border border-gray-300 px-6 py-3 text-gray-700 hover:bg-gray-50 transition">
              Cancelar
            </a>
            <button type="submit"
                    class="inline-flex justify-center items-center rounded-xl bg-emerald-600 px-6 py-3 text-white font-semibold hover:bg-emerald-700 transition">
              Actualizar Cita
            </button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>
@endsection
