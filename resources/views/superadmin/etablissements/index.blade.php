@extends('layouts.app')
@section('title', 'Établissements')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-building me-2"></i>Établissements</h1>
</div>

<div class="row g-3">
    @forelse($etablissements as $etab)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    @if($etab->logo)
                        <img src="{{ $etab->logo_url }}" class="rounded" width="50" height="50" style="object-fit:cover">
                    @else
                        <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:50px;height:50px">
                            <i class="bi bi-building text-muted fs-4"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $etab->nom }}</h5>
                        <div class="text-muted small">{{ $etab->admin->name ?? '—' }}</div>
                    </div>
                </div>
                <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $etab->adresse ?? 'Adresse non définie' }}</p>
                <p class="text-muted small mb-3"><i class="bi bi-telephone me-1"></i>{{ $etab->telephone ?? '—' }}</p>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark"><i class="bi bi-grid-3x3 me-1"></i>{{ $etab->tables_count }} tables</span>
                    <span class="badge bg-light text-dark"><i class="bi bi-journal-text me-1"></i>{{ $etab->menus_count }} menus</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-building fs-1 d-block mb-2"></i>
                Aucun établissement enregistré.
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection