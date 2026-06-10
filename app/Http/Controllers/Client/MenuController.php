<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;

class MenuController extends Controller
{
    public function show(string $qrToken)
    {
        $table = RestaurantTable::where('qr_token', $qrToken)
            ->with('etablissement')
            ->firstOrFail();

        $menu = $table->etablissement->menuActif()
            ->with([
                'categoriesRacines.articles.options',
                'categoriesRacines.sousCategories.articles.options',
            ])
            ->first();

        if (!$menu) {
            return view('client.menu-indisponible', compact('table'));
        }

        return view('client.menu', compact('table', 'menu'));
    }
}