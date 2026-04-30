<?php

namespace App\Http\Controllers;

use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Penalite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques principales
        $totalLivres = Livre::count();
        $livresDisponibles = Livre::where('stock', '>', 0)->count();
        $livresEmpruntes = Emprunt::whereNull('date_retour_reelle')->count();

        $retardsEnCours = Emprunt::whereNull('date_retour_reelle')
            ->where('date_retour_prevue', '<', Carbon::today())
            ->count();

        $totalPenalitesNonPayees = Penalite::where('payee', 0)->sum('montant');
        $totalPenalitesPayees = Penalite::where('payee', 1)->sum('montant');
        $totalPenalitesGeneral = Penalite::sum('montant');

        $nombreAdherents = User::where('role', 'adherent')->count();

        $empruntsCeMois = Emprunt::whereMonth('date_emprunt', Carbon::now()->month)
            ->whereYear('date_emprunt', Carbon::now()->year)
            ->count();

        // Top 5 livres
        $topLivres = Emprunt::select('livre_id', DB::raw('count(*) as total'))
            ->with('livre')
            ->groupBy('livre_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Emprunts en retard avec détails
        $empruntsEnRetard = Emprunt::whereNull('date_retour_reelle')
            ->where('date_retour_prevue', '<', Carbon::today())
            ->with(['user', 'livre'])
            ->orderBy('date_retour_prevue', 'asc')
            ->limit(10)
            ->get();

        // Graphique: Emprunts par mois (12 derniers mois)
        $empruntsParMois = Emprunt::select(
                DB::raw('YEAR(date_emprunt) as annee'),
                DB::raw('MONTH(date_emprunt) as mois'),
                DB::raw('count(*) as total')
            )
            ->where('date_emprunt', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->annee, $item->mois, 1);
                return [
                    'mois' => $date->translatedFormat('M Y'),
                    'total' => $item->total
                ];
            });

        // Graphique: Répartition par catégorie
        $statsParCategorie = Livre::select('categorie_id', DB::raw('count(*) as total'))
            ->with('categorie')
            ->groupBy('categorie_id')
            ->get();

        // Graphique: Évolution des retards
        $retardsParMois = Emprunt::select(
                DB::raw('YEAR(date_retour_prevue) as annee'),
                DB::raw('MONTH(date_retour_prevue) as mois'),
                DB::raw('count(*) as total')
            )
            ->whereNull('date_retour_reelle')
            ->where('date_retour_prevue', '<', Carbon::now())
            ->where('date_retour_prevue', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('annee', 'mois')
            ->orderBy('annee', 'asc')
            ->orderBy('mois', 'asc')
            ->get()
            ->map(function($item) {
                $date = Carbon::createFromDate($item->annee, $item->mois, 1);
                return [
                    'mois' => $date->translatedFormat('M Y'),
                    'total' => $item->total
                ];
            });

        // Graphique: Top 5 auteurs
        $topAuteurs = Livre::select('auteur_id', DB::raw('count(*) as total'))
            ->with('auteur')
            ->groupBy('auteur_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Taux de retour à temps
        $totalRetournes = Emprunt::whereNotNull('date_retour_reelle')->count();
        $retourDansDelai = Emprunt::whereNotNull('date_retour_reelle')
            ->whereRaw('date_retour_reelle <= date_retour_prevue')
            ->count();
        $tauxRetourDansDelai = $totalRetournes > 0
            ? round(($retourDansDelai / $totalRetournes) * 100, 2)
            : 0;

        // Statistiques supplémentaires
        $empruntsActifs = Emprunt::whereNull('date_retour_reelle')->count();
        $penalitesMoyenne = Penalite::avg('montant') ?? 0;

        return view('dashboard', compact(
            'totalLivres', 'livresDisponibles', 'livresEmpruntes',
            'retardsEnCours', 'totalPenalitesNonPayees', 'totalPenalitesPayees',
            'totalPenalitesGeneral', 'nombreAdherents', 'empruntsCeMois',
            'topLivres', 'empruntsEnRetard', 'statsParCategorie',
            'tauxRetourDansDelai', 'empruntsParMois', 'retardsParMois',
            'topAuteurs', 'empruntsActifs', 'penalitesMoyenne'
        ));
    }
}
