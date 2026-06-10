<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtablissementController extends Controller
{
    public function dashboard()
    {
        $etab  = auth()->user()->etablissement;
        $stats = [
            'commandes_today' => 0,
            'en_attente'      => 0,
            'ca_today'        => 0,
            'tables'          => 0,
        ];
        $commandesRecentes = collect();

        if ($etab) {
            $stats['tables']          = $etab->tables()->count();
            $stats['commandes_today'] = \App\Models\Commande::whereHas('table', fn($q) => $q->where('etablissement_id', $etab->id))
                ->whereDate('created_at', today())->count();
            $stats['en_attente']      = \App\Models\Commande::whereHas('table', fn($q) => $q->where('etablissement_id', $etab->id))
                ->where('statut', 'en_attente')->count();
            $stats['ca_today']        = \App\Models\Commande::whereHas('table', fn($q) => $q->where('etablissement_id', $etab->id))
                ->whereDate('created_at', today())->sum('total');
            $commandesRecentes        = \App\Models\Commande::whereHas('table', fn($q) => $q->where('etablissement_id', $etab->id))
                ->with('table', 'items')->latest()->take(10)->get();
        }

        return view('admin.dashboard', compact('etab', 'stats', 'commandesRecentes'));
    }

    public function edit()
    {
        $etab = auth()->user()->etablissement;
        return view('admin.etablissement.edit', compact('etab'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'adresse'     => 'nullable|string',
            'description' => 'nullable|string',
            'telephone'   => 'nullable|string|max:20',
            'logo'        => 'nullable|image|max:2048',
        ]);

        $etab = auth()->user()->etablissement;
        if (!$etab) {
            $etab = auth()->user()->etablissement()->create($request->only(['nom','adresse','description','telephone']));
        } else {
            $data = $request->only(['nom','adresse','description','telephone']);
            if ($request->hasFile('logo')) {
                if ($etab->logo) Storage::disk('public')->delete($etab->logo);
                $data['logo'] = $request->file('logo')->store('logos', 'public');
            }
            $etab->update($data);
        }

        return back()->with('success', 'Établissement mis à jour.');
    }
}