@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

  {{-- BANDA SUPERIOR: usamos <div role="banner"> para evitar reglas globales sobre <header>.
       Forzamos color con utilidades !important (!bg-… !text-…) --}}
  <div role="banner" class="!bg-blue-700 !text-white shadow-lg relative z-20">
    <div class="max-w-3xl mx-auto px-6 py-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        {{-- Ícono calendario (blanco) --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
             class="w-9 h-9 !text-white" fill="none" stroke="currentColor" stroke-width="1.8">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 6.75h15A1.5 1.5 0 0 1 21 8.25v9A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25v-9A1.5 1.5 0 0 1 4.5 6.75z" />
        </svg>
        <div>
          <h1 class="text-2xl font-semibold leading-tight !text-white">Registrar Nueva Cita</h1>
          <p class="text-sm !text-white/90">Agrega una cita para una de tus mascotas</p>
        </div>
      </div>

      {{-- Botón VOLVER dentro del banner --}}
      <a href="{{ route('citas.index') }}"
         class="inline-flex items-center gap-2 rounded-full bg-white/15 hover:bg-white/25 px-4 py-2 text-sm border border-white/40 transition-colors !text-white">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver
      </a>
    </div>
  </div>

  {{-- TARJETA FLOTANTE CENTRADA --}}
  <div class="max-w-3xl mx-auto px-4 pb-16 -mt-8 relative z-30">
    <div class="bg-white shadow-2xl ring-1 ring-black/5 rounded-2xl overflow-hidden border border-gray-100">

      {{-- Cabecera interna --}}
      <div class="px-6 pt-6 pb-2">
        <h2 class="text-lg font-semibold text-gray-800">Datos de la Cita</h2>
        <p class="text-sm text-gray-500">Completa los siguientes campos.</p>
      </div>
      <div class="h-px bg-gray-100 mx-6"></div>

      {{-- Formulario --}}
      <div class="p-6 sm:p-8">
        @if ($errors->any())
          <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3">
            <p class="font-medium mb-1">Revisa estos campos:</p>
            <ul class="list-disc list-inside text-sm">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('citas.store') }}" method="POST" class="space-y-8">
          @csrf

          {{-- Mascota --}}
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mascota <span class="text-red-500">*</span></label>
            <select name="mascota_id"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    required>
              <option value="">Seleccione una mascota</option>
              @foreach($mascotas as $m)
                <option value="{{ $m->id }}" @selected(old('mascota_id')==$m->id)>{{ $m->nombre }}</option>
              @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Solo aparecen tus mascotas registradas.</p>
          </div>

          {{-- Motivo --}}
          <div>
            <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">Motivo <span class="text-red-500">*</span></label>
            <input id="motivo" name="motivo" type="text" value="{{ old('motivo') }}"
                   class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
          </div>

          {{-- Fecha y Hora --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
              <input id="fecha" name="fecha" type="date" value="{{ old('fecha') }}"
                     class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
            </div>
            <div>
              <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
              <input id="hora" name="hora" type="time" value="{{ old('hora') }}"
                     class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
              <p class="text-xs text-gray-500 mt-1">Puedes dejarla vacía y asignarla después.</p>
            </div>
          </div>

          {{-- Observaciones --}}
          <div>
            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
            <textarea id="observaciones" name="observaciones" rows="4"
                      class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('observaciones') }}</textarea>
          </div>

          <input type="hidden" name="estado" value="pendiente">

          {{-- Acciones --}}
          <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('citas.index') }}"
               class="inline-flex items-center rounded-xl border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                    class="inline-flex justify-center items-center rounded-xl bg-blue-600 px-5 py-2.5 text-white font-medium hover:bg-blue-700">
              Guardar Cita
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection
