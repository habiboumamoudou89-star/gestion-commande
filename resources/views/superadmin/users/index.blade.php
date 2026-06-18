@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="bi bi-people me-2"></i>Gestion des utilisateurs</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelUtilisateur">
        <i class="bi bi-person-plus me-1"></i> Nouvel utilisateur
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Établissement</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                     style="width:36px;height:36px;font-size:.85rem">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td>
                            @foreach($user->getRoleNames() as $role)
                            <span class="badge
                                @if($role === 'super_admin') bg-danger
                                @elseif($role === 'admin') bg-primary
                                @else bg-success @endif
                                rounded-pill">{{ $role }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->etablissement?->nom ?? '—' }}</td>
                        <td class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditUser{{ $user->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer {{ $user->name }} ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modifier : {{ $user->name }}</h5>
                                    <button class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Rôle</label>
                                            <select name="role" class="form-select" required>
                                                <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                                <option value="caissier" {{ $user->hasRole('caissier') ? 'selected' : '' }}>Caissier</option>
                                                <option value="super_admin" {{ $user->hasRole('super_admin') ? 'selected' : '' }}>Super Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nouveau mot de passe (laisser vide pour conserver)</label>
                                            <input type="password" name="password" class="form-control" autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>

<div class="modal fade" id="modalNouvelUtilisateur" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Nouvel utilisateur</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin (propriétaire d'un établissement)</option>
                            <option value="caissier">Caissier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-primary"><i class="bi bi-person-check me-1"></i>Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection