<x-guest-layout>
    <style>
        body {
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            font-family: 'Inter', sans-serif;
        }

        form {
            background: #fff;
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            max-width: 400px;
            margin: 4rem auto;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        form:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            margin-top: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
        }

        label {
            font-weight: 600;
            color: #334155;
        }

        .block {
            margin-bottom: 1.25rem;
        }

        .btn-primary, x-primary-button {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            background: #0284c7;
            color: #fff !important;
            font-weight: 600;
            border: 1px solid #0284c7;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-primary:hover, x-primary-button:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }

        .underline:hover {
            color: #0284c7 !important;
        }

        .remember_me span {
            font-size: 0.875rem;
            color: #64748b;
        }

        @media (max-width: 500px) {
            form {
                margin: 2rem 1rem;
                padding: 2rem 1.5rem;
            }
        }
    </style>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="Ingresa tu correo electrónico"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="block">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="Ingresa tu contraseña"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block remember_me">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif

            <x-primary-button class="btn-primary">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
