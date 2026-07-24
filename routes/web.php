<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Guest (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Wajib login (mentor & mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Satu pintu masuk dashboard — role diidentifikasi di DashboardController::index()
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----- Mentor-only -----
    Route::prefix('mentor')->name('mentor.')->middleware('authcheck:mentor')->group(function () {
        Route::get('/antrian', [MentorController::class, 'antrian'])->name('antrian');
        Route::post('/antrian/{logbook}/setujui', [MentorController::class, 'setujui'])->name('antrian.setujui');
        Route::post('/antrian/{logbook}/tolak', [MentorController::class, 'tolak'])->name('antrian.tolak');
        Route::get('/mahasiswa', [MentorController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/riwayat/{student}', [MentorController::class, 'riwayat'])->name('riwayat');
        Route::get('/pengajuan', [MentorController::class, 'pengajuan'])->name('pengajuan');
        Route::post('/pengajuan/{bimbinganRequest}/setujui', [MentorController::class, 'setujuiPengajuan'])->name('pengajuan.setujui');
        Route::post('/pengajuan/{bimbinganRequest}/tolak', [MentorController::class, 'tolakPengajuan'])->name('pengajuan.tolak');

    });

    // ----- Mahasiswa-only -----
    Route::middleware('authcheck:mahasiswa')->group(function () {
        Route::get('/logbook', [StudentController::class, 'index'])->name('logbook.index');
        Route::post('/logbook', [StudentController::class, 'store'])->name('logbook.store');
        Route::put('/logbook/{logbook}', [StudentController::class, 'update'])->name('logbook.update');
        Route::delete('/logbook/{logbook}', [StudentController::class, 'destroy'])->name('logbook.destroy');
        Route::delete('/logbook/images/{image}', [StudentController::class, 'deleteImage'])->name('logbook.images.destroy');
        Route::delete('/logbook/documents/{document}', [StudentController::class, 'deleteDocument'])->name('logbook.documents.destroy');
        Route::get('/riwayat', [StudentController::class, 'riwayat'])->name('riwayat');
        Route::get('/pilih-mentor', [StudentController::class, 'pilihMentor'])->name('pilih-mentor.index');
        Route::post('/pilih-mentor/{mentor}/ajukan', [StudentController::class, 'ajukanMentor'])->name('pilih-mentor.ajukan');

    });

    // ----- Bersama (mentor & mahasiswa) -----
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');

    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});
