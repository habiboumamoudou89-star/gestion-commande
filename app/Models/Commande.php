<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'statut', 'total', 'qr_confirmation', 'notes', 'table_id'
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }

    public function items()
    {
        return $this->hasMany(CommandeItem::class);
    }

    public function getBadgeClassAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'bg-warning text-dark',
            'en_cours'   => 'bg-info text-white',
            'prete'      => 'bg-success text-white',
            'servie'     => 'bg-secondary text-white',
            'annulee'    => 'bg-danger text-white',
        };
    }

    public function getLabelStatutAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'en_cours'   => 'En cours',
            'prete'      => 'Prête',
            'servie'     => 'Servie',
            'annulee'    => 'Annulée',
            default      => $this->statut,
        };
    }
}