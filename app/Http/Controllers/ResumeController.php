<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Services\OllamaService;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function __construct(private readonly OllamaService $ollamaService)
    {
        // Injection du service via le constructeur (résolu automatiquement par le container Laravel)
    }

    /**
     * Affiche le formulaire de recherche de résumé.
     */
    public function index()
    {
        return view('resume.index');
    }

    /**
     * Traite la requête, interroge la BDD puis Ollama, retourne le résumé.
     */
    public function generer(Request $request)
    {
        // --- Validation ---
        $request->validate([
            'titre' => ['required', 'string', 'min:2', 'max:255'],
        ], [
            'titre.required' => 'Veuillez saisir un titre de livre.',
            'titre.min'      => 'Le titre doit contenir au moins 2 caractères.',
        ]);

        $titreRecherche = $request->input('titre');

        // --- Recherche en base de données ---
        // Recherche insensible à la casse avec LIKE
        $livre = Livre::where('titre', 'LIKE', "%{$titreRecherche}%")
            ->first();

        if (!$livre) {
            return back()
                ->withInput()
                ->with('error', "Aucun livre trouvé pour « {$titreRecherche} ». Vérifiez l'orthographe.");
        }

        // --- Appel à Ollama ---
        try {
            $resume = $this->ollamaService->genererResume(
                titre:       $livre->titre,
                auteur:      $livre->auteur       ?? 'Auteur inconnu',
                description: $livre->description  ?? 'Aucune description disponible.',
                categorie:   $livre->categorie     ?? 'Non classé',
            );
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error_ollama', $e->getMessage());
        }

        // --- Retour à la vue avec les données ---
        return view('resume.index', [
            'livre'  => $livre,
            'resume' => $resume,
        ]);
    }
}