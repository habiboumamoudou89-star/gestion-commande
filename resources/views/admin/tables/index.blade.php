@extends('layouts.app')
@section('title', 'Tables & QR codes')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-grid-3x3 me-2"></i>Tables & QR codes</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelleTable">
        <i class="bi bi-plus-lg me-1"></i> Nouvelle table
    </button>
</div>

<div class="row g-3">
    @forelse($tables as $table)
    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="card h-100">
            <div class="card-body text-center p-3">
                @if($table->qr_code)
                    <img src="{{ asset('storage/' . $table->qr_code) }}"
                         class="img-fluid mb-2 border rounded"
                         style="max-width:140px"
                         alt="QR Table {{ $table->numero }}">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2 mx-auto"
                         style="width:140px;height:140px">
                        <i class="bi bi-qr-code text-muted" style="font-size:3rem"></i>
                    </div>
                @endif

                <h5 class="fw-bold mb-1">{{ $table->numero }}</h5>

                @if($table->commandeEnCours)
                    <span class="badge {{ $table->commandeEnCours->badge_class }} mb-2">
                        {{ $table->commandeEnCours->label_statut }}
                    </span>
                @else
                    <span class="badge bg-light text-muted mb-2">Libre</span>
                @endif

                <div class="d-flex gap-1 justify-content-center mt-2">
                    <form action="{{ route('admin.tables.qr', $table) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-outline-secondary" title="Régénérer QR">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </form>

                    @if($table->qr_code)
                    <a href="{{ route('admin.tables.qr.download', $table) }}"
                       class="btn btn-sm btn-outline-primary" title="Télécharger">
                        <i class="bi bi-download"></i>
                    </a>
                    @endif

                    <a href="{{ route('client.menu', $table->qr_token) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-success" title="Voir le menu client">
                        <i class="bi bi-eye"></i>
                    </a>

                    <form action="{{ route('admin.tables.destroy', $table) }}" method="POST"
                          onsubmit="return confirm('Supprimer la table {{ $table->numero }} ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-grid-3x3 fs-1 d-block mb-2"></i>
                Aucune table créée. Cliquez sur "Nouvelle table" pour commencer.
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="modal fade" id="modalNouvelleTable" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle table</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Numéro / Nom de la table <span class="text-danger">*</span></label>
                    <input type="text" name="numero" class="form-control" required
                           placeholder="Ex: T1, Terrasse 3, VIP 2">
                    <div class="form-text">Un QR code unique sera automatiquement généré.</div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary"><i class="bi bi-qr-code me-1"></i>Créer & générer QR</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection