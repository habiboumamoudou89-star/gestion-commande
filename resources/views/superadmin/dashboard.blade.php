@extends('layouts.app')
@section('title', 'Dashboard Super Admin')

@section('content')
<div class="page-header">
    <h1>Dashboard Super Admin</h1>
</div>

<div class="row g-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                <i class="bi bi-people fs-4 text-primary"></i>
            </div>
            <div>
                <div class="text-muted small">Total utilisateurs</div>
                <div class="fw-bold fs-4">{{ $stats['users'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                <i class="bi bi-person-check fs-4 text-success"></i>
            </div>
            <div>
                <div class="text-muted small">Admins</div>
                <div class="fw-bold fs-4">{{ $stats['admins'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                <i class="bi bi-person-badge fs-4 text-warning"></i>
            </div>
            <div>
                <div class="text-muted small">Caissiers</div>
                <div class="fw-bold fs-4">{{ $stats['caissiers'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-3 bg-info bg-opacity-10 p-3">
                <i class="bi bi-building fs-4 text-info"></i>
            </div>
            <div>
                <div class="text-muted small">Établissements</div>
                <div class="fw-bold fs-4">{{ $stats['etablissements'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-3">
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-primary">
        <i class="bi bi-people me-2"></i>Gérer les utilisateurs
    </a>
    <a href="{{ route('superadmin.etablissements.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-building me-2"></i>Gérer les établissements
    </a>
</div>
@endsection