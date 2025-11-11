{{-- resources/views/facturas/create.blade.php --}}
<x-app-layout>
  <style>
    :root {
      --bg: #f4f7fb;
      --card: #ffffff;
      --ink: #0f172a;
      --ink2: #475569;
      --muted: #94a3b8;
      --ring: #e2e8f0;
      --ring2: #d0d7e2;
      --sky: #3b82f6;
      --sky-100: #eff6ff;
      --sky-700: #1d4ed8;
      --emerald: #10b981;
      --emerald-700: #047857;
      --rose: #ef4444;
      --rose-100: #fee2e2;
      --amber-100: #fef3c7;
      --amber-700: #b45309;
      --shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
      --radius: 18px;
      --transition: all 0.25s ease;
    }

    body { background: var(--bg); font-family: 'Inter', system-ui, sans-serif; color: var(--ink2); }

    .fx-wrap {
      max-width: 1150px;
      margin: 36px auto;
      padding: 0 20px;
    }

    .fx-card {
      background: var(--card);
      border: 1px solid var(--ring);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      transition: var(--transition);
    }

    .fx-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 22px 28px;
      background: linear-gradient(135deg, var(--sky-700), var(--sky));
      color: #fff;
    }

    .fx-brand {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .fx-ico {
      width: 50px;
      height: 50px;
      background: #fff;
      color: var(--sky-700);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }

    .fx-title { margin: 0; font-weight: 800; font-size: 20px; line-height: 1.1; }
    .fx-sub { margin: 0; font-size: 13px; opacity: 0.9; }

    .fx-btn {
      border-radius: 10px;
      padding: .6rem 1.1rem;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
      cursor: pointer;
    }

    .fx-btn-dark { background: #0f172a; color: #fff; }
    .fx-btn-dark:hover { background: #1e293b; }

    .fx-btn-sky { background: var(--sky); color: #fff; }
    .fx-btn-sky:hover { background: var(--sky-700); }

    .fx-btn-rose { background: var(--rose-100); color: var(--rose); border: 1px solid var(--rose-100); }
    .fx-btn-rose:hover { background: #fecaca; }

    .fx-btn-emerald { background: var(--emerald); color: #fff; }
    .fx-btn-emerald:hover { background: var(--emerald-700); }

    .fx-body {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 24px;
      padding: 24px;
    }

    @media (max-width: 900px) { .fx-body { grid-template-columns: 1fr; } }

    .fx-section-title { font-weight: 800; color: var(--ink); margin-bottom: 6px; }
    .fx-section-sub { font-size: 13px; margin-bottom: 16px; color: var(--muted); }

    .fx-items { display: flex; flex-direction: column; gap: 12px; }

    .fx-row {
      display: grid;
      grid-template-columns: 6fr 2fr 2fr 2fr auto;
      gap: 10px;
      border: 1px solid var(--ring);
      border-radius: var(--radius);
      background: #fafcff;
      padding: 12px;
      transition: var(--transition);
    }
    .fx-row:hover { background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }

    @media (max-width: 640px) { .fx-row { grid-template-columns: 1fr; } }

    .fx-label { font-weight: 700; font-size: 11px; color: var(--muted); margin-bottom: 4px; text-transform: uppercase; }
    .fx-inp {
      width: 100%;
      border: 1px solid var(--ring2);
      border-radius: 10px;
      padding: 10px 12px;
      background: #f8fafc;
      color: var(--ink);
      font-weight: 600;
      transition: var(--transition);
    }
    .fx-inp:focus { background: #fff; border-color: var(--sky); box-shadow: 0 0 0 3px rgba(59,130,246,.15); outline: none; }
    .fx-inp[readonly] { background: #f1f5f9; }

    .fx-summary {
      background: #fff;
      border: 1px solid var(--ring);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 20px;
    }

    .fx-line { display: flex; justify-content: space-between; align-items: center; margin: 10px 0; }
    .fx-k { color: var(--ink2); font-weight: 600; }
    .fx-v { color: var(--ink); font-weight: 800; font-size: 16px; }

    .fx-money { font-variant-numeric: tabular-nums; }

    .fx-add { margin-top: 8px; }

    /* Animación */
    .fx-row.added { animation: fadeIn .4s ease; }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-5px);} to {opacity: 1; transform: none;} }
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
              <p class="fx-sub">Gestiona y factura las recetas asociadas a esta historia clínica.</p>
            </div>
          </div>
          <a href="{{ url()->previous() }}" class="fx-btn fx-btn-dark">⬅ Volver</a>
        </div>

        <div class="fx-body">
          {{-- Columna izquierda --}}
          <div>
            <h3 class="fx-section-title">Ítems</h3>
            <p class="fx-section-sub">Edita precios o cantidades; el total se calcula automáticamente.</p>

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
                    <input type="number" min="1" class="fx-inp calc" name="items[{{ $i }}][cantidad]"
                      value="{{ $it['cantidad'] ?? 1 }}">
                  </div>
                  <div>
                    <label class="fx-label">Precio</label>
                    <input type="number" min="0" step="0.01" class="fx-inp calc"
                      name="items[{{ $i }}][precio]" value="{{ number_format($it['precio'] ?? 0,2,'.','') }}">
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
              <button type="button" id="add" class="fx-btn fx-btn-sky">＋ Agregar ítem</button>
            </div>
          </div>

          {{-- Columna derecha --}}
          <div class="fx-summary">
            <h3 class="fx-section-title" style="margin-bottom:12px;">Resumen</h3>

            <label class="fx-label">Cliente</label>
            <input class="fx-inp" name="cliente" placeholder="Nombre del cliente">

            <div style="height:10px"></div>

            <label class="fx-label">Mascota</label>
            <input class="fx-inp" readonly value="{{ $historia->cita->mascota->nombre ?? '' }}">

            <div class="fx-line"><div class="fx-k">Subtotal</div><div id="lblSubtotal" class="fx-v fx-money">$ 0</div></div>

            <div class="fx-line">
              <div class="fx-k">Impuesto (%)</div>
              <input id="impuesto" type="number" min="0" step="0.01" name="impuesto"
                value="0" class="fx-inp" style="width:110px;text-align:right">
            </div>

            <div class="fx-line" style="border-top:1px solid var(--ring);padding-top:12px">
              <div class="fx-k">Total</div>
              <div id="lblTotal" class="fx-v fx-money">$ 0</div>
            </div>

            <button type="submit" class="fx-btn fx-btn-emerald" style="width:100%;margin-top:16px;">
              💾 Guardar factura
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
        row.className = 'fx-row item added';
        row.innerHTML = `
          <div><label class="fx-label">Descripción</label>
            <input class="fx-inp" name="items[${idx}][descripcion]" required></div>
          <div><label class="fx-label">Cant.</label>
            <input type="number" min="1" class="fx-inp calc" name="items[${idx}][cantidad]" value="1"></div>
          <div><label class="fx-label">Precio</label>
            <input type="number" min="0" step="0.01" class="fx-inp calc" name="items[${idx}][precio]" value="0"></div>
          <div><label class="fx-label">Subtotal</label>
            <input class="fx-inp fx-money subtotal" readonly value="0,00"></div>
          <div style="display:flex;align-items:end;justify-content:end">
            <button type="button" class="fx-btn fx-btn-rose remove">✕</button></div>`;
        idx++;
        wrap.appendChild(row);
        recalc();
      });

      recalc();
    })();
  </script>
</x-app-layout>
