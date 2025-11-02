{{-- resources/views/facturas/create.blade.php --}}
<x-app-layout>
  <style>
    :root{
      --bg:#f6f9fc; --card:#fff; --ink:#0f172a; --ink2:#475569; --muted:#94a3b8;
      --ring:#e9eef6; --ring2:#dfe7f2;
      --sky:#0ea5e9; --sky-100:#e0f2fe; --sky-700:#0369a1;
      --emerald:#10b981; --emerald-700:#059669;
      --rose-100:#ffe4e6; --rose-700:#be123c;
      --amber-100:#fef3c7; --amber-700:#b45309;
      --shadow:0 12px 38px rgba(2,6,12,.08);
    }
    body{background:var(--bg)}
    .fx-wrap{max-width:1120px;margin:28px auto;padding:0 16px}
    .fx-card{background:var(--card);border:1px solid var(--ring);border-radius:22px;box-shadow:var(--shadow)}
    .fx-header{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;border-bottom:1px solid var(--ring)}
    .fx-brand{display:flex;align-items:center;gap:12px}
    .fx-ico{width:44px;height:44px;border-radius:14px;background:var(--sky-100);color:var(--sky-700);display:flex;align-items:center;justify-content:center;font-size:22px}
    .fx-title{margin:0;color:var(--ink);font:800 20px/1.1 ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto}
    .fx-sub{margin:0;color:var(--muted);font:600 12px/1 ui-sans-serif}
    .fx-btn{appearance:none;border:1px solid transparent;border-radius:12px;padding:.6rem 1rem;font:800 14px ui-sans-serif;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem}
    .fx-btn-dark{background:#0f172a;color:#fff;border-color:#0f172a}
    .fx-btn-dark:hover{background:#1e293b}
    .fx-btn-sky{background:var(--sky);color:#fff;border-color:var(--sky)}
    .fx-btn-sky:hover{background:#0284c7}
    .fx-btn-rose{background:var(--rose-100);color:var(--rose-700);border-color:#fecdd3}
    .fx-btn-rose:hover{background:#ffe1e5}
    .fx-btn-emerald{background:var(--emerald);color:#fff;border-color:var(--emerald)}
    .fx-btn-emerald:hover{background:var(--emerald-700)}

    .fx-body{display:grid;grid-template-columns:1fr;gap:18px;padding:18px}
    @media(min-width:1024px){ .fx-body{grid-template-columns:2fr 1fr} }

    /* Items card */
    .fx-section-title{margin:0 0 8px;color:var(--ink);font:800 15px ui-sans-serif}
    .fx-section-sub{margin:0 0 12px;color:var(--muted);font:600 12px ui-sans-serif}
    .fx-items{display:flex;flex-direction:column;gap:10px}
    .fx-row{display:grid;grid-template-columns:6fr 2fr 2fr 2fr auto;gap:10px;background:#fff;border:1px solid var(--ring);border-radius:14px;padding:10px}
    @media(max-width:640px){ .fx-row{grid-template-columns:1fr} }

    .fx-label{display:block;color:#64748b;font:800 11px ui-sans-serif;margin-bottom:4px}
    .fx-inp{width:100%;border:1px solid var(--ring2);border-radius:12px;background:#f8fbff;color:var(--ink);padding:10px 12px;font:600 14px ui-sans-serif}
    .fx-inp:focus{outline:0;background:#fff;border-color:#93c5fd;box-shadow:0 0 0 3px rgba(2,132,199,.15)}
    .fx-inp[readonly]{background:#f1f5f9}

    .fx-summary{background:#fff;border:1px solid var(--ring);border-radius:18px;padding:16px;box-shadow:var(--shadow)}
    .fx-line{display:flex;justify-content:space-between;align-items:center;margin:8px 0}
    .fx-k{color:var(--ink2);font:600 14px ui-sans-serif}
    .fx-v{color:var(--ink);font:800 16px ui-sans-serif}

    .fx-add{margin-top:8px}
    .fx-money{font-variant-numeric:tabular-nums}
  </style>

  <div class="fx-wrap">
    <form id="facturaForm" method="POST" action="{{ route('facturas.store') }}">
      @csrf
      <input type="hidden" name="historia_id" value="{{ $historia->id }}">

      <div class="fx-card">
        <div class="fx-header">
          <div class="fx-brand">
            <div class="fx-ico">💳</div>
            <div>
              <h1 class="fx-title">Nueva factura · Historia #{{ $historia->id }}</h1>
              <p class="fx-sub">Facturación de todas las recetas asociadas a esta historia.</p>
            </div>
          </div>
          <a href="{{ url()->previous() }}" class="fx-btn fx-btn-dark">Volver</a>
        </div>

        <div class="fx-body">
          {{-- ========== Columna IZQUIERDA: Ítems ========== --}}
          <div>
            <div class="fx-section-title">Ítems</div>
            <p class="fx-section-sub">Edita precio o cantidad; el subtotal y el total se calculan automáticamente.</p>

            <div id="items" class="fx-items">
              @foreach($items as $i => $it)
                <div class="fx-row item">
                  <div>
                    <label class="fx-label">Descripción</label>
                    <input class="fx-inp" name="items[{{ $i }}][descripcion]" required
                           value="{{ $it['descripcion'] ?? '' }}">
                  </div>
                  <div>
                    <label class="fx-label">Cant.</label>
                    <input type="number" min="1" step="1" class="fx-inp calc"
                           name="items[{{ $i }}][cantidad]"
                           value="{{ $it['cantidad'] ?? 1 }}">
                  </div>
                  <div>
                    <label class="fx-label">Precio</label>
                    <input type="number" min="0" step="0.01" class="fx-inp calc"
                           name="items[{{ $i }}][precio]"
                           value="{{ number_format($it['precio'] ?? 0,2,'.','') }}">
                  </div>
                  <div>
                    <label class="fx-label">Subtotal</label>
                    <input class="fx-inp fx-money subtotal" readonly value="0,00">
                  </div>
                  <div style="display:flex;align-items:end;justify-content:end">
                    <button type="button" class="fx-btn fx-btn-rose remove">✕</button>
                  </div>
                </div>
              @endforeach
            </div>

            <div class="fx-add">
              <button type="button" id="add" class="fx-btn fx-btn-sky">+ Agregar ítem</button>
            </div>
          </div>

          {{-- ========== Columna DERECHA: Resumen ========== --}}
          <div class="fx-summary">
            <div class="fx-section-title" style="margin-bottom:10px">Resumen</div>

            <label class="fx-label">Cliente</label>
            <input class="fx-inp" name="cliente" placeholder="Nombre del cliente">

            <div style="height:8px"></div>
            <label class="fx-label">Mascota</label>
            <input class="fx-inp" readonly value="{{ $historia->cita->mascota->nombre ?? '' }}">

            <div class="fx-line">
              <div class="fx-k">Subtotal</div>
              <div id="lblSubtotal" class="fx-v fx-money">$ 0</div>
            </div>

            <div class="fx-line">
              <div class="fx-k">Impuesto (%)</div>
              <input id="impuesto" type="number" min="0" step="0.01" name="impuesto"
                     value="0" class="fx-inp" style="width:110px;text-align:right">
            </div>

            <div class="fx-line" style="border-top:1px solid var(--ring);padding-top:10px">
              <div class="fx-k">Total</div>
              <div id="lblTotal" class="fx-v fx-money">$ 0</div>
            </div>

            <button type="submit" class="fx-btn fx-btn-emerald" style="width:100%;margin-top:12px">
              Guardar factura
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <script>
    (function(){
      const wrap = document.getElementById('items');
      const add  = document.getElementById('add');
      const iva  = document.getElementById('impuesto');
      let idx    = {{ count($items) }};

      const money = n => new Intl.NumberFormat('es-CO',{style:'currency',currency:'COP',maximumFractionDigits:0}).format(n||0);

      function recalc(){
        let subtotal = 0;
        wrap.querySelectorAll('.item').forEach(row=>{
          const c = parseFloat(row.querySelector('[name*="[cantidad]"]').value||'0');
          const p = parseFloat(row.querySelector('[name*="[precio]"]').value||'0');
          const st = (c>0 && p>0) ? c*p : 0;
          row.querySelector('.subtotal').value = st.toFixed(2).replace('.',',');
          subtotal += st;
        });
        document.getElementById('lblSubtotal').textContent = money(subtotal);
        const imp = parseFloat(iva.value||'0');
        const total = subtotal + subtotal*(imp/100);
        document.getElementById('lblTotal').textContent = money(total);
      }

      wrap.addEventListener('input', e=>{
        if (e.target.classList.contains('calc')) recalc();
      });
      iva.addEventListener('input', recalc);

      wrap.addEventListener('click', e=>{
        const btn = e.target.closest('.remove');
        if(!btn) return;
        btn.closest('.item').remove();
        recalc();
      });

      add.addEventListener('click', ()=>{
        const row = document.createElement('div');
        row.className = 'fx-row item';
        row.innerHTML = `
          <div>
            <label class="fx-label">Descripción</label>
            <input class="fx-inp" name="items[${idx}][descripcion]" required>
          </div>
          <div>
            <label class="fx-label">Cant.</label>
            <input type="number" min="1" step="1" class="fx-inp calc" name="items[${idx}][cantidad]" value="1">
          </div>
          <div>
            <label class="fx-label">Precio</label>
            <input type="number" min="0" step="0.01" class="fx-inp calc" name="items[${idx}][precio]" value="0">
          </div>
          <div>
            <label class="fx-label">Subtotal</label>
            <input class="fx-inp fx-money subtotal" readonly value="0,00">
          </div>
          <div style="display:flex;align-items:end;justify-content:end">
            <button type="button" class="fx-btn fx-btn-rose remove">✕</button>
          </div>`;
        idx++;
        wrap.appendChild(row);
        recalc();
      });

      // primera pasada
      recalc();

      // Validación mínima
      document.getElementById('facturaForm').addEventListener('submit', e=>{
        let ok=false;
        wrap.querySelectorAll('.item').forEach(r=>{
          const c=parseFloat(r.querySelector('[name*="[cantidad]"]').value||'0');
          const p=parseFloat(r.querySelector('[name*="[precio]"]').value||'0');
          if(c>0 && p>0) ok=true;
        });
        if(!ok){ e.preventDefault(); alert('Agrega al menos un ítem con cantidad y precio mayores a 0.'); }
      });
    })();
  </script>
</x-app-layout>
