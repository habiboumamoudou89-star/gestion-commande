<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeItem extends Model
{
    protected $fillable = [
        'quantite', 'prix_unitaire', 'commande_id', 'article_id'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'commande_item_option');
    }

    public function getSousTotalAttribute()
    {
        $optionsTotal = $this->options->sum('prix_supplementaire');
        return ($this->prix_unitaire + $optionsTotal) * $this->quantite;
    }
}