<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:255',
            'menu_id'   => 'required|exists:menus,id',
            'parent_id' => 'nullable|exists:categories,id',
            'image'     => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nom', 'menu_id', 'parent_id']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $data['ordre'] = Categorie::where('menu_id', $request->menu_id)->max('ordre') + 1;
        Categorie::create($data);
        return back()->with('success', 'Catégorie créée.');
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        $data = $request->only(['nom']);
        if ($request->hasFile('image')) {
            if ($categorie->image) Storage::disk('public')->delete($categorie->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $categorie->update($data);
        return back()->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}