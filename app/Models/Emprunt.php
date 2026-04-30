<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    protected $table = 'emprunts';

    protected $fillable = [
        'user_id',
        'livre_id',
        'date_emprunt',
        'date_retour_prevue',
        'date_retour_reelle'
    ];

    protected $casts = [
        'date_emprunt' => 'date',
        'date_retour_prevue' => 'date',
        'date_retour_reelle' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function penalite()
    {
        return $this->hasOne(Penalite::class);
    }

    /**
     * Calculer les jours de retard
     */
    public function calculerJoursRetard(): int
    {
        $dateReference = $this->date_retour_reelle ?? now();

        if ($dateReference <= $this->date_retour_prevue) {
            return 0;
        }

        return $dateReference->diffInDays($this->date_retour_prevue);
    }

    /**
     * Calculer le montant de la pénalité (0.50€ par jour de retard)
     */
    public function calculerPenalite(): float
    {
        $joursRetard = $this->calculerJoursRetard();
        return $joursRetard * 0.50;
    }

    /**
     * Vérifier si l'emprunt est en retard
     */
    public function estEnRetard(): bool
    {
        if ($this->date_retour_reelle) {
            return $this->date_retour_reelle > $this->date_retour_prevue;
        }
        return now() > $this->date_retour_prevue;
    }
}
