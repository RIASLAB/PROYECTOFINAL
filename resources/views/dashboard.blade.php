<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Panel de Veterinaria</h2>
    </x-slot>

    <div class="p-6 grid md:grid-cols-2 gap-4">
        <a href="{{ route('mascotas.index') }}" class="p-6 bg-gray-100 rounded shadow hover:bg-gray-200">
            🐾 Gestión de Mascotas
        </a>
        <a href="{{ route('citas.index') }}" class="p-6 bg-gray-100 rounded shadow hover:bg-gray-200">
            📅 Gestión de Citas
        </a>
    </div>
</x-app-layout>
