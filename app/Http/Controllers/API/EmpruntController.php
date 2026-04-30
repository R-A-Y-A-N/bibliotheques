<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Livre;
use App\Models\Emprunt;
use App\Models\Penalite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmpruntController extends Controller
{
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function isAdmin(): bool
    {
        $user = Auth::user();
        // Adaptez cette condition selon votre structure de base de données
        return $user && (
            $user->role === 'admin' ||
            $user->is_admin == 1 ||
            $user->type === 'admin'
        );
    }

    /**
     * Emprunter un livre
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'livre_id' => 'required|exists:livres,id'
            ]);

            $livre = Livre::findOrFail($request->livre_id);

            if ($livre->stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Livre indisponible pour le moment'
                ], 400);
            }

            // Vérifier si l'utilisateur a déjà emprunté ce livre non retourné
            $existingEmprunt = Emprunt::where('user_id', Auth::id())
                ->where('livre_id', $livre->id)
                ->whereNull('date_retour_reelle')
                ->first();

            if ($existingEmprunt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà emprunté ce livre et ne l\'avez pas encore retourné'
                ], 400);
            }

            DB::beginTransaction();

            // Décrémenter stock
            $livre->decrement('stock');

            // Créer l'emprunt
            $emprunt = Emprunt::create([
                'user_id' => Auth::id(),
                'livre_id' => $livre->id,
                'date_emprunt' => now(),
                'date_retour_prevue' => now()->addDays(10),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $emprunt->load(['user', 'livre']),
                'message' => 'Livre emprunté avec succès. Date de retour prévue: ' . $emprunt->date_retour_prevue->format('d/m/Y')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'emprunt',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retourner un livre
     */
    public function retourner($id): JsonResponse
    {
        try {
            $emprunt = Emprunt::findOrFail($id);

            // Vérifier si l'utilisateur est autorisé
            if ($emprunt->user_id !== Auth::id() && !$this->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à retourner cet emprunt'
                ], 403);
            }

            // Éviter double retour
            if ($emprunt->date_retour_reelle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce livre a déjà été retourné'
                ], 400);
            }

            DB::beginTransaction();

            // Marquer retour
            $emprunt->date_retour_reelle = now();
            $emprunt->save();

            // Remettre stock
            $emprunt->livre->increment('stock');

            // Calculer pénalité si retard
            $joursRetard = $emprunt->calculerJoursRetard();
            $penaliteData = null;

            if ($joursRetard > 0 && !$emprunt->penalite) {
                $penalite = Penalite::create([
                    'emprunt_id' => $emprunt->id,
                    'montant' => $emprunt->calculerPenalite(),
                    'payee' => false
                ]);
                $penaliteData = $penalite;
            }

            DB::commit();

            $response = [
                'success' => true,
                'data' => [
                    'emprunt' => $emprunt->load(['livre']),
                    'jours_retard' => $joursRetard,
                    'penalite' => $penaliteData
                ],
                'message' => 'Livre retourné avec succès'
            ];

            if ($joursRetard > 0) {
                $response['message'] .= '. Retard de ' . $joursRetard . ' jour(s). Pénalité: ' . ($penaliteData->montant ?? $emprunt->calculerPenalite()) . ' FCFA';
            }

            return response()->json($response, 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Emprunt non trouvé'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du retour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les emprunts de l'utilisateur connecté
     */
    public function mesEmprunts(): JsonResponse
    {
        try {
            $emprunts = Emprunt::with(['livre.auteur', 'livre.categorie', 'penalite'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Calculer les statistiques
            $stats = [
                'total_emprunts' => $emprunts->total(),
                'emprunts_en_cours' => Emprunt::where('user_id', Auth::id())
                    ->whereNull('date_retour_reelle')
                    ->count(),
                'emprunts_retournes' => Emprunt::where('user_id', Auth::id())
                    ->whereNotNull('date_retour_reelle')
                    ->count(),
                'penalites_total' => Penalite::whereHas('emprunt', function($q) {
                    $q->where('user_id', Auth::id());
                })->sum('montant'),
                'penalites_impayees' => Penalite::whereHas('emprunt', function($q) {
                    $q->where('user_id', Auth::id());
                })->where('payee', false)->sum('montant')
            ];

            return response()->json([
                'success' => true,
                'data' => $emprunts,
                'stats' => $stats,
                'message' => 'Emprunts récupérés avec succès'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des emprunts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les emprunts d'un utilisateur spécifique (Admin seulement)
     */
    public function showUserEmprunts($id): JsonResponse
    {
        try {
            // Vérifier si l'utilisateur est admin
            if (!$this->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Droits administrateur requis.'
                ], 403);
            }

            $user = User::with(['emprunts.livre', 'emprunts.penalite'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'emprunts' => $user->emprunts()->with(['livre'])->latest()->paginate(10)
                ],
                'message' => 'Emprunts de l\'utilisateur récupérés avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des emprunts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer tous les emprunts (Admin seulement)
     */
    public function allEmprunts(): JsonResponse
    {
        try {
            // Vérifier si l'utilisateur est admin
            if (!$this->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Droits administrateur requis.'
                ], 403);
            }

            $emprunts = Emprunt::with(['user', 'livre.auteur', 'livre.categorie', 'penalite'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $stats = [
                'total' => Emprunt::count(),
                'en_cours' => Emprunt::whereNull('date_retour_reelle')->count(),
                'termines' => Emprunt::whereNotNull('date_retour_reelle')->count(),
                'en_retard' => Emprunt::whereNull('date_retour_reelle')
                    ->where('date_retour_prevue', '<', now())
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $emprunts,
                'stats' => $stats,
                'message' => 'Tous les emprunts récupérés avec succès'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des emprunts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard statistiques (Admin seulement)
     */
    public function dashboard(): JsonResponse
    {
        try {
            // Vérifier si l'utilisateur est admin
            if (!$this->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Droits administrateur requis.'
                ], 403);
            }

            $totalLivres = Livre::count();
            $livresDisponibles = Livre::where('stock', '>', 0)->count();
            $livresEmpruntes = Livre::where('stock', 0)->count();

            $empruntsEnCours = Emprunt::whereNull('date_retour_reelle')->count();
            $retards = Emprunt::whereNull('date_retour_reelle')
                ->where('date_retour_prevue', '<', now())
                ->count();

            $totalPenalites = Penalite::sum('montant');
            $penalitesImpayees = Penalite::where('payee', false)->sum('montant');
            $penalitesPayees = Penalite::where('payee', true)->sum('montant');

            $retardsList = Emprunt::with(['user', 'livre'])
                ->whereNull('date_retour_reelle')
                ->where('date_retour_prevue', '<', now())
                ->orderBy('date_retour_prevue', 'asc')
                ->limit(10)
                ->get()
                ->map(function($emprunt) {
                    return [
                        'id' => $emprunt->id,
                        'user' => $emprunt->user->name,
                        'user_email' => $emprunt->user->email,
                        'livre' => $emprunt->livre->titre,
                        'date_emprunt' => $emprunt->date_emprunt->format('d/m/Y'),
                        'date_retour_prevue' => $emprunt->date_retour_prevue->format('d/m/Y'),
                        'jours_retard' => now()->diffInDays($emprunt->date_retour_prevue, false),
                        'penalite' => $emprunt->calculerPenalite()
                    ];
                });

            $empruntsParMois = Emprunt::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois', 'desc')
            ->limit(6)
            ->get();

            $data = [
                'statistiques' => [
                    'livres' => [
                        'total' => $totalLivres,
                        'disponibles' => $livresDisponibles,
                        'empruntes' => $livresEmpruntes
                    ],
                    'emprunts' => [
                        'en_cours' => $empruntsEnCours,
                        'en_retard' => $retards
                    ],
                    'penalites' => [
                        'total' => $totalPenalites,
                        'impayees' => $penalitesImpayees,
                        'payees' => $penalitesPayees
                    ]
                ],
                'emprunts_en_retard' => $retardsList,
                'evolution_emprunts' => $empruntsParMois
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Dashboard récupéré avec succès'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour les pénalités (cron job ou manuel)
     */
    public function updatePenalites(): JsonResponse
    {
        try {
            // Vérifier si l'utilisateur est admin
            if (!$this->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé. Droits administrateur requis.'
                ], 403);
            }

            $emprunts = Emprunt::whereNull('date_retour_reelle')
                ->where('date_retour_prevue', '<', now())
                ->get();

            $updated = 0;
            $created = 0;

            foreach ($emprunts as $emprunt) {
                $joursRetard = $emprunt->calculerJoursRetard();

                if ($joursRetard > 0) {
                    $penalite = $emprunt->penalite;

                    if ($penalite) {
                        $penalite->update([
                            'montant' => $emprunt->calculerPenalite()
                        ]);
                        $updated++;
                    } else {
                        Penalite::create([
                            'emprunt_id' => $emprunt->id,
                            'montant' => $emprunt->calculerPenalite(),
                            'payee' => false
                        ]);
                        $created++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'penalites_creees' => $created,
                    'penalites_mises_a_jour' => $updated,
                    'total_traites' => $emprunts->count()
                ],
                'message' => 'Mise à jour des pénalités effectuée'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des pénalités',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
