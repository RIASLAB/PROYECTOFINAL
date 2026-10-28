@extends('layouts.app')

@section('content')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
  @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

  :root{
    --hx-bg:#faf6ef;
    --hx-card:#ffffff;
    --hx-ink:#182a2a;
    --hx-ink-2:#44595a;
    --hx-ink-3:#7a8d8b;
    --hx-border:rgba(20,60,56,.12);
    --hx-border-2:rgba(20,60,56,.14);
    --hx-prim:#14857a;
    --hx-prim-600:#0d6e63;
    --hx-teal-900:#0a4f48;
    --hx-teal-50:#e7f5f2;
    --hx-coral:#ff7a59;
    --hx-amber:#f4b740;
    --fd:"Fraunces",Georgia,serif;
    --fb:"Plus Jakarta Sans",system-ui,sans-serif;
  }

  *{box-sizing:border-box;margin:0;padding:0}
  body{
    font-family:var(--fb);
    background:var(--hx-bg);
    animation:fadeInBody 0.5s ease;
  }

  .hx-wrap{max-width:1200px;margin:26px auto;padding:0 18px 48px; animation:fadeInUp 0.5s ease;}
  .hx-card, .hx-card-lite{
    background:var(--hx-card);
    border:1px solid var(--hx-border);
    border-radius:20px;
    box-shadow:0 4px 14px rgba(13,84,76,.08);
    transition:transform .3s ease, box-shadow .3s ease;
  }
  .hx-card-lite{box-shadow:0 4px 14px rgba(13,84,76,.06);}

  /* Header con banda de marca */
  .hx-head{
    display:flex;align-items:center;gap:12px;padding:24px 28px;
    background:radial-gradient(600px 240px at 12% 0%, rgba(255,122,89,.28), transparent 60%),
               linear-gradient(135deg,var(--hx-prim-600),var(--hx-teal-900));
    color:#fff;border-radius:20px 20px 0 0;
  }
  .hx-head--with-actions{justify-content:space-between;}
  .hx-head-left{display:flex;align-items:center;gap:16px;}
  .hx-head .hx-ico{
    width:54px;height:54px;border-radius:16px;
    background:rgba(255,255,255,.16);
    display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;
  }
  .hx-title{margin:0;font-family:var(--fd);font-weight:600;color:#fff;font-size:1.6rem;letter-spacing:-.02em;}
  .hx-sub{margin:0;font-size:.9rem;color:rgba(255,255,255,.9);}

  .hx-head-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
  .hx-btn{
    appearance:none;border-radius:999px;border:1px solid transparent;padding:.6rem 1.1rem;font-family:var(--fb);font-weight:700;
    font-size:14px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.5rem;
    transition:all .22s ease;
  }
  .hx-btn-prim{background:var(--hx-prim);color:#fff;border-color:var(--hx-prim);}
  .hx-btn-prim:hover{background:var(--hx-prim-600); transform:translateY(-2px);}
  .hx-btn-ghost{background:rgba(255,255,255,.16);color:#fff;border-color:rgba(255,255,255,.3);}
  .hx-btn-ghost:hover{background:rgba(255,255,255,.28); transform:translateY(-2px);}
  .hx-btn-back{background:rgba(255,255,255,.16);color:#fff;border-color:rgba(255,255,255,.3);}
  .hx-btn-back:hover{background:rgba(255,255,255,.28);transform:translateY(-2px);}

  .hx-hline{height:1px;background:var(--hx-border);margin:0;}

  .hx-grid-3{display:grid;grid-template-columns:1fr;gap:14px;}
  @media (min-width:768px){ .hx-grid-3{grid-template-columns:repeat(3,1fr);} }

  .hx-field label{display:block;font-size:12px;font-weight:700;color:var(--hx-ink-2);margin-bottom:6px;}
  .hx-inp{
    width:100%;border:1.5px solid var(--hx-border-2);background:#fff;color:var(--hx-ink);
    border-radius:12px;padding:10px 12px;font-family:var(--fb);font-size:14px;
    transition:border-color .25s ease, box-shadow .25s ease;
  }
  .hx-inp:focus{
    outline:0;background:#fff;border-color:var(--hx-prim);box-shadow:0 0 0 4px rgba(26,163,148,.16);
  }

  .hx-actions{display:flex;gap:10px;justify-content:flex-end;}

  .hx-chip{font-size:11px;font-weight:800;border-radius:999px;padding:.35rem .6rem;border:1px solid transparent;display:inline-flex;align-items:center;gap:.35rem;}
  .hx-chip-gray{background:var(--hx-teal-50);border-color:transparent;color:var(--hx-prim-600);}

  .hx-grid{display:grid;grid-template-columns:1fr;gap:14px;}
  @media (min-width:768px){ .hx-grid{grid-template-columns:repeat(2,1fr);} }
  @media (min-width:1024px){ .hx-grid{grid-template-columns:repeat(3,1fr);} }

  .hx-hcard{
    position:relative;overflow:hidden;
    background:#fff;border:1px solid var(--hx-border);border-radius:18px;
    box-shadow:0 4px 14px rgba(13,84,76,.08);
    transition:transform .25s ease, box-shadow .25s ease;
  }
  .hx-hcard::before{content:"";position:absolute;top:0;left:0;width:100%;height:4px;background:linear-gradient(90deg,var(--hx-prim),var(--hx-coral));transform:scaleX(0);transform-origin:left;transition:transform .3s ease;}
  .hx-hcard:hover{
    transform:translateY(-6px);
    box-shadow:0 14px 34px rgba(13,84,76,.14);
  }
  .hx-hcard:hover::before{transform:scaleX(1);}
  .hx-hcard p{margin:2px 0;font-size:13px;color:var(--hx-ink-2);}
  .hx-hcard b{color:var(--hx-ink);}

  .hx-modal-bg{
    position:fixed;top:0;left:0;width:100%;height:100%;
    background:rgba(8,40,37,.5);display:none;align-items:center;justify-content:center;z-index:50;
    animation:fadeInBg .25s ease;
  }
  .hx-modal{
    background:#fff;border-radius:18px;max-width:600px;width:90%;
    box-shadow:0 20px 60px rgba(2,6,12,.25);animation:pop .25s ease;
  }
  .hx-modal h2{font-family:var(--fd);font-weight:600;color:var(--hx-teal-900);font-size:1.3rem;margin-bottom:6px;}
  .hx-modal p{margin:4px 0;color:var(--hx-ink-2);font-size:14px;}

  @keyframes fadeInBody{0%{opacity:0}100%{opacity:1}}
  @keyframes fadeInUp{0%{opacity:0;transform:translateY(10px)}100%{opacity:1;transform:translateY(0)}}
  @keyframes fadeInBg{0%{opacity:0}100%{opacity:1}}
  @keyframes pop{0%{transform:scale(.9);opacity:0}100%{transform:scale(1);opacity:1}}

  @media (max-width:500px){
    .hx-head{flex-direction:column;align-items:flex-start;gap:12px;}
    .hx-head-actions{width:100%;justify-content:flex-start;gap:6px;flex-wrap:wrap;}
    .hx-grid-3{grid-template-columns:1fr;}
    .hx-grid{grid-template-columns:1fr;}
  }
  </style>

  <div class="hx-wrap">
    {{-- Header con filtros --}}
    <div class="hx-card overflow-hidden">
      <div class="hx-head hx-head--with-actions">
        <div class="hx-head-left">
          <div class="hx-ico">📋</div>
          <div>
            <h1 class="hx-title">{{ $titulo }}</h1>
            <p class="hx-sub">Filtra o busca las historias</p>
          </div>
        </div>
        <div class="hx-head-actions">
          @php
            $volver = $scope === 'client' ? 'client.dashboard' : 'vet.dashboard';
          @endphp
          <a href="{{ $scope === 'mascota' ? route('agenda.index') : route($volver) }}" class="hx-btn hx-btn-back">⬅ Volver</a>
          @if($scope !== 'client')
            <a href="{{ route('vet.recetas.mine') }}" class="hx-btn hx-btn-ghost">🧾 Recetas</a>
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
          <a href="{{ url()->current() }}" class="hx-btn hx-btn-ghost" style="background:#eef1f0;color:#44595a;border-color:rgba(20,60,56,.14)">Limpiar</a>
          <button class="hx-btn hx-btn-prim" type="submit">Buscar</button>
        </div>
      </form>
    </div>

    {{-- Rejilla de historias --}}
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
                  @if ($scope === 'vet')
                    <a href="{{ route('vet.recetas.index', $h->id) }}" class="hx-btn hx-btn-prim" title="Ver o crear recetas de esta historia">💊 Recetas</a>
                    @if($h->pendiente_cobro)
                      <span class="hx-chip hx-chip-gray" title="La recepción ya puede cobrarla">En caja</span>
                    @else
                      <form method="POST" action="{{ route('historias.enviarCaja', $h->id) }}">
                        @csrf
                        <button type="submit" class="hx-btn hx-btn-ghost" title="Enviar esta historia a caja para que la recepción la cobre">💰 Enviar a caja</button>
                      </form>
                    @endif
                  @endif
                  <a href="{{ route('historias.pdf', $h->id) }}" class="hx-btn hx-btn-ghost" title="Descargar en PDF">PDF</a>
                  <button class="hx-btn hx-btn-ghost ver-btn"
                          style="background:#eef1f0;color:#44595a;border-color:rgba(20,60,56,.14)"
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

  {{-- Modal --}}
  <div class="hx-modal-bg" id="detalleModal">
    <div class="hx-modal p-6">
      <div class="flex justify-between items-center mb-4">
        <h2>Detalles de historia</h2>
        <button id="cerrarModal" class="hx-btn hx-btn-ghost" style="background:#eef1f0;color:#44595a;border-color:rgba(20,60,56,.14)">✕</button>
      </div>
      <div id="detalleContenido" class="text-sm leading-relaxed"></div>
    </div>
  </div>

  {{-- Script --}}
  <script>
    const modal = document.getElementById('detalleModal');
    const contenido = document.getElementById('detalleContenido');
    const cerrar = document.getElementById('cerrarModal');

    document.querySelectorAll('.ver-btn').forEach(btn=>{
      btn.addEventListener('click',()=>{
        // Se arma con textContent para evitar inyección de HTML (XSS) desde los campos de la historia
        const filas = [
          ['Fecha', `${btn.dataset.fecha} · ${btn.dataset.hora}`],
          ['Mascota', btn.dataset.pet],
          ['Veterinario', btn.dataset.vet],
          ['Motivo', btn.dataset.motivo],
          ['Anamnesis', btn.dataset.anamnesis],
          ['Diagnóstico', btn.dataset.diagnostico],
          ['Tratamiento', btn.dataset.tratamiento],
          ['Recomendaciones', btn.dataset.recomendaciones],
        ];
        contenido.replaceChildren(...filas.map(([etiqueta, valor]) => {
          const p = document.createElement('p');
          const b = document.createElement('b');
          b.textContent = etiqueta + ': ';
          p.append(b, document.createTextNode(valor || '-'));
          return p;
        }));
        modal.style.display = 'flex';
      });
    });

    cerrar.addEventListener('click',()=>modal.style.display='none');
    modal.addEventListener('click',e=>{if(e.target===modal) modal.style.display='none'});
  </script>
@endsection