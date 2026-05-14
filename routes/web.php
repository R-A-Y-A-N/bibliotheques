<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LivreController;
use App\Http\Controllers\Web\EmpruntController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ResumeController;
// Affichage du formulaire
Route::get('/resume', [ResumeController::class, 'index'])->name('resume.index');

// Traitement de la requête de résumé
Route::post('/resume', [ResumeController::class, 'generer'])->name('resume.generer');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('livres', LivreController::class);

// Dashboard
Route::get('/dashboard', [LivreController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');


// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/users/{id}/emprunts', [EmpruntController::class, 'showUserEmprunts']);
Route::get('/users/{id}/emprunts', [EmpruntController::class, 'showUserEmprunts'])->name('emprunts.index');

// Routes pour les emprunts
Route::post('/emprunts', [EmpruntController::class, 'store'])->name('emprunts.store');
Route::put('/emprunts/{id}/retour', [EmpruntController::class, 'retour'])->name('emprunts.retour');
Route::get('/mes-emprunts', [EmpruntController::class, 'mesEmprunts'])->name('emprunts.mes-emprunts');
Route::post('/penalites/{id}/payer', [EmpruntController::class, 'payerPenalite'])->name('penalites.payer');
Route::get('/admin/emprunts', [EmpruntController::class, 'allEmprunts'])
    ->name('admin.emprunts');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/penalites', [LivreController::class, 'indexc'])->name('penalites.index');
Route::post('/notifications/read', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('notifications.read');
require __DIR__ . '/auth.php';
