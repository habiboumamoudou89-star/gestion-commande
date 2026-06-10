<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['titre', 'actif', 'etablissement_id'];

    protected $casts = ['actif' => 'boolean'];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function categories()
    {
        return $this->hasMany(Categorie::class)->orderBy('ordre');
    }

    public function categoriesRacines()
    {
        return $this->hasMany(Categorie::class)
            ->whereNull('parent_id')
            ->orderBy('ordre');
    }
}
