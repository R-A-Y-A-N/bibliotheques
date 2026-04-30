<?php

use App\Http\Controllers\Api\LivreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmpruntController;


Route::prefix('livres')->group(function () {
    // Routes CRUD de base
    Route::get('/', [LivreController::class, 'index']);           // GET /api/livres
    Route::post('/', [LivreController::class, 'store']);          // POST /api/livres
    Route::get('/{id}', [LivreController::class, 'show']);        // GET /api/livres/{id}
    Route::put('/{id}', [LivreController::class, 'update']);      // PUT /api/livres/{id}
    Route::patch('/{id}', [LivreController::class, 'update']);    // PATCH /api/livres/{id}
    Route::delete('/{id}', [LivreController::class, 'destroy']);  // DELETE /api/livres/{id}

    // Routes supplémentaires
    Route::get('/auteur/{auteurId}', [LivreController::class, 'getByAuthor']);     // GET /api/livres/auteur/{auteurId}
    Route::get('/categorie/{categorieId}', [LivreController::class, 'getByCategory']); // GET /api/livres/categorie/{categorieId}
    Route::get('/search', [LivreController::class, 'search']);    // GET /api/livres/search?q=terme


});

Route::prefix('emprunts')->group(function () {
    // Routes utilisateur
    Route::post('/', [EmpruntController::class, 'store']);                    // POST /api/emprunts
    Route::put('/{id}/retourner', [EmpruntController::class, 'retourner']);   // PUT /api/emprunts/{id}/retourner
    Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts']);   // GET /api/emprunts/mes-emprunts

    // Routes admin
    Route::get('/all', [EmpruntController::class, 'allEmprunts']);            // GET /api/emprunts/all
    Route::get('/dashboard', [EmpruntController::class, 'dashboard']);        // GET /api/emprunts/dashboard
    Route::get('/user/{id}', [EmpruntController::class, 'showUserEmprunts']); // GET /api/emprunts/user/{id}
    Route::post('/update-penalites', [EmpruntController::class, 'updatePenalites']); // POST /api/emprunts/update-penalites
});
