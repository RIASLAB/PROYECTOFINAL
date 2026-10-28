<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        .pf {
            --ink:#182a2a; --ink-soft:#5a6f6e;
            --teal-900:#0a4f48; --teal-700:#0d6e63; --teal-600:#14857a; --teal-500:#1aa394; --teal-50:#e7f5f2;
            --coral:#ff7a59; --coral-dark:#ef5c39;
            --cream:#faf6ef; --white:#fff; --line:rgba(20,60,56,.14);
            --shadow-sm:0 4px 14px rgba(13,84,76,.08);
            --radius:20px; --fd:"Fraunces",Georgia,serif; --fb:"Plus Jakarta Sans",system-ui,sans-serif;
            font-family:var(--fb); color:var(--ink);
        }
        body { background:var(--cream) !important; }
        .pf * { box-sizing:border-box; }
        .pf-page { padding:34px 18px 60px; }
        .pf-shell { max-width:720px; margin:0 auto; }

        .pf-head {
            display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
            background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
                       linear-gradient(135deg,var(--teal-700),var(--teal-900));
            color:#fff; border-radius:var(--radius); padding:24px 28px; box-shadow:var(--shadow-sm); margin-bottom:22px;
        }
        .pf-head .l { display:flex; align-items:center; gap:16px; }
        .pf-head .ic { width:54px; height:54px; border-radius:16px; background:rgba(255,255,255,.16); display:grid; place-items:center; font-size:1.6rem; }
        .pf-head h1 { font-family:var(--fd); font-weight:600; font-size:1.55rem; letter-spacing:-.02em; margin:0; }
        .pf-head p { margin:3px 0 0; opacity:.9; font-size:.9rem; }
        .pf-back { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.16); border:1px solid rgba(255,255,255,.3); color:#fff; padding:10px 16px; border-radius:999px; font-weight:700; font-size:.88rem; text-decoration:none; transition:background .2s; }
        .pf-back:hover { background:rgba(255,255,255,.3); }

        .pf-card { background:var(--white); border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; }
        .pf-card__top { padding:22px 28px 6px; }
        .pf-card__top h2 { font-family:var(--fd); font-weight:600; font-size:1.2rem; color:var(--teal-900); margin:0; }
        .pf-card__top p { margin:4px 0 0; color:var(--ink-soft); font-size:.88rem; }
        .pf-divider { height:1px; background:var(--line); margin:16px 28px 0; }
        .pf-body { padding:24px 28px 28px; }

        .pf-alert { margin-bottom:22px; border-radius:14px; border:1px solid var(--coral); background:#fdece8; color:var(--coral-dark); padding:14px 16px; display:flex; gap:10px; align-items:flex-start; }
        .pf-alert ul { margin:6px 0 0; padding-left:18px; font-size:.88rem; }

        .pf-field { margin-bottom:20px; }
        .pf-field label { display:block; font-weight:600; font-size:.9rem; margin-bottom:7px; }
        .pf-field label .req { color:var(--coral); }
        .pf-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; }

        .pf-input {
            width:100%; padding:12px 15px; font-family:var(--fb); font-size:.97rem; color:var(--ink);
            background:var(--white); border:1.5px solid var(--line); border-radius:12px; outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .pf-input::placeholder { color:#9bb0ad; }
        .pf-input:focus { border-color:var(--teal-500); box-shadow:0 0 0 4px rgba(26,163,148,.16); }

        .pf-actions { display:flex; justify-content:flex-end; gap:12px; padding-top:8px; flex-wrap:wrap; }
        .pf-btn { appearance:none; border:none; border-radius:12px; padding:13px 24px; font-family:var(--fb); font-weight:700; font-size:.96rem; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:transform .2s, background .2s, box-shadow .2s; }
        .pf-cancel { background:#eef1f0; color:var(--ink); } .pf-cancel:hover { background:#e2e7e5; }
        .pf-save { background:var(--teal-600); color:#fff; box-shadow:0 10px 24px rgba(13,110,99,.28); } .pf-save:hover { background:var(--teal-700); transform:translateY(-2px); }

        @media (max-width:560px) { .pf-grid { grid-template-columns:1fr; } .pf-head h1 { font-size:1.3rem; } .pf-actions { justify-content:stretch; } .pf-actions .pf-btn { flex:1; justify-content:center; } }
    </style>

    <div class="pf">
        <div class="pf-page">
            <div class="pf-shell">
                <div class="pf-head">
                    <div class="l">
                        <div class="ic"><i class="fas fa-pen-to-square"></i></div>
                        <div>
                            <h1>Editar Mascota</h1>
                            <p>Actualiza la información de la mascota.</p>
                        </div>
                    </div>
                    <a href="{{ route('mascotas.index') }}" class="pf-back"><i class="fas fa-arrow-left"></i> Volver</a>
                </div>

                <div class="pf-card">
                    <div class="pf-card__top">
                        <h2>Datos de la mascota</h2>
                        <p>Edita los campos necesarios.</p>
                    </div>
                    <div class="pf-divider"></div>

                    <div class="pf-body">
                        @if ($errors->any())
                            <div class="pf-alert">
                                <i class="fas fa-circle-exclamation" style="margin-top:2px"></i>
                                <div>
                                    <strong>Revisa estos campos:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('mascotas.update', $mascota) }}">
                            @csrf
                            @method('PUT')

                            <div class="pf-field">
                                <label for="nombre">Nombre <span class="req">*</span></label>
                                <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $mascota->nombre) }}" class="pf-input" required>
                            </div>

                            <div class="pf-field">
                                <div class="pf-grid">
                                    <div>
                                        <label for="especie">Especie <span class="req">*</span></label>
                                        <input id="especie" type="text" name="especie" value="{{ old('especie', $mascota->especie) }}" class="pf-input" list="especies" required>
                                        <datalist id="especies">
                                            <option value="Perro"><option value="Gato"><option value="Ave"><option value="Conejo"><option value="Hámster">
                                        </datalist>
                                    </div>
                                    <div>
                                        <label for="raza">Raza</label>
                                        <input id="raza" type="text" name="raza" value="{{ old('raza', $mascota->raza) }}" class="pf-input">
                                    </div>
                                </div>
                            </div>

                            <div class="pf-field">
                                <div class="pf-grid">
                                    <div>
                                        <label for="edad">Edad</label>
                                        <input id="edad" type="number" name="edad" value="{{ old('edad', $mascota->edad) }}" class="pf-input" min="0">
                                    </div>
                                    <div>
                                        <label for="dueno">Dueño</label>
                                        <input id="dueno" type="text" name="dueno" value="{{ old('dueno', $mascota->dueno) }}" class="pf-input">
                                    </div>
                                </div>
                            </div>

                            <div class="pf-actions">
                                <a href="{{ route('mascotas.index') }}" class="pf-btn pf-cancel">Cancelar</a>
                                <button type="submit" class="pf-btn pf-save"><i class="fas fa-floppy-disk"></i> Actualizar Mascota</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>