<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Article;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CommandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'table_id'           => 'required|exists:tables,id',
            'items'              => 'required|array|min:1',
            'items.*.article_id' => 'required|exists:articles,id',
            'items.*.quantite'   => 'required|integer|min:1',
            'items.*.options'    => 'nullable|array',
            'items.*.options.*'  => 'exists:options,id',
        ]);

        $commande = null;

        DB::transaction(function () use ($request, &$commande) {
            $total    = 0;
            $commande = Commande::create([
                'table_id' => $request->table_id,
                'statut'   => 'en_attente',
                'total'    => 0,
            ]);

            foreach ($request->items as $itemData) {
                $article = Article::findOrFail($itemData['article_id']);
                $item    = $commande->items()->create([
                    'article_id'    => $article->id,
                    'quantite'      => $itemData['quantite'],
                    'prix_unitaire' => $article->prix,
                ]);

                $prixOptions = 0;
                if (!empty($itemData['options'])) {
                    $item->options()->attach($itemData['options']);
                    $prixOptions = Option::whereIn('id', $itemData['options'])->sum('prix_supplementaire');
                }

                $total += ($article->prix + $prixOptions) * $itemData['quantite'];
            }

            $url = route('client.commande.confirmation', $commande->id);
            $qr  = QrCode::format('png')->size(250)->generate($url);
            $commande->update([
                'total'           => $total,
                'qr_confirmation' => base64_encode($qr),
            ]);
        });

        return redirect()->route('client.commande.confirmation', $commande->id);
    }

    public function confirmation(Commande $commande)
    {
        $commande->load('items.article', 'items.options', 'table');
        return view('client.confirmation', compact('commande'));
    }

    public function statut(Commande $commande)
    {
        return response()->json(['statut' => $commande->statut]);
    }
}