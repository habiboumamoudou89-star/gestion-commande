<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — QR Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            width: 100%; max-width: 420px;
            background: white; border-radius: 20px;
            padding: 2.5rem; box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .logo-wrap { font-size: 2.5rem; font-weight: 800; color: #1a1a2e; }
        .logo-wrap span { color: #f39c12; }
        .form-control { border-radius: 10px; padding: .75rem 1rem; }
        .btn-login {
            background: #1a1a2e; color: white; border: none;
            border-radius: 10px; padding: .85rem; font-weight: 600;
        }
        .btn-login:hover { background: #0f3460; color: white; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="logo-wrap">QR<span>Order</span></div>
        <p class="text-muted small mt-1">Système de commande par QR code</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger small py-2">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}" required autofocus
                   placeholder="votre@email.com">
        </div>
        <div class="mb-4">
            <label class="form-label small fw-semibold">Mot de passe</label>
            <input type="password" name="password" class="form-control"
                   required placeholder="••••••••">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Se souvenir de moi</label>
        </div>
        <button type="submit" class="btn btn-login w-100">Se connecter</button>
    </form>

    <div class="mt-4 text-center text-muted" style="font-size:.75rem">
        <p class="mb-1 fw-semibold">Comptes de démonstration :</p>
        <p class="mb-0">superadmin@qrorder.com</p>
        <p class="mb-0">admin@qrorder.com</p>
        <p class="mb-0">caissier@qrorder.com</p>
        <p class="mt-1">Mot de passe : <strong>password</strong></p>
    </div>
</div>
</body>
</html>