<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;

class CommandeController extends Controller
{
    public function index()
    {
        $etab      = auth()->user()->etablissement;
        $commandes = Commande::whereHas('table', fn($q) => $q->where('etablissement_id', $etab->id))
            ->with('table', 'items.article')
            ->latest()
            ->paginate(20);

        return view('admin.commandes.index', compact('commandes'));
    }
}