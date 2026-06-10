@extends('layouts.app')
@section('title', 'Scanner QR')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-qr-code-scan me-2"></i>Scanner un QR code</h1>
</div>

<div class="card" style="max-width:500px;margin:0 auto">
    <div class="card-body text-center p-4">
        <p class="text-muted">Utilisez la caméra pour scanner le QR code d'une table.</p>
        <div id="reader" class="mb-3" style="width:100%"></div>
        <div id="result" class="alert alert-success d-none"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");
html5QrCode.start(
    { facingMode: "environment" },
    { fps: 10, qrbox: 250 },
    (decodedText) => {
        html5QrCode.stop();
        document.getElementById('result').classList.remove('d-none');
        document.getElementById('result').textContent = 'QR détecté ! Redirection...';
        window.location.href = decodedText;
    }
);
</script>
@endpush