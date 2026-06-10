@extends('layouts.app')
@section('title', 'Commandes')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-receipt me-2"></i>Toutes les commandes</h1>
</div>

<div class="card">
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
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $cmd)
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
                        <td class="text-muted small">{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox me-2"></i>Aucune commande
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $commandes->links() }}
    </div>
</div>
@endsection