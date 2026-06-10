@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Dashboard</h1>
        <p class="text-muted mb-0">{{ $etab->nom ?? 'Aucun établissement' }}</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                <i class="bi bi-receipt fs-4 text-primary"></i>
            </div>
            <div>
                <div class="text-muted small">Commandes aujourd'hui</div>
                <div class="fw-bold fs-4">{{ $stats['commandes_today'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                <i class="bi bi-clock fs-4 text-warning"></i>
            </div>
            <div>
                <div class="text-muted small">En attente</div>
                <div class="fw-bold fs-4">{{ $stats['en_attente'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                <i class="bi bi-cash-stack fs-4 text-success"></i>
            </div>
            <div>
                <div class="text-muted small">CA aujourd'hui (MAD)</div>
                <div class="fw-bold fs-4">{{ number_format($stats['ca_today'], 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-info bg-opacity-10 p-3">
                <i class="bi bi-grid-3x3 fs-4 text-info"></i>
            </div>
            <div>
                <div class="text-muted small">Tables</div>
                <div class="fw-bold fs-4">{{ $stats['tables'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
        <span><i class="bi bi-clock-history me-2"></i>Commandes récentes</span>
        <a href="{{ route('admin.commandes.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Table</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Heure</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandesRecentes as $cmd)
                    <tr>
                        <td class="ps-4 text-muted">{{ $cmd->id }}</td>
                        <td><strong>{{ $cmd->table->numero }}</strong></td>
                        <td>{{ $cmd->items->count() }} article(s)</td>
                        <td>{{ number_format($cmd->total, 2) }} MAD</td>
                        <td>
                            <span class="badge {{ $cmd->badge_class }} rounded-pill px-3">
                                {{ $cmd->label_statut }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $cmd->created_at->format('H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox me-2"></i>Aucune commande aujourd'hui
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection