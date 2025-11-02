<x-app-layout>
  <style>
    :root{
      --bg:#f4f7fb;
      --card:#ffffff;
      --ink:#0f172a;
      --muted:#6b7280;
      --ring:#e6ecf6;
      --primary:#2563eb;
      --primary-2:#1d4ed8;
      --chip:#eff6ff;
      --chip-ink:#1d4ed8;
      --shadow: 0 6px 20px rgba(15, 23, 42, .06);
    }

    .wrap{
      max-width: 980px;
      margin: 32px auto;
      padding: 0 16px;
    }

    .receta-card{
      background: var(--card);
      border-radius: 22px;
      box-shadow: var(--shadow);
      border: 1px solid var(--ring);
      overflow: hidden;
    }

    /* Header */
    .receta-head{
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 22px 24px;
      border-bottom: 1px solid var(--ring);
      background: linear-gradient(180deg, #fafcff, #fff);
    }
    .receta-head .left{
      display: flex;
      gap: 12px;
      align-items: center;
    }
    .badge-emoji{
      width: 44px; height: 44px;
      border-radius: 14px;
      background: #eaf2ff;
      display: grid; place-items: center;
      font-size: 22px;
      color: var(--primary);
    }
    .title{
      font: 700 22px/1.2 system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial;
      color: var(--ink);
      margin: 0;
    }
    .subtitle{
      margin-top: 4px;
      font: 500 13px/1.4 system-ui, Inter, Roboto, Arial;
      color: var(--muted);
    }

    .btn-link{
      font: 600 14px/1 system-ui, Inter, Roboto, Arial;
      color: var(--primary);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: 10px 14px;
      border-radius: 12px;
      transition: .2s ease;
    }
    .btn-link:hover{
      background: #f1f5ff;
      color: var(--primary-2);
    }

    /* Body sections */
    .receta-body{
      padding: 24px;
      background: var(--bg);
    }
    .section{
      background: var(--card);
      border: 1px solid var(--ring);
      border-radius: 16px;
      padding: 18px 18px;
      margin-bottom: 14px;
    }
    .section h3{
      margin: 0 0 8px;
      font: 700 16px/1.2 system-ui, Inter, Roboto, Arial;
      color: var(--ink);
    }
    .section p{
      margin: 0;
      white-space: pre-line; /* respeta saltos de línea del textarea */
      font: 500 14px/1.6 system-ui, Inter, Roboto, Arial;
      color: #374151;
    }

    /* Chip arriba del cuerpo (opcional si quieres mostrar tipo) */
    .chip{
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      font: 700 11px/1 system-ui, Inter, Roboto, Arial;
      color: var(--chip-ink);
      background: var(--chip);
      padding: 6px 10px;
      border-radius: 999px;
      border: 1px solid #dbeafe;
      margin-bottom: 10px;
    }

    /* CTA row (si luego agregas exportar o imprimir) */
    .cta-row{
      display:flex;
      gap:10px;
      justify-content:flex-end;
      margin-top: 12px;
    }
    .btn{
      appearance:none;
      border:none;
      cursor:pointer;
      padding: 10px 16px;
      border-radius: 12px;
      font: 700 14px/1 system-ui, Inter, Roboto, Arial;
    }
    .btn-primary{
      color:#fff;
      background: linear-gradient(180deg, var(--primary), var(--primary-2));
      box-shadow: 0 8px 20px rgba(37, 99, 235, .25);
    }
    .btn-primary:hover{ filter: brightness(1.03); }
    .btn-ghost{
      background: #f8fafc;
      color: #0f172a;
      border:1px solid var(--ring);
    }
    .btn-ghost:hover{ background:#eef2f8; }

    @media (max-width: 640px){
      .title{ font-size: 18px; }
      .subtitle{ font-size: 12px; }
      .receta-head{ padding: 18px; }
      .receta-body{ padding: 18px; }
    }
  </style>

  <div class="wrap">
    <div class="receta-card">
      <!-- Header -->
      <div class="receta-head">
        <div class="left">
          <div class="badge-emoji">📄</div>
          <div>
            <h1 class="title">{{ $receta->titulo ?: 'Receta' }}</h1>
            <div class="subtitle">
              Historia #{{ $receta->historia_id }}
              · {{ $receta->created_at->format('d/m/Y · H:i') }}
            </div>
          </div>
        </div>

        <a href="{{ route('vet.recetas.index', $receta->historia) }}" class="btn-link">← Volver al listado</a>
      </div>

      <!-- Body -->
      <div class="receta-body">
        <span class="chip">Receta</span>

        @if(filled($receta->indicaciones))
          <section class="section">
            <h3>Indicaciones</h3>
            <p>{{ $receta->indicaciones }}</p>
          </section>
        @endif

        @if(filled($receta->medicamentos))
          <section class="section">
            <h3>Medicamentos</h3>
            <p>{{ $receta->medicamentos }}</p>
          </section>
        @endif

        @if(filled($receta->notas))
          <section class="section">
            <h3>Notas</h3>
            <p>{{ $receta->notas }}</p>
          </section>
        @endif

        {{-- Si quieres botones de acción (PDF / Imprimir), descomenta:
        <div class="cta-row">
          <button class="btn btn-ghost">Imprimir</button>
          <button class="btn btn-primary">Descargar PDF</button>
        </div>
        --}}
      </div>
    </div>
  </div>
</x-app-layout>
