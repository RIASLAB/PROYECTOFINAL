<x-guest-layout>
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <h2 class="auth-title">Crear cuenta</h2>
    <p class="auth-subtitle">Únete a Lugo Vet en un par de pasos 🐾</p>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Nombre -->
        <div class="auth-field">
            <x-input-label for="name" :value="__('Nombre completo')" />
            <x-text-input
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Ingresa tu nombre"
            />
            <x-input-error :messages="$errors->get('name')" class="auth-error" />
        </div>

        <!-- Correo -->
        <div class="auth-field">
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="tucorreo@ejemplo.com"
            />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <!-- Contraseña -->
        <div class="auth-field">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Crea una contraseña"
            />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <!-- Confirmar contraseña -->
        <div class="auth-field">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
            <x-text-input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Repite la contraseña"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
        </div>

        <!-- Autorización de tratamiento de datos (Ley 1581 de 2012, Decreto 1377 de 2013) -->
        <div class="auth-field">
            <label class="auth-remember" for="acepta_datos" style="align-items:flex-start; line-height:1.4">
                <input id="acepta_datos" type="checkbox" name="acepta_datos" value="1" required
                       style="margin-top:3px; flex-shrink:0" {{ old('acepta_datos') ? 'checked' : '' }}>
                <span>Autorizo a Lugo Vet a tratar mis datos personales y los de mis mascotas para gestionar citas,
                    historias clínicas y facturación, conforme a la Ley 1581 de 2012. Puedo conocer, actualizar
                    o pedir la eliminación de mis datos en cualquier momento.</span>
            </label>
            <x-input-error :messages="$errors->get('acepta_datos')" class="auth-error" />
        </div>

        <button type="submit" class="auth-btn">{{ __('Registrarme') }}</button>

        <p class="auth-foot">
            ¿Ya tienes cuenta?
            <a class="auth-link" href="{{ route('login') }}">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>