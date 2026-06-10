<div class="nav-section">Administration</div>
<a href="{{ route('superadmin.dashboard') }}"
   class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('superadmin.users.index') }}"
   class="nav-link {{ request()->routeIs('superadmin.users*') ? 'active' : '' }}">
    <i class="bi bi-people"></i> Utilisateurs
</a>
<a href="{{ route('superadmin.etablissements.index') }}"
   class="nav-link {{ request()->routeIs('superadmin.etablissements*') ? 'active' : '' }}">
    <i class="bi bi-building"></i> Établissements
</a>