<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class LivreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $livres = Livre::with(['auteur', 'categorie'])->paginate(8);

        return response()->json([
            'success' => true,
            'data' => $livres,
            'message' => 'Livres récupérés avec succès'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'stock' => 'required|integer|min:0',
                'nombre_exmp' => 'required|integer|min:0',
                'auteur_id' => 'required|exists:auteurs,id',
                'categorie_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            // 📸 upload image
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('livres', 'public');
            }

            $livre = Livre::create($data);

            return response()->json([
                'success' => true,
                'data' => $livre->load(['auteur', 'categorie']),
                'message' => 'Livre créé avec succès'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du livre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $livre = Livre::with(['auteur', 'categorie'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $livre,
                'message' => 'Livre récupéré avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livre non trouvé'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $livre = Livre::findOrFail($id);

            $data = $request->validate([
                'titre' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'stock' => 'sometimes|required|integer|min:0',
                'nombre_exmp' => 'sometimes|required|integer|min:0',
                'auteur_id' => 'sometimes|required|exists:auteurs,id',
                'categorie_id' => 'sometimes|required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            // 📸 nouvelle image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($livre->image) {
                    Storage::disk('public')->delete($livre->image);
                }
                $data['image'] = $request->file('image')->store('livres', 'public');
            }

            $livre->update($data);

            return response()->json([
                'success' => true,
                'data' => $livre->load(['auteur', 'categorie']),
                'message' => 'Livre mis à jour avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livre non trouvé'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du livre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $livre = Livre::findOrFail($id);

            // 🔥 supprimer l'image associée
            if ($livre->image) {
                Storage::disk('public')->delete($livre->image);
            }

            $livre->delete();

            return response()->json([
                'success' => true,
                'message' => 'Livre supprimé avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Livre non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du livre',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get books by author
     */
    public function getByAuthor($auteurId): JsonResponse
    {
        try {
            $auteur = Auteur::findOrFail($auteurId);
            $livres = $auteur->livres()->with('categorie')->paginate(8);

            return response()->json([
                'success' => true,
                'data' => $livres,
                'message' => 'Livres de l\'auteur récupérés avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Auteur non trouvé'
            ], 404);
        }
    }

    /**
     * Get books by category
     */
    public function getByCategory($categorieId): JsonResponse
    {
        try {
            $categorie = Categorie::findOrFail($categorieId);
            $livres = $categorie->livres()->with('auteur')->paginate(8);

            return response()->json([
                'success' => true,
                'data' => $livres,
                'message' => 'Livres de la catégorie récupérés avec succès'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée'
            ], 404);
        }
    }

    /**
     * Search books by title or description
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->get('q');

        $livres = Livre::with(['auteur', 'categorie'])
            ->where('titre', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(8);

        return response()->json([
            'success' => true,
            'data' => $livres,
            'message' => 'Résultats de recherche pour : ' . $query
        ], 200);
    }
}
