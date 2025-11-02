<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Citas
        </h2>
    </x-slot>

    <div class="py-8 px-6 max-w-6xl mx-auto bg-white rounded shadow">
        {{-- Mensaje de éxito --}}
        @if (session('ok'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('ok') }}
            </div>
        @endif

        {{-- Barra de búsqueda --}}
        <form method="GET" action="{{ route('citas.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Buscar cita o mascota..."
                class="border rounded p-2 flex-grow">
            <button type="submit"
                class="bg-blue-500 text-white px-4 rounded hover:bg-blue-600 transition">Buscar</button>
            <a href="{{ route('citas.create') }}"
                class="bg-green-400 text-black px-4 py-2 rounded hover:bg-green-500 transition font-semibold border border-green-700">
                + Nueva Cita
            </a>
            <a href="{{ route('citas.pdf') }}"
   class="bg-red-400 text-black px-4 py-2 rounded hover:bg-red-500 transition font-semibold border border-red-700">
   📄 Exportar PDF
</a>

        </form>

        {{-- Tabla de citas --}}
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2">#</th>
                    <th class="p-2">Mascota</th>
                    <th class="p-2">Motivo</th>
                    <th class="p-2">Fecha</th>
                    <th class="p-2">Hora</th>
                    <th class="p-2">Observaciones</th>
                    <th class="p-2 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($citas as $cita)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $cita->id }}</td>
                        <td class="p-2">{{ $cita->mascota->nombre }}</td>
                        <td class="p-2">{{ $cita->motivo }}</td>
                        <td class="p-2">{{ $cita->fecha }}</td>
                        <td class="p-2">{{ $cita->hora }}</td>
                        <td class="p-2">{{ $cita->observaciones }}</td>
                        <td class="p-2 text-center">
                            <a href="{{ route('citas.edit', $cita) }}"
                                class="text-blue-600 hover:underline mr-3">Editar</a>
                            <form action="{{ route('citas.destroy', $cita) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('¿Eliminar esta cita?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-3 text-center text-gray-500">No hay citas registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $citas->links() }}
        </div>
    </div>
</x-app-layout>
