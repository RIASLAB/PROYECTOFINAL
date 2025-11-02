@extends('layouts.app')

@section('content')
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap">

  <style>
    :root{
      --hx-bg:#f6f9fc;
      --hx-card:#ffffff;
      --hx-ink:#0f172a;
      --hx-ink-2:#334155;
      --hx-ink-3:#64748b;
      --hx-border:#eef2f7;
      --hx-border-2:#e6edf6;
      --hx-prim:#0ea5e9;
      --hx-prim-600:#0284c7;
      --hx-green:#16a34a;
      --hx-red:#ef4444;
      --hx-blue:#3b82f6;
      --hx-graychip:#e2e8f0;
    }
    *{box-sizing:border-box}
    body{font-family:Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;background:var(--hx-bg)}

    .hx-wrap{max-width:1200px;margin:26px auto;padding:0 18px}
    .hx-card{background:var(--hx-card);border:1px solid var(--hx-border);border-radius:18px;box-shadow:0 15px 50px rgba(2,6,12,.06)}
    .hx-card-lite{background:var(--hx-card);border:1px solid var(--hx-border);border-radius:18px;box-shadow:0 10px 32px rgba(2,6,12,.05)}

    /* ===== Encabezado con acciones a la derecha ===== */
    .hx-head{display:flex;align-items:center;gap:12px;padding:18px 22px}
    .hx-head--with-actions{justify-content:space-between}
    .hx-head-left{display:flex;align-items:center;gap:12px}
    .hx-head .hx-ico{width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#67e8f9,#0ea5e9);display:flex;align-items:center;justify-content:center;color:#fff}
    .hx-title{margin:0;font-weight:800;color:var(--hx-ink);font-size:19px}
    .hx-sub{margin:0;font-size:12px;color:var(--hx-ink-3)}
    .hx-head-actions{display:flex;align-items:center;gap:10px}
    .hx-btn{appearance:none;border-radius:12px;border:1px solid transparent;padding:.6rem 1rem;font-weight:800;font-size:14px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem}
    .hx-btn-prim{background:var(--hx-prim);color:#fff;border-color:var(--hx-prim)}
    .hx-btn-prim:hover{background:var(--hx-prim-600)}
    .hx-btn-ghost{background:#eef2f7;color:var(--hx-ink-2);border-color:#e5e7eb}
    .hx-btn-ghost:hover{background:#e2e8f0}
    .hx-btn-back{background:#0f172a;color:#fff;border-color:#0f172a}
    .hx-btn-back:hover{background:#1e293b;border-color:#1e293b}

    .hx-hline{height:1px;background:linear-gradient(90deg,#f1f5f9 0,#e6eef7 50%,#f1f5f9 100%)}

    .hx-grid-3{display:grid;grid-template-columns:1fr;gap:14px}
    @media (min-width:768px){ .hx-grid-3{grid-template-columns:repeat(3,1fr)} }
    .hx-field label{display:block;font-size:12px;font-weight:700;color:var(--hx-ink-2);margin-bottom:6px}
    .hx-inp{
      width:100%;border:1px solid var(--hx-border-2);background:#f8fbff;color:var(--hx-ink);
      border-radius:12px;padding:10px 12px;font-size:14px;
    }
    .hx-inp:focus{outline:0;background:#fff;border-color:#93c5fd;box-shadow:0 0 0 3px rgba(59,130,246,.18)}
    .hx-actions{display:flex;gap:10px;justify-content:flex-end}

    .hx-chip{font-size:11px;font-weight:800;border-radius:999px;padding:.35rem .55rem;border:1px solid transparent;display:inline-flex;align-items:center;gap:.35rem}
    .hx-chip-gray{background:#f1f5f9;border-color:#e2e8f0;color:#334155}

    .hx-grid{display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:14px}
    @media (min-width:768px){ .hx-grid{grid-template-columns:repeat(2,minmax(0,1fr))} }
    @media (min-width:1024px){ .hx-grid{grid-template-columns:repeat(3,minmax(0,1fr))} }

    .hx-hcard{
      background:#fff;border:1px solid #edf3f9;border-radius:16px;
      box-shadow:0 10px 30px rgba(2,6,12,.06);
      transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .hx-hcard:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(2,6,12,.08);border-color:#e3eef9;}

    /* ===== Modal ===== */
    .hx-modal-bg{
      position:fixed;top:0;left:0;width:100%;height:100%;
      background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;z-index:50;
    }
    .hx-modal{
      background:#fff;border-radius:16px;max-width:600px;width:90%;
      box-shadow:0 20px 60px rgba(0,0,0,.2);animation:pop .25s ease;
    }
    @keyframes pop{0%{transform:scale(.9);opacity:0}100%{transform:scale(1);opacity:1}}
    .hx-modal h2{font-weight:800;color:var(--hx-ink);font-size:18px;margin-bottom:6px}
    .hx-modal p{margin:4px 0;color:var(--hx-ink-3);font-size:14px}
  </style>

  <div class="hx-wrap">
    {{-- ===== Header y Filtros ===== --}}
    <div class="hx-card overflow-hidden">

      {{-- HEADER CON BOTONES A LA DERECHA --}}
      <div class="hx-head hx-head--with-actions">
        <div class="hx-head-left">
          <div class="hx-ico">📋</div>
          <div>
            <h1 class="hx-title">Mis historias clínicas</h1>
            <p class="hx-sub">Filtra o busca tus historias</p>
          </div>
        </div>
        <div class="hx-head-actions">
          <a href="{{ route('vet.dashboard') }}" class="hx-btn hx-btn-back">⬅ Volver al Panel</a>
          @if(Route::has('recetas.mine') || Route::has('vet.recetas'))
            <a href="{{ Route::has('recetas.mine') ? route('recetas.mine') : route('vet.recetas') }}" class="hx-btn hx-btn-ghost">🧾 Recetas</a>
          @endif
        </div>
      </div>

      <div class="hx-hline"></div>

      <form class="p-4 md:p-6" method="GET" action="{{ url()->current() }}">
        <div class="hx-grid-3">
          <div class="hx-field">
            <label>Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="hx-inp">
          </div>
          <div class="hx-field">
            <label>Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="hx-inp">
          </div>
          <div class="hx-field">
            <label>Buscar</label>
            <input type="text" name="q" value="{{ $q }}" placeholder="motivo o mascota" class="hx-inp">
          </div>
        </div>

        <div class="hx-actions mt-4">
          <a href="{{ url()->current() }}" class="hx-btn hx-btn-ghost">Limpiar</a>
          <button class="hx-btn hx-btn-prim" type="submit">Buscar</button>
        </div>
      </form>
    </div>

    {{-- ===== Rejilla de historias ===== --}}
    <div class="hx-card-lite mt-4 overflow-hidden">
      @if($hist->count() === 0)
        <div class="p-6 text-center text-gray-500">No hay historias registradas.</div>
      @else
        <div class="p-4 md:p-6">
          <div class="hx-grid">
            @foreach($hist as $h)
              @php
                $c    = $h->cita;
                $pet  = $c->mascota->nombre ?? '—';
                $vet  = $c->vet->name ?? '—';
                $f    = \Carbon\Carbon::parse($c->fecha)->format('d/m/Y');
                $hr   = $c->hora;
              @endphp

              <div class="hx-hcard p-4 flex flex-col justify-between">
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <div class="font-semibold text-gray-800">{{ $f }} · {{ $hr }}</div>
                    <span class="hx-chip hx-chip-gray">Historia</span>
                  </div>
                  <div class="text-sm">
                    <p><b>Mascota:</b> {{ $pet }}</p>
                    <p><b>Motivo:</b> {{ $h->motivo }}</p>
                    <p><b>Vet:</b> {{ $vet }}</p>
                  </div>
                </div>
                <div class="flex justify-between md:justify-end gap-2 mt-3">
                  @if (Route::has('vet.recetas.index'))
                    <a href="{{ route('vet.recetas.index', $h->id) }}"
                       class="hx-btn hx-btn-prim"
                       title="Ver o crear recetas de esta historia">
                      💊 Recetas
                    </a>
                  @endif
                  <button class="hx-btn hx-btn-ghost ver-btn"
                          data-motivo="{{ $h->motivo }}"
                          data-anamnesis="{{ $h->anamnesis }}"
                          data-diagnostico="{{ $h->diagnostico }}"
                          data-tratamiento="{{ $h->tratamiento }}"
                          data-recomendaciones="{{ $h->recomendaciones }}"
                          data-fecha="{{ $f }}"
                          data-hora="{{ $hr }}"
                          data-pet="{{ $pet }}"
                          data-vet="{{ $vet }}">
                    Ver
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="px-4 md:px-6 py-4 border-t" style="border-color:var(--hx-border)">
          {{ $hist->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- ===== Modal ===== --}}
  <div class="hx-modal-bg" id="detalleModal">
    <div class="hx-modal p-6">
      <div class="flex justify-between items-center mb-4">
        <h2>Detalles de historia</h2>
        <button id="cerrarModal" class="hx-btn hx-btn-ghost">✕</button>
      </div>
      <div id="detalleContenido" class="text-sm leading-relaxed"></div>
    </div>
  </div>

  {{-- ===== Script ===== --}}
  <script>
    const modal = document.getElementById('detalleModal');
    const contenido = document.getElementById('detalleContenido');
    const cerrar = document.getElementById('cerrarModal');

    document.querySelectorAll('.ver-btn').forEach(btn=>{
      btn.addEventListener('click',()=>{
        contenido.innerHTML = `
          <p><b>Fecha:</b> ${btn.dataset.fecha} · ${btn.dataset.hora}</p>
          <p><b>Mascota:</b> ${btn.dataset.pet}</p>
          <p><b>Veterinario:</b> ${btn.dataset.vet}</p>
          <hr class="my-2">
          <p><b>Motivo:</b> ${btn.dataset.motivo || '-'}</p>
          <p><b>Anamnesis:</b> ${btn.dataset.anamnesis || '-'}</p>
          <p><b>Diagnóstico:</b> ${btn.dataset.diagnostico || '-'}</p>
          <p><b>Tratamiento:</b> ${btn.dataset.tratamiento || '-'}</p>
          <p><b>Recomendaciones:</b> ${btn.dataset.recomendaciones || '-'}</p>
        `;
        modal.style.display = 'flex';
      });
    });
    cerrar.addEventListener('click',()=>modal.style.display='none');
    modal.addEventListener('click',e=>{if(e.target===modal) modal.style.display='none'});
  </script>
@endsection
