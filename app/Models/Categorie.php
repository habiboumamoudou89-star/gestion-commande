<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['nom', 'image', 'parent_id', 'ordre', 'menu_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    public function sousCategories()
    {
        return $this->hasMany(Categorie::class, 'parent_id')->orderBy('ordre');
    }

    public function articles()
    {
        return $this->hasMany(Article::class)->orderBy('ordre');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
