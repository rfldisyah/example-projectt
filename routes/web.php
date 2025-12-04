<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\DiaryAnalysisController;
use App\Http\Controllers\TestimonyController;
use App\Http\Controllers\AdminDashboardController; // Controller Baru untuk Admin
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIK ---
Route::get('/', function () {
    // Menampilkan testimoni di landing page (Read Only)
    $testimonials = \App\Models\Testimony::latest()->take(3)->get();
    return view('welcome', compact('testimonials'));
})->name('home');


// --- 2. AUTHENTICATION ---
require __DIR__.'/auth.php';


// --- 3. LOGIC PENGARAHAN DASHBOARD ---
// Rute ini menentukan kemana user diarahkan saat membuka '/dashboard'
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- 4. GROUP: USER BIASA (Privacy Protected) ---
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {

    // A. Dashboard User
    // Menampilkan ringkasan diary milik SENDIRI
    Route::get('/user/dashboard', [DiaryController::class, 'index'])->name('user.dashboard');

    // B. Manajemen Diary (CRUD Lengkap - Milik Sendiri)
    Route::resource('diaries', DiaryController::class);

    // C. Manajemen Hasil Analisis (Hanya Lihat & Hapus)
    Route::resource('analysis', DiaryAnalysisController::class)
        ->only(['index', 'show', 'destroy']);

    // D. Testimonial (User hanya bisa Membuat)
    Route::resource('testimonials', TestimonyController::class)
        ->only(['create', 'store']);

    // E. Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// --- 5. GROUP: ADMIN (Statistik Only & Manage Testimoni) ---
// Pastikan Anda membuat middleware 'role' atau cek logic manual
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // A. Dashboard Admin (Hanya Statistik/Count)
    // Tidak boleh menggunakan DiaryController::index karena itu menampilkan isi diary
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // B. Manajemen Testimonial (Full kecuali Create)
    // Admin tugasnya mengedit, menghapus, atau melihat daftar testimoni
    Route::resource('testimony', TestimonyController::class)->except(['create', 'store']); // Create & Store ada di sisi User

});
