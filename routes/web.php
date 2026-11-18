<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseHistoryController;
use App\Http\Controllers\MyExerciseHistoryController;
use App\Http\Controllers\PatientController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Data Pasien
    Route::resource('patients', PatientController::class);

    // CRUD Riwayat Latihan Rehabilitasi (Admin)
    Route::resource('exercise-histories', ExerciseHistoryController::class)
        ->parameters(['exercise-histories' => 'exerciseHistory']);

    // Riwayat Latihan Rehabilitasi Milik Pasien
    Route::get('/riwayat-latihan-saya', [MyExerciseHistoryController::class, 'index'])
        ->name('my-exercise-histories.index');

    Route::get('/riwayat-latihan-saya/tambah', [MyExerciseHistoryController::class, 'create'])
        ->name('my-exercise-histories.create');

    Route::post('/riwayat-latihan-saya', [MyExerciseHistoryController::class, 'store'])
        ->name('my-exercise-histories.store');

    Route::get('/riwayat-latihan-saya/{exerciseHistory}', [MyExerciseHistoryController::class, 'show'])
        ->name('my-exercise-histories.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
