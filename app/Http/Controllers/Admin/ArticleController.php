<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nom', 'prix', 'categorie_id', 'description']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $data['ordre'] = Article::where('categorie_id', $request->categorie_id)->max('ordre') + 1;
        Article::create($data);
        return back()->with('success', 'Article créé.');
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'nom'  => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['nom', 'prix', 'description']);
        $data['disponible'] = $request->has('disponible');
        if ($request->hasFile('image')) {
            if ($article->image) Storage::disk('public')->delete($article->image);
            $data['image'] = $request->file('image')->store('articles', 'public');
        }
        $article->update($data);
        return back()->with('success', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        if ($article->image) Storage::disk('public')->delete($article->image);
        $article->delete();
        return back()->with('success', 'Article supprimé.');
    }

    public function toggle(Article $article)
    {
        $article->update(['disponible' => !$article->disponible]);
        return response()->json(['success' => true]);
    }

    public function storeOption(Request $request, Article $article)
    {
        $request->validate([
            'nom'                 => 'required|string|max:255',
            'groupe'              => 'nullable|string|max:100',
            'prix_supplementaire' => 'required|numeric|min:0',
        ]);
        $article->options()->create([
            'nom'                 => $request->nom,
            'groupe'              => $request->groupe,
            'prix_supplementaire' => $request->prix_supplementaire,
            'obligatoire'         => $request->boolean('obligatoire'),
        ]);
        return back()->with('success', 'Option ajoutée.');
    }

    public function destroyOption(Option $option)
    {
        $option->delete();
        return back()->with('success', 'Option supprimée.');
    }
}