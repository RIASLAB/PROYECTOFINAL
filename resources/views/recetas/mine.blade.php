@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/recetas-mine.css') }}">

<div class="page">
  {{-- Encabezado --}}
  <div class="page-header">
    <div class="header-left">
      <div class="icon-box">🧾</div>
      <div>
        <h1 class="title">Mis Recetas</h1>
        <p class="subtitle">Recetas creadas por ti (veterinario).</p>
      </div>
    </div>

    <div class="header-right">
      <a href="{{ route('vet.dashboard') }}" class="btn btn-back">⬅ Volver al Panel</a>
      @if(Route::has('historias.index'))
        <a href="{{ route('historias.index') }}" class="btn btn-ghost">📚 Historias</a>
      @endif
    </div>
  </div>

  {{-- Buscador --}}
  <form method="GET" class="search-card">
    <div class="search-row">
      <div class="search-input-wrap">
        <span class="search-icon">🔍</span>
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Buscar por mascota, indicaciones o notas...">
      </div>
      <button type="submit" class="btn btn-dark">Buscar</button>
    </div>
  </form>

  {{-- Contenido --}}
  @if($recetas->isEmpty())
    <div class="empty-card">
      <div class="empty-icon">🍽️</div>
      <h2>No hay recetas</h2>
      <p>No encontramos resultados con tu criterio actual.</p>
    </div>
  @else
    <div class="cards">
      @foreach($recetas as $r)
      <div class="card">
        <div class="card-head">
          <div class="card-meta">
            <div class="pill-icon">🧪</div>
            <div>
              <p class="meta-small">
                {{ ($r->fecha?->format('d/m/Y')) ?? $r->created_at->format('d/m/Y') }}
                · Historia #{{ $r->historia_id }}
              </p>
              <p class="meta-main">
                Mascota: <strong>{{ $r->mascota->nombre ?? '—' }}</strong>
              </p>
            </div>
          </div>
          <span class="badge">RX</span>
        </div>

        <div class="indicaciones line-clamp-3">
          {{ \Illuminate\Support\Str::limit(strip_tags($r->indicaciones), 220) }}
        </div>

        @if($r->notas)
        <div class="notes">
          <span class="tag">Notas</span>
          {{ \Illuminate\Support\Str::limit($r->notas, 120) }}
        </div>
        @endif

        <div class="card-actions">
          @if(Route::has('recetas.show'))
            <a href="{{ route('recetas.show', $r->id) }}" class="btn btn-primary">Ver</a>
          @endif
          @if(Route::has('recetas.edit'))
            <a href="{{ route('recetas.edit', $r->id) }}" class="btn btn-warn">Editar</a>
          @endif
          @if(Route::has('facturas.createFromHistoria'))
            <a href="{{ route('facturas.createFromHistoria', $r->historia_id) }}" class="btn btn-success">Facturar</a>
          @endif
        </div>
      </div>
      @endforeach
    </div>

    <div class="pagination">
      {{ $recetas->links() }}
    </div>
  @endif
</div>
@endsection
