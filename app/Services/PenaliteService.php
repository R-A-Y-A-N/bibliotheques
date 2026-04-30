<?php

namespace App\Services;

use App\Models\Emprunt;
use App\Models\Penalite;

class PenaliteService
{
    public function verifierEtMettreAJour()
    {
        $emprunts = Emprunt::all();

        foreach ($emprunts as $emprunt) {

            $joursRetard = $emprunt->calculerJoursRetard();

            if ($joursRetard > 0) {

                $penalite = $emprunt->penalite;

                if ($penalite) {
                    // 🔁 UPDATE
                    $penalite->update([
                        'montant' => $emprunt->calculerPenalite()
                    ]);
                } else {
                    // ➕ CREATE
                    Penalite::create([
                        'emprunt_id' => $emprunt->id,
                        'montant' => $emprunt->calculerPenalite(),
                        'payee' => false
                    ]);
                }
            }
        }
    }
}
