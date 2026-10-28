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
    <style>
        .pf-shell.wide { max-width:920px; }
        .pf-dl { display:grid; grid-template-columns:1fr 1fr; gap:14px 26px; }
        .pf-dl div { border-bottom:1px dashed var(--line); padding-bottom:10px; }
        .pf-dl dt { font-size:.78rem; text-transform:uppercase; letter-spacing:.06em; color:var(--ink-soft); font-weight:700; }
        .pf-dl dd { margin:4px 0 0; font-size:.97rem; }
        .pf-dl .full { grid-column:1 / -1; }
        .pf-sub { font-family:var(--fd); font-weight:600; color:var(--teal-900); font-size:1.1rem; margin:0 0 12px; }
        .pf-table { width:100%; border-collapse:collapse; font-size:.9rem; }
        .pf-table th { text-align:left; font-size:.76rem; text-transform:uppercase; letter-spacing:.05em; color:var(--ink-soft); padding:8px 10px; background:var(--teal-50); }
        .pf-table td { padding:9px 10px; border-bottom:1px solid var(--line); }
        .pf-empty { color:var(--ink-soft); font-size:.92rem; margin:0; }
        .pf-meds { display:grid; grid-template-columns:1.6fr 1fr 1.2fr 1fr 1fr; gap:8px; margin-bottom:8px; }
        .pf-meds .pf-input { padding:10px 12px; font-size:.9rem; }
        .pf-hint { color:var(--ink-soft); font-size:.82rem; margin:-2px 0 10px; }
        .pf-err { color:var(--coral-dark); font-size:.85rem; margin-top:6px; }
        @media (max-width:760px){ .pf-dl, .pf-meds { grid-template-columns:1fr; } }
    </style>

    @php
        $cita = $historia->cita;
        $fecha = optional($cita)->fecha ? \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') : '—';
    @endphp
    <div class="pf">
        <div class="pf-page">
            <div class="pf-shell wide">
                <div class="pf-head">
                    <div class="l">
                        <div class="ic"><i class="fas fa-file-medical"></i></div>
                        <div>
                            <h1>Historia clínica #{{ $historia->id }}</h1>
                            <p>{{ $cita->mascota->nombre ?? '—' }} · {{ $fecha }} · {{ $cita->hora ?? '' }}</p>
                        </div>
                    </div>
                    <a href="{{ url()->previous() }}" class="pf-back"><i class="fas fa-arrow-left"></i> Volver</a>
                </div>

                <div class="pf-card">
                    <div class="pf-card__top">
                        <h2>Datos de la atención</h2>
                        <p>Información registrada por el veterinario.</p>
                    </div>
                    <div class="pf-divider"></div>
                    <div class="pf-body">
                        <dl class="pf-dl">
                            <div><dt>Mascota</dt><dd>{{ $cita->mascota->nombre ?? '—' }}</dd></div>
                            <div><dt>Servicio</dt><dd>{{ $cita->servicio_nombre ?? '—' }}</dd></div>
                            <div><dt>Veterinario</dt><dd>{{ $cita->vet->name ?? '—' }}</dd></div>
                            <div><dt>Fecha y hora</dt><dd>{{ $fecha }} {{ $cita->hora ?? '' }}</dd></div>
                            <div class="full"><dt>Motivo</dt><dd>{{ $historia->motivo }}</dd></div>
                            <div class="full"><dt>Anamnesis</dt><dd>{{ $historia->anamnesis ?? '—' }}</dd></div>
                            <div class="full"><dt>Diagnóstico</dt><dd>{{ $historia->diagnostico ?? '—' }}</dd></div>
                            <div class="full"><dt>Tratamiento</dt><dd>{{ $historia->tratamiento ?? '—' }}</dd></div>
                            <div class="full"><dt>Recomendaciones</dt><dd>{{ $historia->recomendaciones ?? '—' }}</dd></div>
                        </dl>
                    </div>
                </div>

                <div class="pf-card" style="margin-top:20px">
                    <div class="pf-body">
                        <h3 class="pf-sub">Recetas</h3>
                        @forelse($historia->recetas as $r)
                            <p style="margin:0 0 8px"><strong>{{ optional($r->fecha)->format('d/m/Y') }}:</strong> {{ $r->indicaciones }}</p>
                            @if($r->items->count())
                                <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;margin-bottom:14px">
                                <table class="pf-table" style="min-width:560px">
                                    <thead><tr><th>Medicamento</th><th>Dosis</th><th>Frecuencia</th><th>Duración</th><th>Vía</th></tr></thead>
                                    <tbody>
                                    @foreach($r->items as $it)
                                        <tr><td>{{ $it->medicamento }}</td><td>{{ $it->dosis }}</td><td>{{ $it->frecuencia }}</td><td>{{ $it->duracion }}</td><td>{{ $it->via }}</td></tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                            @endif
                        @empty
                            <p class="pf-empty">Esta historia no tiene recetas.</p>
                        @endforelse

                        <div class="pf-actions">
                            <a href="{{ url()->previous() }}" class="pf-btn pf-cancel">Volver</a>
                            <a href="{{ route('historias.pdf', $historia) }}" class="pf-btn pf-save"><i class="fas fa-file-pdf"></i> Descargar PDF</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
