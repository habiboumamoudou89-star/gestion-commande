@extends('layouts.app')
@section('title', 'Mon établissement')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-building me-2"></i>Mon établissement</h1>
</div>

<div class="card" style="max-width:700px">
    <div class="card-body p-4">
        <form action="{{ route('admin.etablissement.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control"
                       value="{{ old('nom', $etab->nom ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Adresse</label>
                <input type="text" name="adresse" class="form-control"
                       value="{{ old('adresse', $etab->adresse ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Téléphone</label>
                <input type="text" name="telephone" class="form-control"
                       value="{{ old('telephone', $etab->telephone ?? '') }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $etab->description ?? '') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Logo</label>
                @if($etab && $etab->logo)
                    <div class="mb-2">
                        <img src="{{ $etab->logo_url }}" class="img-thumbnail" width="100">
                    </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save me-2"></i>Enregistrer
            </button>
        </form>
    </div>
</div>
@endsection