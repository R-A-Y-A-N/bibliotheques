<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalite extends Model
{
    protected $table = 'penalites';

    protected $fillable = [
        'emprunt_id',
        'montant',
        'payee'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'payee' => 'boolean'
    ];

    public function emprunt(): BelongsTo
    {
        return $this->belongsTo(Emprunt::class);
    }

    /**
     * Vérifier si la pénalité est impayée
     */
    public function estImpayee(): bool
    {
        return !$this->payee;
    }
}
