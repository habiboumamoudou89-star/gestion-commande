<div class="modal fade" id="modalEditArticle{{ $article->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier : {{ $article->nom }}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#infos{{ $article->id }}">Infos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#options{{ $article->id }}">
                                Options <span class="badge bg-secondary">{{ $article->options->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="infos{{ $article->id }}">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Nom</label>
                                    <input type="text" name="nom" class="form-control" value="{{ $article->nom }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix (MAD)</label>
                                    <input type="number" name="prix" class="form-control" value="{{ $article->prix }}" required step="0.50">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $article->description }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Photo</label>
                                    @if($article->image)
                                        <div class="mb-2"><img src="{{ $article->image_url }}" class="img-thumbnail" width="80"></div>
                                    @endif
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="disponible" value="1"
                                               id="dispo{{ $article->id }}" {{ $article->disponible ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dispo{{ $article->id }}">Disponible</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="options{{ $article->id }}">
                            @foreach($article->options as $option)
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">{{ $option->nom }}</span>
                                    @if($option->groupe) <span class="badge bg-light text-dark">{{ $option->groupe }}</span> @endif
                                    @if($option->prix_supplementaire > 0)
                                        <span class="text-success small">+{{ $option->prix_supplementaire }} MAD</span>
                                    @endif
                                    @if($option->obligatoire) <span class="badge bg-warning text-dark">Obligatoire</span> @endif
                                </div>
                                <form action="{{ route('admin.options.destroy', $option) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cette option ?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x"></i></button>
                                </form>
                            </div>
                            @endforeach

                            <hr>
                            <p class="fw-semibold">Ajouter une option</p>
                            <form action="{{ route('admin.articles.options.store', $article) }}" method="POST">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" name="nom" class="form-control form-control-sm" placeholder="Nom option" required>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="groupe" class="form-control form-control-sm" placeholder="Groupe (optionnel)">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="prix_supplementaire" class="form-control form-control-sm" placeholder="Prix +" value="0" min="0" step="0.5">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="obligatoire" value="1">
                                            <label class="form-check-label small">Obligatoire</label>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>