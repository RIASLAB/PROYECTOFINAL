<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registrar Nueva Mascota
        </h2>
    </x-slot>

    <div class="py-8 px-6 max-w-3xl mx-auto bg-white rounded shadow">
        <a href="{{ route('mascotas.index') }}" 
           class="text-blue-600 hover:underline text-sm mb-4 inline-block">
           ← Volver al listado
        </a>

        {{-- Formulario de registro --}}
        <form method="POST" action="{{ route('mascotas.store') }}" class="grid gap-4">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" name="nombre" class="border p-2 w-full rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Especie</label>
                <input type="text" name="especie" class="border p-2 w-full rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Raza</label>
                <input type="text" name="raza" class="border p-2 w-full rounded">
            </div>

            <div>
                <label class="block font-semibold mb-1">Edad</label>
                <input type="number" name="edad" class="border p-2 w-full rounded" min="0">
            </div>

            <div>
                <label class="block font-semibold mb-1">Dueño</label>
                <input type="text" name="dueno" class="border p-2 w-full rounded" required>
            </div>

            <button type="submit" 
                    class="bg-green-300 text-black px-5 py-2 rounded hover:bg-green-400 transition font-semibold shadow border border-green-700">
                Guardar Mascota
            </button>
        </form>
    </div>
</x-app-layout>
