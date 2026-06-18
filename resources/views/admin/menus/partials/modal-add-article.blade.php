<div class="modal fade" id="modalArticle{{ $categorie->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter un article — {{ $categorie->nom }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="categorie_id" value="{{ $categorie->id }}">
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nom de l'article <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required placeholder="Ex: Couscous Royal">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Prix (MAD) <span class="text-danger">*</span></label>
                        <input type="number" name="prix" class="form-control" required min="0" step="0.50" placeholder="0.00">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Ingrédients, allergènes..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Photo</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Ajouter l'article</button>
                </div>
            </form>
        </div>
    </div>
</div>