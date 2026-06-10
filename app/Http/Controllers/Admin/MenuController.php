<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $etab  = auth()->user()->etablissement;
        $menus = $etab ? $etab->menus()->with('categoriesRacines.articles.options', 'categoriesRacines.sousCategories.articles')->get() : collect();
        return view('admin.menus.index', compact('menus', 'etab'));
    }

    public function store(Request $request)
    {
        $request->validate(['titre' => 'required|string|max:255']);
        $etab = auth()->user()->etablissement;
        $etab->menus()->create(['titre' => $request->titre, 'actif' => false]);
        return back()->with('success', 'Menu créé.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu supprimé.');
    }

    public function toggleActif(Menu $menu)
    {
        $menu->etablissement->menus()->update(['actif' => false]);
        $menu->update(['actif' => true]);
        return back()->with('success', 'Menu activé.');
    }
}