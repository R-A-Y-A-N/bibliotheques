<?php

namespace app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Livre;
use Illuminate\Support\Facades\Auth;
use App\Models\Emprunt;
use App\Models\Penalite;
use App\Models\User;



class EmpruntController extends Controller
{


public function store(Request $request)
{
    $livre = Livre::findOrFail($request->livre_id);

    if ($livre->stock <= 0) {
        return back()->with('error', 'Livre indisponible');
    }

    // 🔻 Décrémenter stock
    $livre->decrement('stock');

    // 🔥 Créer l'emprunt
    Emprunt::create([
        'user_id' => Auth::id(),
        'livre_id' => $livre->id,
        'date_emprunt' => now(),
        'date_retour_prevue' => now()->addDays(10), // ✅ 10 jours comme tu veux
    ]);

    return back()->with('success', 'Livre emprunté avec succès');
}


public function retourner($id)
{
    $emprunt = Emprunt::findOrFail($id);

    // 🔻 éviter double retour
    if ($emprunt->date_retour_reelle) {
        return back()->with('error', 'Livre déjà retourné');
    }

    // 🔻 marquer retour
    $emprunt->date_retour_reelle = now();
    $emprunt->save();

    // 🔻 remettre stock
    $emprunt->livre->increment('stock');

    // 🔥 calcul automatique (tu l’as déjà 👇)
    $joursRetard = $emprunt->calculerJoursRetard();

    if ($joursRetard > 0 && !$emprunt->penalite) {
        Penalite::create([
            'emprunt_id' => $emprunt->id,
            'montant' => $emprunt->calculerPenalite(),
            'payee' => false
        ]);
    }

    return back()->with('success', 'Livre retourné');
}


private function verifierEtMettreAJourPenalites()
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
public function showUserEmprunts($id)
{
    $user = User::with(['emprunts.livre'])->findOrFail($id);

    return view('users.emprunts', compact('user'));
}

public function allEmprunts()
{
    $emprunts = Emprunt::with(['user', 'livre'])->latest()->get();

    return view('admin.emprunts', compact('emprunts'));
}
public function dashboard()
{
    $totalLivres = Livre::count();
    $empruntsEnCours = Emprunt::whereNull('date_retour')->count();
    $retards = Emprunt::whereNull('date_retour')
        ->where('created_at', '<', now()->subDays(14))
        ->count();

    $totalPenalites = Penalite::sum('montant');

    $retardsList = Emprunt::with(['user', 'livre'])
        ->whereNull('date_retour')
        ->where('created_at', '<', now()->subDays(14))
        ->latest()
        ->get();

    return view('admin', compact(
        'totalLivres',
        'empruntsEnCours',
        'retards',
        'totalPenalites',
        'retardsList'
    ));
}
}
