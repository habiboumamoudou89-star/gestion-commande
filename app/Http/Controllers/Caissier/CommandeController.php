<?php
namespace App\Http\Controllers\Caissier;

use App\Http\Controllers\Controller;
use App\Models\Commande;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with(['table', 'items.article', 'items.options'])
            ->whereIn('statut', ['en_attente', 'en_cours', 'prete'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('statut');

        return view('caissier.commandes', compact('commandes'));
    }

    public function updateStatut(Commande $commande, string $statut)
    {
        $transitions = [
            'en_attente' => ['en_cours', 'annulee'],
            'en_cours'   => ['prete', 'annulee'],
            'prete'      => ['servie'],
        ];

        if (!in_array($statut, $transitions[$commande->statut] ?? [])) {
            return back()->with('error', 'Transition non autorisée.');
        }

        $commande->update(['statut' => $statut]);
        return back()->with('success', 'Commande mise à jour.');
    }

    public function scanner()
    {
        return view('caissier.scanner');
    }
}