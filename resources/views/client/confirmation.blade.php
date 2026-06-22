<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Commande confirmée</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .confirmation-card { max-width: 480px; margin: 2rem auto; }
        .status-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.5rem; border-radius: 50px; font-weight: 600;
        }
        .status-en_attente { background: #fef9c3; color: #854d0e; }
        .status-en_cours   { background: #dbeafe; color: #1e40af; }
        .status-prete      { background: #dcfce7; color: #166534; animation: pulse .8s infinite; }
        .status-servie     { background: #f1f5f9; color: #475569; }
        .status-annulee    { background: #fee2e2; color: #991b1b; }
        @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.6;} }
    </style>
</head>
<body>
<div class="container py-4 confirmation-card">
    <div class="text-center mb-4">
        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
             style="width:72px;height:72px">
            <i class="bi bi-check-lg" style="font-size:2rem"></i>
        </div>
        <h1 class="fw-bold h3">Commande envoyée !</h1>
        <p class="text-muted">Table {{ $commande->table->numero }} · Commande #{{ $commande->id }}</p>
    </div>

    {{-- Statut --}}
    <div class="card mb-3">
        <div class="card-body text-center py-4">
            <div class="text-muted small mb-2">Statut de votre commande</div>
            <div class="status-badge status-{{ $commande->statut }}" id="statusBadge">
                <i class="bi bi-circle-fill"></i>
                <span id="statusText">{{ $commande->label_statut }}</span>
            </div>
            <div class="text-muted small mt-3">
                @if($commande->statut === 'en_attente')
                    Votre commande est en attente de prise en charge...
                @elseif($commande->statut === 'en_cours')
                    Votre commande est en cours de préparation 🍳
                @elseif($commande->statut === 'prete')
                    🎉 Votre commande est prête ! Le serveur arrive.
                @endif
            </div>
        </div>
    </div>

    {{-- Récapitulatif --}}
    <div class="card mb-3">
        <div class="card-header fw-semibold py-3">Récapitulatif</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($commande->items as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <span class="badge bg-secondary rounded-pill me-1">×{{ $item->quantite }}</span>
                        {{ $item->article->nom }}
                        @if($item->options->isNotEmpty())
                            <div class="text-muted small ms-3">{{ $item->options->pluck('nom')->join(', ') }}</div>
                        @endif
                    </div>
                    <span class="fw-semibold">{{ number_format($item->sous_total, 2) }} MAD</span>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="card-footer d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span class="text-warning">{{ number_format($commande->total, 2) }} MAD</span>
        </div>
    </div>

    {{-- QR de confirmation --}}
    @if($commande->qr_confirmation)
    <div class="card text-center mb-3">
        <div class="card-body py-4">
            <p class="text-muted small mb-3">Montrez ce QR code au caissier</p>
            <div style="max-width:200px;margin:0 auto">
                {!! base64_decode($commande->qr_confirmation) !!}
            </div>
            <div class="text-muted small mt-2">Commande #{{ $commande->id }}</div>
        </div>
    </div>
    @endif

    <a href="javascript:history.back()" class="btn btn-outline-secondary w-100">
        <i class="bi bi-arrow-left me-2"></i>Retour au menu
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const commandeId = {{ $commande->id }};
const labels = {
    'en_attente': 'En attente',
    'en_cours': 'En cours de préparation 🍳',
    'prete': '🎉 Prête ! Le serveur arrive.',
    'servie': 'Servie ✅',
    'annulee': 'Annulée'
};

async function checkStatut() {
    try {
        const resp = await fetch(`/menu/commande/${commandeId}/statut`);
        const data = await resp.json();
        document.getElementById('statusText').textContent = labels[data.statut] || data.statut;
        document.getElementById('statusBadge').className = `status-badge status-${data.statut}`;
        if (data.statut === 'servie' || data.statut === 'annulee') clearInterval(interval);
    } catch(e) {}
}

const interval = setInterval(checkStatut, 15000);
</script>
</body>
</html>
