<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\EtablissementController as SuperAdminEtabController;
use App\Http\Controllers\Admin\EtablissementController as AdminEtabController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CategorieController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Caissier\CommandeController as CaissierCommandeController;
use App\Http\Controllers\Client\MenuController as ClientMenuController;
use App\Http\Controllers\Client\CommandeController as ClientCommandeController;

// ─── Auth ───────────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// ─── Redirect après login selon rôle ────────────────────────
Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    $user = auth()->user();
    if ($user->hasRole('super_admin')) return redirect()->route('superadmin.dashboard');
    if ($user->hasRole('admin'))       return redirect()->route('admin.dashboard');
    if ($user->hasRole('caissier'))    return redirect()->route('caissier.commandes');
    return redirect()->route('login');
})->middleware('auth');

// ─── Super Admin ─────────────────────────────────────────────
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('dashboard', [SuperAdminUserController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', SuperAdminUserController::class);
        Route::resource('etablissements', SuperAdminEtabController::class);
    });

// ─── Admin ───────────────────────────────────────────────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('dashboard', [AdminEtabController::class, 'dashboard'])->name('dashboard');

        // Établissement
        Route::get('etablissement/edit',   [AdminEtabController::class, 'edit'])->name('etablissement.edit');
        Route::put('etablissement/update', [AdminEtabController::class, 'update'])->name('etablissement.update');

        // Menus
        Route::resource('menus', MenuController::class)->except(['show']);
        Route::patch('menus/{menu}/activer', [MenuController::class, 'toggleActif'])->name('menus.activer');

        // Catégories
        Route::post('categories',               [CategorieController::class, 'store'])->name('categories.store');
        Route::put('categories/{categorie}',    [CategorieController::class, 'update'])->name('categories.update');
        Route::delete('categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');

        // Articles
        Route::post('articles',                   [ArticleController::class, 'store'])->name('articles.store');
        Route::put('articles/{article}',          [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('articles/{article}',       [ArticleController::class, 'destroy'])->name('articles.destroy');
        Route::patch('articles/{article}/toggle', [ArticleController::class, 'toggle'])->name('articles.toggle');
        Route::post('articles/{article}/options', [ArticleController::class, 'storeOption'])->name('articles.options.store');
        Route::delete('options/{option}',         [ArticleController::class, 'destroyOption'])->name('options.destroy');

        // Tables & QR
        Route::resource('tables', TableController::class)->except(['show', 'edit', 'update']);
        Route::post('tables/{table}/qr',          [TableController::class, 'regenererQr'])->name('tables.qr');
        Route::get('tables/{table}/qr/download',  [TableController::class, 'downloadQr'])->name('tables.qr.download');

        // Commandes (lecture seule)
        Route::get('commandes', [AdminCommandeController::class, 'index'])->name('commandes.index');
    });

// ─── Caissier ────────────────────────────────────────────────
Route::prefix('caissier')
    ->name('caissier.')
    ->middleware(['auth', 'role:caissier'])
    ->group(function () {
        Route::get('commandes',                              [CaissierCommandeController::class, 'index'])->name('commandes');
        Route::patch('commandes/{commande}/statut/{statut}', [CaissierCommandeController::class, 'updateStatut'])->name('commandes.statut');
        Route::get('scanner',                               [CaissierCommandeController::class, 'scanner'])->name('scanner');
    });

// ─── Client (public, accès par QR code) ──────────────────────
Route::prefix('menu')
    ->name('client.')
    ->group(function () {
        Route::get('{qrToken}',                       [ClientMenuController::class, 'show'])->name('menu');
        Route::post('commande',                       [ClientCommandeController::class, 'store'])->name('commande.store');
        Route::get('commande/{commande}/confirmation',[ClientCommandeController::class, 'confirmation'])->name('commande.confirmation');
        Route::get('commande/{commande}/statut',      [ClientCommandeController::class, 'statut'])->name('commande.statut');
    });
