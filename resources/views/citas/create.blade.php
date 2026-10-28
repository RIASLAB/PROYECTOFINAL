@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .cf {
        --ink:#182a2a; --ink-soft:#5a6f6e;
        --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
        --coral:#ff7a59; --coral-dark:#ef5c39;
        --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.14);
        --shadow-sm:0 4px 14px rgba(13,84,76,.08); --shadow-md:0 18px 44px rgba(13,84,76,.16);
        --radius:20px; --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
        font-family:var(--fb); color:var(--ink);
    }
    .cf * { box-sizing:border-box; }
    .cf-page { min-height:100vh; background:var(--cream); padding:34px 18px 60px; }

    .cf-shell { max-width:760px; margin:0 auto; }

    .cf-head {
        display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
        background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                   linear-gradient(135deg,var(--teal-700),var(--teal-900));
        color:#fff; border-radius:var(--radius); padding:24px 28px; box-shadow:var(--shadow-sm); margin-bottom:22px;
    }
    .cf-head .l { display:flex; align-items:center; gap:16px; }
    .cf-head .ic { width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,.16); display:grid; place-items:center; font-size:1.6rem; }
    .cf-head h1 { font-family:var(--fd); font-weight:600; font-size:1.55rem; letter-spacing:-.02em; margin:0; }
    .cf-head p { margin:3px 0 0; opacity:.9; font-size:.9rem; }
    .cf-back { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.3); color:#fff; padding:10px 16px; border-radius:999px; font-weight:700; font-size:.88rem; text-decoration:none; transition:background .2s; }
    .cf-back:hover { background:rgba(255,255,255,.3); }

    .cf-card { background:var(--white); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
    .cf-card__top { padding:22px 28px 6px; }
    .cf-card__top h2 { font-family:var(--fd); font-weight:600; font-size:1.2rem; color:var(--teal-900); margin:0; }
    .cf-card__top p { margin:4px 0 0; color:var(--ink-soft); font-size:.88rem; }
    .cf-divider { height:1px; background:var(--line); margin:16px 28px 0; }
    .cf-body { padding:24px 28px 28px; }

    .cf-alert { margin-bottom:22px; border-radius:14px; border:1px solid var(--coral); background:#fdece8; color:var(--coral-dark); padding:14px 16px; display:flex; gap:10px; align-items:flex-start; }
    .cf-alert ul { margin:6px 0 0; padding-left:18px; font-size:.88rem; }

    .cf-field { margin-bottom:20px; }
    .cf-field label { display:block; font-weight:600; font-size:.9rem; margin-bottom:7px; }
    .cf-field label .req { color:var(--coral); }
    .cf-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }

    .cf-input, .cf-select, .cf-textarea {
        width:100%; padding:12px 15px; font-family:var(--fb); font-size:.97rem; color:var(--ink);
        background:var(--white); border:1.5px solid var(--line); border-radius:12px; outline:none;
        transition:border-color .2s, box-shadow .2s;
    }
    .cf-input::placeholder, .cf-textarea::placeholder { color:#9bb0ad; }
    .cf-input:focus, .cf-select:focus, .cf-textarea:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }
    .cf-textarea { resize:vertical; min-height:110px; }
    .cf-counter { text-align:right; font-size:.78rem; color:var(--ink-soft); margin-top:5px; }

    .cf-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:8px; flex-wrap:wrap; }
    .cf-btn { appearance:none; border:none; border-radius:12px; padding:13px 24px; font-family:var(--fb); font-weight:700; font-size:.96rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:transform .2s, background .2s, box-shadow .2s; }
    .cf-cancel { background:#eef1f0; color:var(--ink); } .cf-cancel:hover { background:#e2e7e5; }
    .cf-save { background:var(--teal-600); color:#fff; box-shadow:0 10px 24px rgba(13,110,99,.28); } .cf-save:hover { background:var(--teal-700); transform:translateY(-2px); }

    @media (max-width:560px) { .cf-grid { grid-template-columns:1fr; } .cf-head h1 { font-size:1.3rem; } .cf-actions { justify-content:stretch; } .cf-actions .cf-btn { flex:1; justify-content:center; } }
</style>

<div class="cf">
    <div class="cf-page">
        <div class="cf-shell">
            {{-- Encabezado --}}
            <div class="cf-head">
                <div class="l">
                    <div class="ic"><i class="fas fa-calendar-plus"></i></div>
                    <div>
                        <h1>Registrar Nueva Cita</h1>
                        <p>Agrega una cita para una de tus mascotas.</p>
                    </div>
                </div>
                <a href="{{ route('citas.index') }}" class="cf-back"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>

            {{-- Tarjeta --}}
            <div class="cf-card">
                <div class="cf-card__top">
                    <h2>Datos de la cita</h2>
                    <p>Completa los siguientes campos.</p>
                </div>
                <div class="cf-divider"></div>

                <div class="cf-body">
                    @if ($errors->any())
                        <div class="cf-alert">
                            <i class="fas fa-circle-exclamation" style="margin-top:2px"></i>
                            <div>
                                <strong>Revisa estos campos:</strong>
                                <ul>
                                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('citas.store') }}" method="POST">
                        @csrf

                        {{-- Mascota --}}
                        <div class="cf-field">
                            <label for="mascota_id">Mascota <span class="req">*</span></label>
                            <select id="mascota_id" name="mascota_id" class="cf-select" required>
                                <option value="">Seleccione una mascota</option>
                                @foreach($mascotas as $m)
                                    <option value="{{ $m->id }}" @selected(old('mascota_id')==$m->id)>{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo de servicio --}}
                        <div class="cf-field">
                            <label for="servicio">Tipo de servicio <span class="req">*</span></label>
                            <select id="servicio" name="servicio" class="cf-select" required>
                                @foreach(\App\Models\Cita::SERVICIOS as $valor => $etiqueta)
                                    <option value="{{ $valor }}" @selected(old('servicio', 'consulta') == $valor)>{{ $etiqueta }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Motivo --}}
                        <div class="cf-field">
                            <label for="motivo">Motivo <span class="req">*</span></label>
                            <input id="motivo" name="motivo" type="text" value="{{ old('motivo') }}"
                                   class="cf-input" placeholder="Describe el motivo de la cita" required>
                        </div>

                        {{-- Fecha y Hora --}}
                        <div class="cf-field">
                            <div class="cf-grid">
                                <div>
                                    <label for="fecha">Fecha <span class="req">*</span></label>
                                    <input id="fecha" name="fecha" type="date" value="{{ old('fecha') }}" class="cf-input" required>
                                </div>
                                <div>
                                    <label for="hora">Hora <span class="req" style="color:#ff7a59">*</span></label>
                                    <input id="hora" name="hora" type="time" value="{{ old('hora') }}" class="cf-input" required>
                                </div>
                            </div>
                        </div>

                        {{-- Observaciones --}}
                        <div class="cf-field">
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones" name="observaciones" rows="4" maxlength="500"
                                      class="cf-textarea" placeholder="Agrega detalles adicionales si lo deseas">{{ old('observaciones') }}</textarea>
                            <div class="cf-counter"><span id="obsCount">0</span> / 500</div>
                        </div>

                        <input type="hidden" name="estado" value="pendiente">

                        {{-- Botones --}}
                        <div class="cf-actions">
                            <a href="{{ route('citas.index') }}" class="cf-btn cf-cancel">Cancelar</a>
                            <button type="submit" class="cf-btn cf-save"><i class="fas fa-floppy-disk"></i> Guardar Cita</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        // Contador de caracteres
        const obs = document.getElementById('observaciones');
        const counter = document.getElementById('obsCount');
        if (obs && counter) {
            const upd = () => counter.textContent = obs.value.length;
            upd(); obs.addEventListener('input', upd);
        }
        // No permitir fechas pasadas
        const fecha = document.getElementById('fecha');
        if (fecha && !fecha.value) {
            const t = new Date(); t.setMinutes(t.getMinutes() - t.getTimezoneOffset());
            fecha.min = t.toISOString().split('T')[0];
        }
    })();
</script>
@endsection