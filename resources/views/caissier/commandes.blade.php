@extends('layouts.app')
@section('title', 'Commandes')

@push('styles')
<style>
    .kanban-col { min-height: 200px; }
    .commande-card {
        border-left: 4px solid transparent;
        transition: transform .15s;
    }
    .commande-card:hover { transform: translateY(-2px); }
    .col-en_attente .commande-card { border-left-color: #f59e0b; }
    .col-en_cours   .commande-card { border-left-color: #3b82f6; }
    .col-prete      .commande-card { border-left-color: #22c55e; }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-list-check me-2"></i>Commandes en cours</h1>
    <div class="d-flex align-items-center gap-2">
        <span class="text-success small"><i class="bi bi-circle-fill"></i> Live</span>
        <span class="text-muted small" id="lastUpdate"></span>
        <a href="{{ route('caissier.scanner') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-qr-code-scan me-1"></i>Scanner
        </a>
    </div>
</div>

<div class="row g-3">
    {{-- En attente --}}
    <div class="col-md-4 col-en_attente">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between py-3 px-3">
                <span class="fw-semibold">
                    <i class="bi bi-hourglass-split text-warning me-2"></i>En attente
                </span>
                <span class="badge bg-warning text-dark rounded-pill">
                    {{ ($commandes['en_attente'] ?? collect())->count() }}
                </span>
            </div>
            <div class="card-body kanban-col p-2">
                @forelse($commandes['en_attente'] ?? [] as $cmd)
                    @include('caissier.partials.commande-card', ['cmd' => $cmd])
                @empty
                    <p class="text-center text-muted py-4 small">Aucune commande en attente</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- En cours --}}
    <div class="col-md-4 col-en_cours">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between py-3 px-3">
                <span class="fw-semibold">
                    <i class="bi bi-fire text-info me-2"></i>En cours
                </span>
                <span class="badge bg-info rounded-pill">
                    {{ ($commandes['en_cours'] ?? collect())->count() }}
                </span>
            </div>
            <div class="card-body kanban-col p-2">
                @forelse($commandes['en_cours'] ?? [] as $cmd)
                    @include('caissier.partials.commande-card', ['cmd' => $cmd])
                @empty
                    <p class="text-center text-muted py-4 small">Aucune commande en cours</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Prête --}}
    <div class="col-md-4 col-prete">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between py-3 px-3">
                <span class="fw-semibold">
                    <i class="bi bi-check-circle text-success me-2"></i>Prêtes à servir
                </span>
                <span class="badge bg-success rounded-pill">
                    {{ ($commandes['prete'] ?? collect())->count() }}
                </span>
            </div>
            <div class="card-body kanban-col p-2">
                @forelse($commandes['prete'] ?? [] as $cmd)
                    @include('caissier.partials.commande-card', ['cmd' => $cmd])
                @empty
                    <p class="text-center text-muted py-4 small">Aucune commande prête</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let countdown = 30;
setInterval(() => {
    countdown--;
    document.getElementById('lastUpdate').textContent = `Actualisation dans ${countdown}s`;
    if (countdown <= 0) { window.location.reload(); }
}, 1000);

document.querySelectorAll('.time-ago').forEach(el => {
    const date = new Date(el.dataset.time);
    const diff = Math.floor((Date.now() - date) / 60000);
    el.textContent = diff < 1 ? 'À l\'instant' : `Il y a ${diff} min`;
});
</script>
@endpush