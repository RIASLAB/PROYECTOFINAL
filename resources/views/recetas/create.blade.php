<x-app-layout>
  <style>
    /* === Botones con estilo consistente y animaciones === */
    .btn-primary{
      display:inline-flex !important;
      align-items:center;
      justify-content:center;
      gap:.5rem;
      padding:.6rem 1.2rem;
      border-radius:1rem;
      background:#0284c7;
      color:#fff !important;
      font-weight:600;
      text-decoration:none !important;
      border:1px solid #0284c7;
      box-shadow:0 4px 12px rgba(2,132,199,.3);
      transition:all .25s ease;
    }
    .btn-primary:hover{
      background:#0369a1;
      transform:translateY(-2px) scale(1.02);
      box-shadow:0 8px 20px rgba(2,132,199,.4);
    }

    .btn-outline{
      display:inline-flex !important;
      align-items:center;
      justify-content:center;
      gap:.5rem;
      padding:.6rem 1.2rem;
      border-radius:1rem;
      border:1px solid #cbd5e1;
      background:#fff;
      color:#334155 !important;
      font-weight:500;
      transition:all .25s ease, transform .2s;
    }
    .btn-outline:hover{
      background:#f8fafc;
      transform:translateY(-1px) scale(1.01);
      box-shadow:0 2px 10px rgba(51,65,85,.1);
    }

    /* === Tarjeta de formulario con animaciones suaves === */
    .card-form{
      background:#fff;
      border-radius:1.5rem;
      box-shadow:0 6px 20px rgba(0,0,0,0.08);
      border:1px solid #f1f5f9;
      overflow:hidden;
      transition:transform .3s ease, box-shadow .3s ease;
    }
    .card-form:hover{
      transform:translateY(-3px);
      box-shadow:0 12px 30px rgba(0,0,0,0.12);
    }

    /* === Inputs y textarea animados === */
    input[type="datetime-local"],
    textarea{
      transition:all .25s ease;
    }
    input[type="datetime-local"]:focus,
    textarea:focus{
      border-color:#0284c7;
      box-shadow:0 0 0 3px rgba(2,132,199,.2);
      background:#fefefe;
    }

    /* === Animaciones de fade y subida === */
    .fade-up{
      opacity:0;
      transform:translateY(10px);
      animation:fadeUp 0.5s forwards;
    }
    @keyframes fadeUp{
      to{opacity:1; transform:translateY(0);}
    }

    /* === Responsividad === */
    @media (max-width:640px){
      .flex{flex-direction:column;}
      .flex > *{width:100%;}
      .flex.justify-end{justify-content:flex-start;}
    }
  </style>

  <div class="max-w-5xl mx-auto p-6 fade-up">
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6 fade-up" style="animation-delay:.1s;">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-2xl bg-amber-100 flex items-center justify-center transition-transform duration-300 hover:scale-110">
          <span class="text-amber-600 text-xl">🩺</span>
        </div>
        <div>
          <h1 class="text-xl md:text-2xl font-semibold text-slate-800 transition-colors duration-300 hover:text-sky-600">
            Nueva receta · Historia #{{ $historia->id }}
          </h1>
          <p class="text-sm text-slate-500">Registra indicaciones, fecha y notas.</p>
        </div>
      </div>
      <a href="{{ route('vet.recetas.index', $historia) }}"
         class="text-sm font-medium text-sky-600 hover:text-sky-800 transition">← Volver</a>
    </div>

    {{-- FORM --}}
    <div class="card-form p-8 fade-up" style="animation-delay:.2s;">
      <form method="POST" action="{{ route('vet.recetas.store', $historia) }}" class="space-y-5">
        @csrf

        {{-- FECHA --}}
        <div>
          <label class="block text-sm font-semibold text-slate-700">Fecha</label>
          <input type="datetime-local" name="fecha"
                 value="{{ old('fecha', now()->format('Y-m-d\TH:i')) }}"
                 class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 focus:border-sky-500 focus:ring-sky-500">
          @error('fecha')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- INDICACIONES --}}
        <div>
          <label class="block text-sm font-semibold text-slate-700">Indicaciones *</label>
          <textarea name="indicaciones" rows="5" required
                    placeholder="Describe el tratamiento, frecuencia, recomendaciones, etc."
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 focus:border-sky-500 focus:ring-sky-500">{{ old('indicaciones') }}</textarea>
          @error('indicaciones')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- NOTAS --}}
        <div>
          <label class="block text-sm font-semibold text-slate-700">Notas (opcional)</label>
          <textarea name="notas" rows="3"
                    placeholder="Observaciones adicionales (control, dieta, reposo, etc.)"
                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 focus:border-sky-500 focus:ring-sky-500">{{ old('notas') }}</textarea>
          @error('notas')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 fade-up" style="animation-delay:.3s;">
          <a href="{{ route('vet.recetas.index', $historia) }}" class="btn-outline">Cancelar</a>
          <button type="submit" class="btn-primary">
            💾 Guardar receta
          </button>
        </div>
      </form>
    </div>
  </div>
</x-app-layout>
