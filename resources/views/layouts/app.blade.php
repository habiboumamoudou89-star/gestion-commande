<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QR Order') — QR Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 250px; --topbar-height: 60px; }
        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }

        .topbar {
            height: var(--topbar-height);
            background: #1a1a2e;
            position: fixed; top: 0; left: 0; right: 0; z-index: 1030;
            display: flex; align-items: center; padding: 0 1.5rem; gap: 1rem;
        }
        .topbar .brand { color: #fff; font-weight: 700; font-size: 1.2rem; text-decoration: none; }
        .topbar .brand span { color: #f39c12; }

        .sidebar {
            width: var(--sidebar-width);
            position: fixed; top: var(--topbar-height); bottom: 0; left: 0;
            background: #fff; border-right: 1px solid #e2e8f0;
            overflow-y: auto; z-index: 1020; padding-top: 1rem;
        }
        .sidebar .nav-section {
            font-size: .7rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .08em;
            padding: .5rem 1.2rem .2rem;
        }
        .sidebar .nav-link {
            color: #475569; padding: .55rem 1.2rem;
            display: flex; align-items: center; gap: .6rem;
            font-size: .9rem; transition: background .15s, color .15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { background: #eef2ff; color: #4f46e5; }
        .sidebar .nav-link i { width: 20px; text-align: center; }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 1.8rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; color: #1e293b; }

        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,.08); border-radius: 12px; }
        .card-header { background: #fff; border-bottom: 1px solid #f1f5f9; font-weight: 600; border-radius: 12px 12px 0 0 !important; }

        .badge-en_attente { background: #fef9c3; color: #854d0e; }
        .badge-en_cours   { background: #dbeafe; color: #1e40af; }
        .badge-prete      { background: #dcfce7; color: #166534; }
        .badge-servie     { background: #f1f5f9; color: #475569; }
        .badge-annulee    { background: #fee2e2; color: #991b1b; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="topbar">
    <a href="/" class="brand">QR<span>Order</span></a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white-50 small">{{ auth()->user()->name }}</span>
        @if(auth()->user()->hasRole('super_admin'))
            <span class="badge bg-danger">Super Admin</span>
        @elseif(auth()->user()->hasRole('admin'))
            <span class="badge bg-primary">Admin</span>
        @else
            <span class="badge bg-success">Caissier</span>
        @endif
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</nav>

<aside class="sidebar">
    @if(auth()->user()->hasRole('super_admin'))
        @include('layouts.partials.sidebar-superadmin')
    @elseif(auth()->user()->hasRole('admin'))
        @include('layouts.partials.sidebar-admin')
    @else
        @include('layouts.partials.sidebar-caissier')
    @endif
</aside>

<main class="main-content">
    @foreach(['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>