<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu indisponible</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center p-4">
        <div class="display-1 mb-3">😴</div>
        <h1 class="h3 fw-bold">Menu indisponible</h1>
        <p class="text-muted">{{ $table->etablissement->nom }} n'a pas de menu actif pour le moment.</p>
        <p class="text-muted small">Veuillez demander au personnel.</p>
    </div>
</body>
</html>