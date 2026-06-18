<div class="modal fade" id="modalCategorie{{ $menu->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-folder-plus me-2"></i>Nouvelle catégorie</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required placeholder="Ex: Plats principaux">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sous-catégorie de</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Catégorie racine —</option>
                            @foreach($menu->categoriesRacines as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image (optionnel)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>