<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    protected $table = 'tables';

    protected $fillable = ['numero', 'qr_token', 'qr_code', 'etablissement_id'];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'table_id');
    }

    public function commandeEnCours()
    {
        return $this->hasOne(Commande::class, 'table_id')
            ->whereIn('statut', ['en_attente', 'en_cours', 'prete'])
            ->latest();
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($table) {
            if (empty($table->qr_token)) {
                $table->qr_token = Str::uuid();
            }
        });
    }
}
