<?php

use App\Http\Controllers\AntrianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AUTH\AuthController;
use App\Http\Controllers\HewanController;
use App\Http\Controllers\LayananController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Antrian utama
Route::get('/antrian', [AntrianController::class, 'index']);
Route::get('/antrian/user/{userId}', [AntrianController::class, 'userAntrian']);
Route::post('/antrian', [AntrianController::class, 'store']);
Route::put('/antrian/{id}', [AntrianController::class, 'update']);
Route::delete('/antrian/{id}', [AntrianController::class, 'destroy']);
Route::get('/antrian/{id}', [AntrianController::class, 'show']);
Route::delete('/antrian/{id}/hapus-admin', [AntrianController::class, 'destroyAdmin']);


// Proses admin
Route::post('/antrian/{id}/tangani', [AntrianController::class, 'tangani']);
Route::post('/antrian/{id}/selesai', [AntrianController::class, 'selesai']);

// Dashboard (3 card + history)
Route::get('/dashboard', [AntrianController::class, 'dashboard']);
// Hapus antrian history (ADMIN)
Route::delete('/antrian/history/{id}', [AntrianController::class, 'destroyHistory']);

// Untuk Admin CRUD
// Route Hewan
Route::get('/hewan', [HewanController::class, 'index']);
Route::post('/hewan', [HewanController::class, 'store']);
Route::get('/hewan/{id}', [HewanController::class, 'show']);
Route::post('/hewan/{id}', [HewanController::class, 'update']);
Route::delete('/hewan/{id}', [HewanController::class, 'destroy']);

//Route Layanan
Route::get('/layanan', [LayananController::class, 'index']);
Route::post('/layanan', [LayananController::class, 'store']);
Route::get('/layanan/{id}', [LayananController::class, 'show']);
Route::post('/layanan/{id}', [LayananController::class, 'update']);
Route::delete('/layanan/{id}', [LayananController::class, 'destroy']);

