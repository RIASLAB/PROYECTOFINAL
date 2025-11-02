{{-- HISTORIA CLÍNICA · MODAL CORPORATIVO --}}
<div id="hc" aria-hidden="true">
  <div class="hc-overlay"></div>

  <div class="hc-card" role="dialog" aria-modal="true" aria-labelledby="hc-title">
    {{-- Header --}}
    <div class="hc-header">
      <div class="hc-title-wrap">
        <h3 id="hc-title">Historia clínica</h3>
        <p class="hc-subtitle" id="hc-subtitle"><!-- se llena por JS si se desea --></p>
      </div>
      <button type="button" class="hc-icon-btn hc-close" aria-label="Cerrar">✕</button>
    </div>

    {{-- Body (scroll interno) --}}
    @if(Route::has('historias.store'))
      <form method="POST" action="{{ route('historias.store') }}" id="hc-form" class="hc-form">
        @csrf
        <input type="hidden" name="cita_id" id="hc-cita-id">

        <div class="hc-body">
          <div class="hc-field">
            <label class="hc-label">Motivo <span class="hc-req">*</span></label>
            <input name="motivo" required class="hc-input"/>
          </div>

          <div class="hc-field">
            <label class="hc-label">Anamnesis</label>
            <textarea name="anamnesis" rows="3" class="hc-input"></textarea>
          </div>

          <div class="hc-grid">
            <div class="hc-field">
              <label class="hc-label">Diagnóstico</label>
              <textarea name="diagnostico" rows="4" class="hc-input"></textarea>
            </div>
            <div class="hc-field">
              <label class="hc-label">Tratamiento</label>
              <textarea name="tratamiento" rows="4" class="hc-input"></textarea>
            </div>
          </div>

          <div class="hc-field">
            <label class="hc-label">Recomendaciones</label>
            <textarea name="recomendaciones" rows="3" class="hc-input"></textarea>
          </div>

          {{-- Puedes dejar este checkbox si quieres cerrar cita desde aquí
          <label class="hc-check">
            <input type="checkbox" name="completar_cita" value="1">
            Marcar cita como completada
          </label>
          --}}
        </div>

        {{-- Footer --}}
        <div class="hc-footer">
          <button type="button" class="hc-btn hc-btn-ghost hc-close">Cerrar</button>
          <button type="submit" class="hc-btn hc-btn-primary">Guardar historia</button>
        </div>
      </form>

    @else
      <div class="hc-body">
        <p class="hc-muted">La ruta <code>historias.store</code> no está definida.</p>
      </div>
      <div class="hc-footer">
        <button type="button" class="hc-btn hc-btn-ghost hc-close">Cerrar</button>
      </div>
    @endif
  </div>
</div>

