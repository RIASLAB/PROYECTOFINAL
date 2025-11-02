<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-6">
        <h2 class="text-xl font-bold mb-4">Agregar Usuario</h2>

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium">Nombre</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block font-medium">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
                @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Rol</label>
                    <select name="role" class="w-full border rounded p-2" required>
                        @foreach($roles as $r)
                            <option value="{{ $r }}" @selected(old('role')===$r)>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-medium">Estado</label>
                    <select name="status" class="w-full border rounded p-2" required>
                        <option value="activo" @selected(old('status')==='activo')>Activo</option>
                        <option value="inactivo" @selected(old('status')==='inactivo')>Inactivo</option>
                    </select>
                    @error('status')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block font-medium">Veterinario asignado (opcional)</label>
                <select name="assigned_vet_id" class="w-full border rounded p-2">
                    <option value="">-</option>
                    @foreach($veterinarios as $v)
                        <option value="{{ $v->id }}" @selected(old('assigned_vet_id')==$v->id)>{{ $v->name }}</option>
                    @endforeach
                </select>
                @error('assigned_vet_id')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Contraseña</label>
                    <input type="password" name="password" class="w-full border rounded p-2" required>
                    @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-medium">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
                </div>
            </div>

            <div class="flex gap-2">
                <button class="usr-btn" type="submit">Guardar</button>
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 border rounded">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
