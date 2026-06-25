<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'nom', 'description', 'prix', 'image',
        'disponible', 'ordre', 'categorie_id'
    ];

    protected $casts = [
        'prix'       => 'decimal:2',
        'disponible' => 'boolean',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }



    public function getImageUrlAttribute()
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }

    // Images placeholder par catégorie selon le nom
    $nom = strtolower($this->nom);
    if (str_contains($nom, 'couscous') || str_contains($nom, 'tajine')) {
        return 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&h=300&fit=crop';
    }
    if (str_contains($nom, 'thé') || str_contains($nom, 'jus') || str_contains($nom, 'boisson')) {
        return 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&h=300&fit=crop';
    }
    if (str_contains($nom, 'dessert') || str_contains($nom, 'gazelle') || str_contains($nom, 'pastilla')) {
        return 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop';
    }

    return 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400&h=300&fit=crop';
}
}
