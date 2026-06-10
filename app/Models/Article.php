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
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/default-article.png');
    }
}
