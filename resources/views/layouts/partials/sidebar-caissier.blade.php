<div class="nav-section">Commandes</div>
<a href="{{ route('caissier.commandes') }}"
   class="nav-link {{ request()->routeIs('caissier.commandes') ? 'active' : '' }}">
    <i class="bi bi-list-check"></i> Toutes les commandes
</a>
<a href="{{ route('caissier.scanner') }}"
   class="nav-link {{ request()->routeIs('caissier.scanner') ? 'active' : '' }}">
    <i class="bi bi-qr-code-scan"></i> Scanner QR
</a>