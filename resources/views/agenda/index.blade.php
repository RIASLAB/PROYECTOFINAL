@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/agenda-custom.css') }}">

<div class="ag-wrap">

  {{-- Encabezado --}}
  <div class="ag-header">
    <div class="ag-header-left">
      <div class="ag-ico">🗓️</div>
      <div>
        <h1 class="ag-title">Agenda de Citas</h1>
        <p class="ag-sub">Gestiona y filtra las citas por fecha, estado y veterinario.</p>
      </div>
    </div>

    <div class="ag-header-actions">
      <a href="{{ route('vet.dashboard') }}" class="btn btn-back">⬅ Volver al Panel</a>
      @if(Route::has('citas.create'))
        <a href="{{ route('citas.create') }}" class="btn btn-primary">➕ Nueva Cita</a>
      @endif
    </div>
  </div>

  {{-- Filtros --}}
  <form method="GET" action="{{ route('agenda.index') }}" class="ag-filters">
    <div class="ag-filters-grid">
      <div class="ag-field">
        <label>Desde</label>
        <input type="date" name="desde" value="{{ $desde }}" class="ag-input">
      </div>
      <div class="ag-field">
        <label>Hasta</label>
        <input type="date" name="hasta" value="{{ $hasta }}" class="ag-input">
      </div>
      <div class="ag-field">
        <label>Estado</label>
        <select name="estado" class="ag-input">
          <option value="">Todos</option>
          @foreach(['pendiente','confirmada','cancelada','completada'] as $e)
            <option value="{{ $e }}" @selected($estado===$e)>{{ ucfirst($e) }}</option>
          @endforeach
        </select>
      </div>
      <div class="ag-field">
        <label>Veterinario</label>
        <select name="vet_id" class="ag-input">
          <option value="">(Cualquiera)</option>
          @foreach($veterinarios as $v)
            <option value="{{ $v->id }}" @selected($vetId==$v->id)>{{ $v->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="ag-field">
        <label>Buscar</label>
        <div class="ag-search-wrap">
          <span class="ag-search-ico">🔍</span>
          <input type="text" name="q" value="{{ $q }}" placeholder="motivo / mascota" class="ag-input ag-search-inp">
        </div>
      </div>
    </div>

    <div class="ag-filter-actions">
      <a href="{{ route('agenda.index') }}" class="btn btn-ghost">Limpiar</a>
      <button type="submit" class="btn btn-dark">Filtrar</button>
    </div>
  </form>

  {{-- Mensajes --}}
  @if(session('ok'))
    <div class="ag-alert ag-alert-ok">{{ session('ok') }}</div>
  @endif
  @if($errors->any())
    <div class="ag-alert ag-alert-err">
      <ul class="ag-ul">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  @error('vet_id')
    <div class="ag-alert ag-alert-err">{{ $message }}</div>
  @enderror

  {{-- Tabla (desktop) --}}
  <div class="ag-table-card">
    <div class="ag-table-scroll">
      <table class="ag-table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Mascota</th>
            <th>Motivo</th>
            <th>Vet</th>
            <th>Estado</th>
            <th class="ag-th-right">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @forelse($citas as $c)
          @php
            $badge = match($c->estado) {
              'pendiente'  => 'ag-badge ag-badge-yellow',
              'confirmada' => 'ag-badge ag-badge-blue',
              'completada' => 'ag-badge ag-badge-green',
              'cancelada'  => 'ag-badge ag-badge-red',
              default      => 'ag-badge ag-badge-gray',
            };
            $h = $c->historia ?? null;
          @endphp
          <tr>
            <td>{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}</td>
            <td>{{ $c->hora }}</td>
            <td class="ag-strong">{{ $c->mascota->nombre ?? '—' }}</td>
            <td>{{ $c->motivo }}</td>
            <td>{{ $c->vet->name ?? 'Sin asignar' }}</td>
            <td><span class="{{ $badge }}">{{ ucfirst($c->estado) }}</span></td>
            <td>
              <div class="ag-actions-row">
                {{-- Recepcionista/Admin --}}
                @if(in_array(auth()->user()->role,['admin','recepcionista']))
                  @if($c->estado !== 'confirmada')
                    <form method="POST" action="{{ route('agenda.confirmar',$c) }}" class="inline">
                      @csrf @method('PATCH')
                      <button class="btn btn-chip">Confirmar</button>
                    </form>
                  @endif

                  <form method="POST" action="{{ route('agenda.asignarVet',$c) }}" class="ag-assign">
                    @csrf @method('PATCH')
                    <select name="vet_id" class="ag-input ag-input-compact">
                      <option value="">— Vet —</option>
                      @foreach($veterinarios as $v)
                        <option value="{{ $v->id }}" @selected($c->vet_id==$v->id)>{{ $v->name }}</option>
                      @endforeach
                    </select>
                    <button class="btn btn-chip">Asignar</button>
                  </form>
                @endif

                {{-- Veterinario: sólo si es su cita y no está completada --}}
                @if(Route::has('citas.completar')
                  && auth()->user()->role === 'veterinario'
                  && (int)$c->vet_id === (int)auth()->id()
                  && $c->estado !== 'completada')
                  <form method="POST" action="{{ route('citas.completar',$c) }}" class="inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-good">Marcar atendida</button>
                  </form>

                  {{-- Abrir modal historia (crear/editar) --}}
                  <button type="button"
                          class="btn btn-dark open-historia"
                          data-cita="{{ $c->id }}"
                          data-historia-id="{{ $h->id ?? '' }}"
                          data-mascota="{{ $c->mascota->nombre ?? '' }}"
                          data-fecha="{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}"
                          data-hora="{{ $c->hora }}"
                          data-motivo="{{ $h->motivo ?? $c->motivo ?? '' }}"
                          data-anamnesis="{{ $h->anamnesis ?? '' }}"
                          data-diagnostico="{{ $h->diagnostico ?? '' }}"
                          data-tratamiento="{{ $h->tratamiento ?? '' }}"
                          data-recomendaciones="{{ $h->recomendaciones ?? '' }}">
                    Historia
                  </button>
                @endif

              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="ag-empty">No hay citas registradas</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Cards (móvil) --}}
  <div class="ag-cards-mobile">
    @forelse($citas as $c)
      @php
        $badge = match($c->estado) {
          'pendiente'  => 'ag-badge ag-badge-yellow',
          'confirmada' => 'ag-badge ag-badge-blue',
          'completada' => 'ag-badge ag-badge-green',
          'cancelada'  => 'ag-badge ag-badge-red',
          default      => 'ag-badge ag-badge-gray',
        };
        $h = $c->historia ?? null;
      @endphp
      <div class="ag-card">
        <div class="ag-card-top">
          <div class="ag-muted">{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }} · {{ $c->hora }}</div>
          <span class="{{ $badge }}">{{ ucfirst($c->estado) }}</span>
        </div>

        <div class="ag-card-body">
          <div><span class="ag-strong">Mascota:</span> {{ $c->mascota->nombre ?? '—' }}</div>
          <div><span class="ag-strong">Motivo:</span> {{ $c->motivo }}</div>
          <div><span class="ag-strong">Vet:</span> {{ $c->vet->name ?? 'Sin asignar' }}</div>
        </div>

        <div class="ag-card-actions">
          {{-- Recepcionista/Admin en móvil --}}
          @if(in_array(auth()->user()->role,['admin','recepcionista']))
            @if($c->estado !== 'confirmada')
              <form method="POST" action="{{ route('agenda.confirmar',$c) }}" class="inline">
                @csrf @method('PATCH')
                <button class="btn btn-chip">Confirmar</button>
              </form>
            @endif

            <form method="POST" action="{{ route('agenda.asignarVet',$c) }}" class="inline">
              @csrf @method('PATCH')
              <select name="vet_id" class="ag-input ag-input-compact">
                <option value="">— Vet —</option>
                @foreach($veterinarios as $v)
                  <option value="{{ $v->id }}" @selected($c->vet_id==$v->id)>{{ $v->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-chip">Asignar</button>
            </form>
          @endif

          {{-- Veterinario en móvil --}}
          @if(Route::has('citas.completar')
            && auth()->user()->role === 'veterinario'
            && (int)$c->vet_id === (int)auth()->id()
            && $c->estado !== 'completada')
            <form method="POST" action="{{ route('citas.completar',$c) }}" class="inline">
              @csrf @method('PATCH')
              <button class="btn btn-good">Atendida</button>
            </form>

            <button type="button"
                    class="btn btn-dark open-historia"
                    data-cita="{{ $c->id }}"
                    data-historia-id="{{ $h->id ?? '' }}"
                    data-mascota="{{ $c->mascota->nombre ?? '' }}"
                    data-fecha="{{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y') }}"
                    data-hora="{{ $c->hora }}"
                    data-motivo="{{ $h->motivo ?? $c->motivo ?? '' }}"
                    data-anamnesis="{{ $h->anamnesis ?? '' }}"
                    data-diagnostico="{{ $h->diagnostico ?? '' }}"
                    data-tratamiento="{{ $h->tratamiento ?? '' }}"
                    data-recomendaciones="{{ $h->recomendaciones ?? '' }}">
              Historia
            </button>
          @endif
        </div>
      </div>
    @empty
      <div class="ag-empty">No hay citas registradas</div>
    @endforelse
  </div>

  <div class="ag-pager">
    {{ $citas->links() }}
  </div>
</div>

{{-- El modal y JS ya lo tienes debajo en tu archivo (no lo toco) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ====== URLs base para form ======
  const HIST_STORE_URL   = "{{ route('historias.store') }}";     // POST /historias
  const HIST_UPDATE_BASE = "{{ url('/historias') }}/";            // PATCH /historias/{id}

  // ====== CSS del modal ======
  const css = `
  #hc2, #hc2 * { box-sizing:border-box; font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; }
  #hc2 .hc2-overlay{ position:fixed; inset:0; z-index:99998; background:rgba(9,12,16,.45); backdrop-filter:blur(2px); opacity:0; pointer-events:none; transition:opacity .18s; }
  #hc2 .hc2-card{ position:fixed; inset:0; margin:auto; z-index:99999; width:min(900px,92vw); max-height:88vh; display:flex; flex-direction:column; overflow:hidden; background:#fff; border-radius:16px; border:1px solid #eef2f7; box-shadow:0 18px 60px rgba(2,6,12,.2); transform:translateY(8px); opacity:0; pointer-events:none; transition:transform .18s, opacity .18s; }
  #hc2[data-open="true"] .hc2-overlay{ opacity:1; pointer-events:auto; }
  #hc2[data-open="true"] .hc2-card{ transform:translateY(0); opacity:1; pointer-events:auto; }
  #hc2 .hc2-header{ display:flex; align-items:center; justify-content:space-between; padding:16px 18px; background:#fafafa; border-bottom:1px solid #eef2f7; }
  #hc2 .hc2-title-wrap{ display:flex; flex-direction:column; gap:4px; }
  #hc2 .hc2-header h3{ margin:0; font-size:18px; font-weight:800; color:#0f172a; }
  #hc2 .hc2-sub{ margin:0; font-size:12px; color:#64748b; }
  #hc2 .hc2-x{ width:36px; height:36px; border:0; border-radius:999px; background:transparent; color:#64748b; font-size:16px; cursor:pointer; }
  #hc2 .hc2-x:hover{ background:#f1f5f9; }
  #hc2 .hc2-form{ display:flex; flex-direction:column; height:100%; }
  #hc2 .hc2-body{ padding:18px; overflow:auto; display:flex; flex-direction:column; gap:14px; }
  #hc2 .hc2-footer{ position:sticky; bottom:0; z-index:2; display:flex; justify-content:flex-end; gap:10px; padding:14px 18px; background:#fafafa; border-top:1px solid #eef2f7; box-shadow:0 -6px 10px -8px rgba(0,0,0,.15); }
  #hc2 .hc2-label{ display:block; margin-bottom:6px; font-weight:700; font-size:12px; color:#475569; }
  #hc2 .hc2-req{ color:#ef4444; }
  #hc2 .hc2-input{ width:100%; border:1px solid #cbd5e1 !important; background:#f8fafc !important; color:#0f172a !important; border-radius:12px !important; padding:10px 12px !important; font-size:14px !important; transition:border-color .15s, box-shadow .15s, background .15s; }
  #hc2 .hc2-input:focus{ outline:0 !important; background:#fff !important; border-color:#16a34a !important; box-shadow:0 0 0 3px rgba(22,163,74,.18) !important; }
  #hc2 .hc2-grid{ display:grid; grid-template-columns:1fr; gap:14px; }
  @media (min-width:768px){ #hc2 .hc2-grid{ grid-template-columns:1fr 1fr; } }
  #hc2 .hc2-btn{ appearance:none !important; border:1px solid transparent !important; border-radius:10px !important; padding:.65rem 1rem !important; font-weight:800 !important; font-size:14px !important; cursor:pointer !important; }
  #hc2 .hc2-ghost{ background:#e5e7eb !important; color:#374151 !important; border-color:#cbd5e1 !important; }
  #hc2 .hc2-ghost:hover{ background:#d1d5db !important; }
  #hc2 .hc2-primary{ background:#16a34a !important; color:#fff !important; border-color:#16a34a !important; }
  #hc2 .hc2-primary:hover{ background:#15803d !important; }
  #hc2 .hc2-primary:disabled{ background:#a7f3d0 !important; color:#065f46 !important; border-color:#a7f3d0 !important; opacity:1 !important; }
  /* Scroll interno */
  #hc2 .hc2-card{ display:flex; flex-direction:column; max-height:92vh; }
  #hc2 .hc2-body{ flex:1 1 auto; min-height:0; overflow:auto; -webkit-overflow-scrolling: touch; }
  #hc2 .hc2-footer{ position:sticky; bottom:0; z-index:2; }
  `;

  // ====== HTML del modal ======
  const html = `
  <div id="hc2" data-open="false" aria-hidden="true">
    <div class="hc2-overlay"></div>
    <div class="hc2-card" role="dialog" aria-modal="true" aria-labelledby="hc2-title">
      <div class="hc2-header">
        <div class="hc2-title-wrap">
          <h3 id="hc2-title">Historia clínica</h3>
          <p class="hc2-sub" id="hc2-sub"></p>
        </div>
        <button type="button" class="hc2-x" aria-label="Cerrar">✕</button>
      </div>
      <form id="hc2-form" method="POST" action="{{ route('historias.save') }}" class="hc2-form">
        @csrf
        <input type="hidden" name="cita_id" id="hc2-cita-id">
        <input type="hidden" name="hc2-historia-id" id="hc2-historia-id">
        <div class="hc2-body">
          <div class="hc2-field">
            <label class="hc2-label">Motivo <span class="hc2-req">*</span></label>
            <input name="motivo" class="hc2-input" required>
          </div>
          <div class="hc2-field">
            <label class="hc2-label">Anamnesis</label>
            <textarea name="anamnesis" class="hc2-input"></textarea>
          </div>
          <div class="hc2-grid">
            <div class="hc2-field">
              <label class="hc2-label">Diagnóstico</label>
              <textarea name="diagnostico" class="hc2-input"></textarea>
            </div>
            <div class="hc2-field">
              <label class="hc2-label">Tratamiento</label>
              <textarea name="tratamiento" class="hc2-input"></textarea>
            </div>
          </div>
          <div class="hc2-field">
            <label class="hc2-label">Recomendaciones</label>
            <textarea name="recomendaciones" class="hc2-input"></textarea>
          </div>
        </div>
        <div class="hc2-footer">
          <button type="button" class="hc2-btn hc2-ghost hc2-close">Cerrar</button>
          <button type="submit" id="hc2-submit" class="hc2-btn hc2-primary">Guardar historia</button>
        </div>
      </form>
    </div>
  </div>`;

  // Inyecta CSS + HTML una sola vez
  if (!document.getElementById('hc2-style-v2')) {
    const s = document.createElement('style'); s.id = 'hc2-style-v2'; s.textContent = css; document.head.appendChild(s);
  }
  if (!document.getElementById('hc2')) {
    const w = document.createElement('div'); w.innerHTML = html; document.body.appendChild(w.firstElementChild);
  }

  // Refs
  // Refs (debe ir ANTES de openModal)
const root   = document.getElementById('hc2');
const form   = document.getElementById('hc2-form');
const inputC = document.getElementById('hc2-cita-id');
const inputH = document.getElementById('hc2-historia-id'); // <-- ESTA línea
const sub    = document.getElementById('hc2-sub');
const title  = document.getElementById('hc2-title');
const submit = document.getElementById('hc2-submit');


  // Helpers
  function setValue(name, val){ const el = form.querySelector(`[name="${name}"]`); if(el){ el.value = val || ''; } }
  function setMethod(method){
    let m = form.querySelector('input[name="_method"]');
    if(method){
      if(!m){ m = document.createElement('input'); m.type='hidden'; m.name='_method'; form.appendChild(m); }
      m.value = method; // 'PATCH' para editar
    } else {
      if(m) m.remove(); // POST puro para crear
    }
  }
  function configureFormFor(payload){
    if(payload.historiaId){
      form.action = HIST_UPDATE_BASE + payload.historiaId; // editar
      setMethod('PATCH');
      title.textContent = 'Editar historia clínica';
      submit.textContent = 'Actualizar historia';
    } else {
      form.action = HIST_STORE_URL; // crear
      setMethod(null);
      title.textContent = 'Nueva historia clínica';
      submit.textContent = 'Guardar historia';
    }
  }

  function openModal(payload = {}) {
  // Fuerza POST /historias/save y sin _method
  form.method = 'POST';
  form.action = "{{ route('historias.save') }}";
  form.querySelectorAll('input[name="_method"]').forEach(el => el.remove());

  // Cita / historia
  inputC.value = payload.citaId || '';

  if (payload.historiaId) {
    inputH.value = payload.historiaId;
    inputH.setAttribute('name','historia_id'); // EDITAR: se envía
    title.textContent = 'Editar historia clínica';
    submit.textContent = 'Actualizar';
  } else {
    inputH.value = '';
    inputH.removeAttribute('name');            // CREAR: no se envía
    title.textContent = 'Nueva historia clínica';
    submit.textContent = 'Guardar';
  }

  // Prefill
  form.querySelector('[name="motivo"]').value          = payload.motivo || '';
  form.querySelector('[name="anamnesis"]').value       = payload.anamnesis || '';
  form.querySelector('[name="diagnostico"]').value     = payload.diagnostico || '';
  form.querySelector('[name="tratamiento"]').value     = payload.tratamiento || '';
  form.querySelector('[name="recomendaciones"]').value = payload.recomendaciones || '';

  root.setAttribute('data-open','true');
  document.documentElement.style.overflow = 'hidden';
}


  function closeModal() {
    root.setAttribute('data-open','false');
    document.documentElement.style.overflow = '';
  }

  // Delegación: abre modal con datos del botón clicado
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.open-historia');
    if (!btn) return;
    e.preventDefault();
    openModal({
      citaId:          btn.dataset.cita || '',
      historiaId:      btn.dataset.historiaId || '',
      mascota:         btn.dataset.mascota || '',
      fecha:           btn.dataset.fecha || '',
      hora:            btn.dataset.hora || '',
      motivo:          btn.dataset.motivo || '',
      anamnesis:       btn.dataset.anamnesis || '',
      diagnostico:     btn.dataset.diagnostico || '',
      tratamiento:     btn.dataset.tratamiento || '',
      recomendaciones: btn.dataset.recomendaciones || ''
    });
  });

  // Cerrar (overlay, X, botón Cerrar, ESC)
  root.querySelector('.hc2-overlay').addEventListener('click', closeModal);
  root.querySelector('.hc2-x').addEventListener('click', closeModal);
  root.querySelector('.hc2-close').addEventListener('click', closeModal);
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && root.getAttribute('data-open')==='true'){ closeModal(); } });

  // Anti doble envío
    // Anti doble envío y control del campo historia_id
  form.addEventListener('submit', function(){
    // si NO hay historiaId, quitar el name para que no se mande
    if (!inputH.value) inputH.removeAttribute('name');

    // prevenir doble clic
    if (submit){ submit.disabled = true; submit.textContent = 'Guardando…'; }
  });
});

</script>
@endsection
