<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'nom', 'groupe', 'prix_supplementaire',
        'obligatoire', 'article_id'
    ];

    protected $casts = [
        'prix_supplementaire' => 'decimal:2',
        'obligatoire'         => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}