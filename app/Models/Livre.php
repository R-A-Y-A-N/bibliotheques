<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livre extends Model
{
    use HasFactory;
    protected $table = 'livres';

        protected $fillable = [
        'titre',
        'description',
        'stock',
        'image',
        'nombre_exmp',
        'auteur_id',
        'categorie_id'
    ];
  // Relation avec Auteur
    public function auteur()
    {
        return $this->belongsTo(Auteur::class);
    }

    // Relation avec Categorie
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

}
