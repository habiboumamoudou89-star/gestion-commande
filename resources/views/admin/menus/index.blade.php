@extends('layouts.app')
@section('title', 'Menus')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-journal-text me-2"></i>Menus</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauMenu">
        <i class="bi bi-plus-lg me-1"></i> Nouveau menu
    </button>
</div>

@forelse($menus as $menu)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold">{{ $menu->titre }}</span>
            @if($menu->actif)
                <span class="badge bg-success">Actif</span>
            @else
                <form action="{{ route('admin.menus.activer', $menu) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline-success py-0">Activer</button>
                </form>
            @endif
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCategorie{{ $menu->id }}">
                <i class="bi bi-plus"></i> Catégorie
            </button>
            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Supprimer ce menu ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
        </div>
    </div>

    <div class="card-body">
        @if($menu->categoriesRacines->isEmpty())
            <p class="text-muted text-center py-3">Aucune catégorie. Commencez par en créer une.</p>
        @endif

        @foreach($menu->categoriesRacines as $categorie)
        <div class="border rounded-3 mb-3 overflow-hidden">
            <div class="d-flex justify-content-between align-items-center p-3 bg-light">
                <div class="d-flex align-items-center gap-2">
                    @if($categorie->image)
                        <img src="{{ $categorie->image_url }}" class="rounded" width="36" height="36" style="object-fit:cover">
                    @endif
                    <strong>{{ $categorie->nom }}</strong>
                    <span class="text-muted small">({{ $categorie->articles->count() }} articles)</span>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-xs btn-outline-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalArticle{{ $categorie->id }}">
                        <i class="bi bi-plus"></i> Article
                    </button>
                    <button class="btn btn-xs btn-outline-secondary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditCat{{ $categorie->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Supprimer cette catégorie et tous ses articles ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>

            @if($categorie->articles->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light border-top">
                        <tr>
                            <th class="ps-3" style="width:50px"></th>
                            <th>Article</th>
                            <th>Prix</th>
                            <th>Options</th>
                            <th>Dispo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categorie->articles as $article)
                        <tr>
                            <td class="ps-3">
                                @if($article->image)
                                    <img src="{{ $article->image_url }}" class="rounded" width="40" height="40" style="object-fit:cover">
                                @else
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $article->nom }}</div>
                                <div class="text-muted small">{{ Str::limit($article->description, 60) }}</div>
                            </td>
                            <td class="fw-semibold">{{ number_format($article->prix, 2) }} MAD</td>
                            <td>
                                <span class="badge bg-secondary rounded-pill">{{ $article->options->count() }}</span>
                            </td>
                            <td>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input toggle-dispo" type="checkbox"
                                           data-id="{{ $article->id }}"
                                           {{ $article->disponible ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditArticle{{ $article->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cet article ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @foreach($categorie->articles as $article)
            @include('admin.menus.partials.modal-edit-article', compact('article'))
            @endforeach

            @include('admin.menus.partials.modal-add-article', ['categorie' => $categorie])
        </div>

        @include('admin.menus.partials.modal-edit-categorie', compact('categorie'))
        @endforeach
    </div>
</div>

@include('admin.menus.partials.modal-add-categorie', compact('menu'))
@empty
<div class="card">
    <div class="card-body text-center text-muted py-5">
        <i class="bi bi-journal-text fs-1 d-block mb-2"></i>
        Aucun menu créé. Cliquez sur "Nouveau menu" pour commencer.
    </div>
</div>
@endforelse

<div class="modal fade" id="modalNouveauMenu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Nouveau menu</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Titre du menu</label>
                    <input type="text" name="titre" class="form-control" required placeholder="Ex: Menu Été 2025">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-dispo').forEach(toggle => {
    toggle.addEventListener('change', async function() {
        const id = this.dataset.id;
        const resp = await fetch(`/admin/articles/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Content-Type': 'application/json'
            }
        });
        if (!resp.ok) { this.checked = !this.checked; alert('Erreur'); }
    });
});
</script>
@endpush