<div class="card commande-card mb-2">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <span class="fw-bold fs-5">{{ $cmd->table->numero }}</span>
                <span class="text-muted small ms-1">#{{ $cmd->id }}</span>
            </div>
            <span class="time-ago text-muted small"
                  data-time="{{ $cmd->created_at->toIso8601String() }}"></span>
        </div>

        <ul class="list-unstyled mb-2">
            @foreach($cmd->items as $item)
            <li class="small d-flex justify-content-between">
                <span>
                    <span class="badge bg-secondary rounded-pill me-1">×{{ $item->quantite }}</span>
                    {{ $item->article->nom }}
                </span>
                <span class="text-muted">{{ number_format($item->prix_unitaire * $item->quantite, 2) }}</span>
            </li>
            @if($item->options->isNotEmpty())
            <li class="text-muted small ps-3">
                {{ $item->options->pluck('nom')->join(', ') }}
            </li>
            @endif
            @endforeach
        </ul>

        <div class="d-flex justify-content-between align-items-center border-top pt-2">
            <span class="fw-bold">{{ number_format($cmd->total, 2) }} MAD</span>
            <div class="d-flex gap-1">
                @if($cmd->statut === 'en_attente')
                    <form action="{{ route('caissier.commandes.statut', [$cmd->id, 'en_cours']) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-play-fill"></i> Prendre en charge
                        </button>
                    </form>
                    <form action="{{ route('caissier.commandes.statut', [$cmd->id, 'annulee']) }}" method="POST"
                          onsubmit="return confirm('Annuler ?')">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-danger btn-sm ms-1">
                            <i class="bi bi-x"></i>
                        </button>
                    </form>
                @elseif($cmd->statut === 'en_cours')
                    <form action="{{ route('caissier.commandes.statut', [$cmd->id, 'prete']) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i> Prête
                        </button>
                    </form>
                @elseif($cmd->statut === 'prete')
                    <form action="{{ route('caissier.commandes.statut', [$cmd->id, 'servie']) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-secondary btn-sm">
                            <i class="bi bi-check2-all"></i> Servie
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>