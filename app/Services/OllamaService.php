<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    /**
     * URL de l'API Ollama locale.
     */
    private string $baseUrl;

    /**
     * Modèle Ollama à utiliser.
     */
    private string $model;

    public function __construct()
    {
        $this->baseUrl = config('ollama.url', 'http://localhost:11434');
        $this->model   = config('ollama.model', 'llama3');
    }

    /**
     * Génère un résumé d'un livre via Ollama.
     *
     * @param  string  $titre       Titre du livre
     * @param  string  $auteur      Auteur du livre
     * @param  string  $description Description ou synopsis
     * @param  string  $categorie   Catégorie / genre littéraire
     * @return string               Résumé généré
     *
     * @throws \RuntimeException Si Ollama est indisponible ou retourne une erreur
     */
    public function genererResume(
        string $titre,
        string $auteur,
        string $description,
        string $categorie
    ): string {
        // Construction du prompt optimisé
        $prompt = $this->construirePrompt($titre, $auteur, $description, $categorie);

        try {
            $response = Http::timeout(120) // Ollama peut être lent sur CPU
                ->post("{$this->baseUrl}/api/generate", [
                    'model'  => $this->model,
                    'prompt' => $prompt,
                    'stream' => false, // On veut une réponse complète d'un coup
                ]);

            if ($response->failed()) {
                Log::error('Ollama API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                throw new \RuntimeException(
                    "Ollama a retourné une erreur HTTP {$response->status()}."
                );
            }

            $data = $response->json();

            // La réponse Ollama est dans la clé "response"
            if (empty($data['response'])) {
                throw new \RuntimeException('Ollama a retourné une réponse vide.');
            }

            return trim($data['response']);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Ollama unreachable', ['error' => $e->getMessage()]);
            throw new \RuntimeException(
                "Impossible de contacter Ollama. Vérifiez qu'il tourne sur {$this->baseUrl}."
            );
        }
    }

    /**
     * Construit un prompt structuré et optimisé pour obtenir un bon résumé.
     */
    private function construirePrompt(
        string $titre,
        string $auteur,
        string $description,
        string $categorie
    ): string {
        return <<<PROMPT
Tu es un assistant littéraire expert. Génère un résumé clair, engageant et structuré du livre suivant.

**Informations sur le livre :**
- Titre : {$titre}
- Auteur : {$auteur}
- Catégorie : {$categorie}
- Synopsis / description : {$description}

**Instructions :**
1. Rédige un résumé en français de 150 à 250 mots.
2. Présente les thèmes principaux, le ton de l'œuvre et ce qui rend ce livre unique.
3. Termine par une phrase indiquant à quel type de lecteur ce livre s'adresse.
4. Ne commence pas par "Voici un résumé" — va directement au contenu.
5. Utilise un style fluide et accessible.

Résumé :
PROMPT;
    }
}