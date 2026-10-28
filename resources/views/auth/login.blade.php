<x-guest-layout>
    <x-auth-session-status class="auth-status" :status="session('status')" />

    <h2 class="auth-title">Iniciar sesión</h2>
    <p class="auth-subtitle">Bienvenido de nuevo a Lugo Vet 🐾</p>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" placeholder="tucorreo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" type="password" name="password"
                required autocomplete="current-password" placeholder="Tu contraseña" />
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </div>

        <div class="auth-row">
            <label class="auth-remember" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Recordarme') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="auth-btn">{{ __('Iniciar sesión') }}</button>

        <p class="auth-foot">
            ¿No tienes cuenta?
            <a class="auth-link" href="{{ route('register') }}">Regístrate aquí</a>
        </p>
    </form>
</x-guest-layout>