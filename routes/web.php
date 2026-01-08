<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacanteController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\CompararCandidatosController;
use App\Http\Controllers\ExportarCandidatosController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/dashboard', [VacanteController::class, 'index'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('vacantes.index');
Route::get('/vacantes/create', [VacanteController::class, 'create'])->middleware(['auth', 'verified'])->name('vacantes.create');
Route::get('/vacantes/{vacante}/edit', [VacanteController::class, 'edit'])->middleware(['auth', 'verified'])->name('vacantes.edit');
Route::get('/vacantes/{vacante}', [VacanteController::class, 'show'])->name('vacantes.show');
Route::get('/candidatos/{vacante}', [CandidatoController::class, 'index'])->name('candidatos.index');

// Comparar candidatos
Route::get('/candidatos/{vacante}/comparar', [CompararCandidatosController::class, 'index'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('candidatos.comparar');
Route::post('/candidatos/{vacante}/comparar', [CompararCandidatosController::class, 'comparar'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('candidatos.comparar.post');

// Exportar candidatos
Route::post('/candidatos/{vacante}/exportar/pdf', [ExportarCandidatosController::class, 'pdf'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('candidatos.exportar.pdf');
Route::post('/candidatos/{vacante}/exportar/excel', [ExportarCandidatosController::class, 'excel'])->middleware(['auth', 'verified', 'rol.reclutador'])->name('candidatos.exportar.excel');

// Notificaciones
Route::get('/notificaciones', NotificacionController::class)->middleware(['auth', 'verified', 'rol.reclutador'])->name('notificaciones');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