{{-- ====== ESTILOS (scopeados) ====== --}}
<style>
  /* Reset local */
  #hc, #hc * { box-sizing: border-box; font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif; }

  /* Overlay con blur */
  #hc .hc-overlay{
    position: fixed; inset: 0; z-index: 99998;
    background: rgba(9, 12, 16, 0.45);
    backdrop-filter: blur(2px);
    opacity: 0; pointer-events: none; transition: opacity .18s ease-in-out;
  }

  /* Tarjeta centrada */
  #hc .hc-card{
    position: fixed; inset: 0; margin: auto; z-index: 99999;
    width: min(840px, 92vw);
    height: auto; max-height: 88vh;
    display: flex; flex-direction: column; overflow: hidden;
    background: #fff; border-radius: 16px; border: 1px solid #eef2f7;
    box-shadow: 0 18px 60px rgba(2, 6, 12, .20);
    transform: translateY(6px); opacity: 0; pointer-events: none;
    transition: transform .18s ease, opacity .18s ease;
  }

  /* Header */
  #hc .hc-header{ display:flex; align-items:center; justify-content:space-between; padding: 16px 18px; background:#fafafa; border-bottom: 1px solid #eef2f7; }
  #hc .hc-title-wrap{ display:flex; flex-direction:column; gap:4px; }
  #hc .hc-header h3{ margin:0; font-weight:800; font-size: 18px; color:#0f172a; letter-spacing:.2px; }
  #hc .hc-subtitle{ margin:0; font-size:12px; color:#64748b; }

  #hc .hc-icon-btn{ width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; border:0; border-radius: 999px; background: transparent; color:#64748b; font-size:16px; cursor:pointer; }
  #hc .hc-icon-btn:hover{ background:#f1f5f9; }

  /* Form & body (scroll interno) */
  #hc .hc-form{ display:flex; flex-direction:column; height: 100%; }
  #hc .hc-body{ padding: 18px; overflow: auto; display:flex; flex-direction:column; gap:14px; }

  #hc .hc-label{ display:block; margin-bottom:6px; font-weight:700; font-size:12px; color:#475569; }
  #hc .hc-req{ color:#ef4444; }

  #hc .hc-input{
    width:100%; border:1px solid #cbd5e1; background:#f8fafc; color:#0f172a;
    border-radius:12px; padding: 10px 12px; font-size:14px;
    transition: border-color .15s, box-shadow .15s, background .15s;
  }
  #hc .hc-input:focus{
    outline:0; background:#fff; border-color:#16a34a;
    box-shadow: 0 0 0 3px rgba(22,163,74,.18);
  }

  #hc .hc-grid{ display:grid; grid-template-columns: 1fr; gap: 14px; }
  @media (min-width: 768px){ #hc .hc-grid{ grid-template-columns: 1fr 1fr; } }

  /* Footer */
  #hc .hc-footer{ display:flex; align-items:center; justify-content:flex-end; gap:10px; padding: 14px 18px; background:#fafafa; border-top:1px solid #eef2f7; }
  #hc .hc-btn{ border:1px solid transparent; border-radius:10px; padding:.65rem 1rem; font-weight:800; font-size:14px; letter-spacing:.2px; cursor:pointer; }
  #hc .hc-btn-primary{ background:#16a34a; color:#fff; border-color:#16a34a; }
  #hc .hc-btn-primary:hover{ background:#15803d; }
  #hc .hc-btn-ghost{ background:#e5e7eb; color:#374151; border-color:#cbd5e1; }
  #hc .hc-btn-ghost:hover{ background:#d1d5db; }

  /* Estados (visible/oculto) */
  #hc[data-open="true"] .hc-overlay{ opacity:1; pointer-events:auto; }
  #hc[data-open="true"] .hc-card{ transform: translateY(0); opacity:1; pointer-events:auto; }
</style>

{{-- ====== LÓGICA DE APERTURA/CIERRE ====== --}}
<script>
(function(){
  const root   = document.getElementById('hc');
  const card   = root?.querySelector('.hc-card');
  const form   = document.getElementById('hc-form');
  const hidden = document.getElementById('hc-cita-id');
  const subtitle = document.getElementById('hc-subtitle');

  // montar en <body> para evitar recortes y z-index
  if (root && root.parentElement !== document.body) document.body.appendChild(root);

  const open = (payload={}) => {
    if (hidden) hidden.value = payload.citaId || '';
    if (subtitle) {
      const parts = [];
      if (payload.mascota) parts.push(`Mascota: ${payload.mascota}`);
      if (payload.fecha)   parts.push(`Cita: ${payload.fecha}${payload.hora ? ' ' + payload.hora : ''}`);
      subtitle.textContent = parts.join(' · ');
    }
    root?.setAttribute('data-open','true');
    document.documentElement.style.overflow = 'hidden';
  };

  const close = () => {
    root?.removeAttribute('data-open');
    document.documentElement.style.overflow = '';
  };

  // abrir desde cualquier .open-historia
  document.querySelectorAll('.open-historia').forEach(btn => {
    btn.addEventListener('click', () => {
      open({
        citaId:  btn.dataset.cita || '',
        mascota: btn.dataset.mascota || '',
        fecha:   btn.dataset.fecha || '',
        hora:    btn.dataset.hora || ''
      });
    });
  });

  // cerrar: botón X, botón Cerrar, click en overlay, tecla ESC
  root?.querySelectorAll('.hc-close').forEach(b => b.addEventListener('click', close));
  root?.querySelector('.hc-overlay')?.addEventListener('click', close);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && root?.getAttribute('data-open') === 'true') close(); });

  // (opcional) manejar envío: aquí no hacemos nada especial
  form?.addEventListener('submit', ()=>{ /* puedes poner loading aquí si quieres */ });
})();
</script>
