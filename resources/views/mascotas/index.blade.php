<x-app-layout>
    {{-- BANDA SUPERIOR (mismo estilo que Citas) --}}
    <div role="banner" class="!bg-blue-700 !text-white shadow-lg relative z-20">
        <div class="max-w-5xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 !text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.5 12a7.5 7.5 0 1015 0 7.5 7.5 0 10-15 0zm9-3l-3 6-2-2" />
                </svg>
                <div>
                    <h1 class="text-2xl font-semibold leading-tight !text-white">Lista de Mascotas</h1>
                    <p class="text-sm !text-white/90">Administra tus mascotas y sus dueños</p>
                </div>
            </div>
            <a href="{{ route('mascotas.create') }}"
               class="inline-flex items-center gap-2 rounded-full bg-white/15 hover:bg-white/25 px-4 py-2 text-sm border border-white/40 transition-colors !text-white">
                + Nueva Mascota
            </a>
        </div>
    </div>

    {{-- TARJETA CENTRADA --}}
    <div class="max-w-5xl mx-auto px-4 pb-16 -mt-8 relative z-30">
        <div class="bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 overflow-hidden">

            {{-- Contenido --}}
            <div class="p-6 md:p-8">
                {{-- Mensaje de éxito --}}
                @if (session('ok'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                        {{ session('ok') }}
                    </div>
                @endif

                {{-- Búsqueda --}}
                <form method="GET" action="{{ route('mascotas.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3 items-center">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        placeholder="Buscar por nombre, especie, raza o dueño…"
                        class="border rounded-lg p-2 w-full sm:flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                    />
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Buscar
                        </button>
                        <a href="{{ route('mascotas.create') }}"
                           class="bg-emerald-500 text-white px-4 py-2 rounded-lg hover:bg-emerald-600 transition font-medium">
                            + Nueva
                        </a>
                    </div>
                </form>

                {{-- Tabla responsive centrada --}}
                {{-- Tabla responsive centrada y proporcionada --}}
<div class="overflow-x-auto">
  <table class="w-full table-fixed border-collapse text-sm text-gray-700">
    {{-- define los anchos de columnas (suman ~100%) --}}
    <colgroup>
      <col style="width: 6%">
      <col style="width: 18%">
      <col style="width: 16%">
      <col style="width: 16%">
      <col style="width: 10%">
      <col style="width: 18%">
      <col style="width: 16%">
    </colgroup>

    <thead>
      <tr class="bg-gradient-to-r from-blue-100 to-indigo-100 text-gray-700 border-b border-gray-300">
        <th class="px-4 py-2 text-left font-semibold">#</th>
        <th class="px-4 py-2 text-left font-semibold">Nombre</th>
        <th class="px-4 py-2 text-left font-semibold">Especie</th>
        <th class="px-4 py-2 text-left font-semibold">Raza</th>
        <th class="px-4 py-2 text-left font-semibold">Edad</th>
        <th class="px-4 py-2 text-left font-semibold">Dueño</th>
        <th class="px-4 py-2 text-center font-semibold">Acciones</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($mascotas as $m)
        @php
          $ownerName = (is_numeric($m->dueno) && $m->owner) ? $m->owner->name : ($m->dueno ?? '—');
        @endphp
        <tr class="border-b last:border-0 hover:bg-gray-50 transition">
          <td class="px-4 py-2 text-gray-500">
            {{ $loop->iteration + ($mascotas->currentPage()-1)*$mascotas->perPage() }}
          </td>
          <td class="px-4 py-2 truncate">{{ $m->nombre }}</td>
          <td class="px-4 py-2 truncate">{{ $m->especie }}</td>
          <td class="px-4 py-2 truncate">{{ $m->raza ?? '—' }}</td>
          <td class="px-4 py-2">{{ $m->edad ?? '—' }}</td>
          <td class="px-4 py-2 truncate">{{ $ownerName }}</td>
          <td class="px-4 py-2 text-center">
            <div class="inline-flex items-center gap-3">
              <a href="{{ route('mascotas.edit', $m) }}"
                 class="text-blue-600 hover:underline font-medium">Editar</a>
              <form action="{{ route('mascotas.destroy', $m) }}" method="POST"
                    onsubmit="return confirm('¿Eliminar esta mascota?')" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline font-medium">Eliminar</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="px-4 py-6 text-center text-gray-500">
            No hay mascotas registradas
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>


                {{-- Paginación --}}
                <div class="mt-6">
                    {{ $mascotas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
