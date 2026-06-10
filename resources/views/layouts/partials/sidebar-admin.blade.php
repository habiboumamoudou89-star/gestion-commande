<div class="nav-section">Général</div>
<a href="{{ route('admin.dashboard') }}"
   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<div class="nav-section">Menu</div>
<a href="{{ route('admin.menus.index') }}"
   class="nav-link {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
    <i class="bi bi-journal-text"></i> Menus & Articles
</a>

<div class="nav-section">Établissement</div>
<a href="{{ route('admin.etablissement.edit') }}"
   class="nav-link {{ request()->routeIs('admin.etablissement*') ? 'active' : '' }}">
    <i class="bi bi-building"></i> Mon établissement
</a>
<a href="{{ route('admin.tables.index') }}"
   class="nav-link {{ request()->routeIs('admin.tables*') ? 'active' : '' }}">
    <i class="bi bi-grid-3x3"></i> Tables & QR codes
</a>

<div class="nav-section">Commandes</div>
<a href="{{ route('admin.commandes.index') }}"
   class="nav-link {{ request()->routeIs('admin.commandes*') ? 'active' : '' }}">
    <i class="bi bi-receipt"></i> Commandes
</a>