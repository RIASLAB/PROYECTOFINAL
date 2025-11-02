<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Editar Mascota</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('mascotas.update',$mascota) }}" class="grid gap-3">
            @csrf
            @method('PUT')
            <input type="text" name="nombre" value="{{ $mascota->nombre }}" class="border p-2 rounded" required>
            <input type="text" name="especie" value="{{ $mascota->especie }}" class="border p-2 rounded" required>
            <input type="text" name="raza" value="{{ $mascota->raza }}" class="border p-2 rounded">
            <input type="number" name="edad" value="{{ $mascota->edad }}" class="border p-2 rounded">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Actualizar</button>
        </form>
    </div>
</x-app-layout>
