<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\HistoryController;

/*
|--------------------------------------------------------------------------
| Web Routes (Frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication pages (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
});

// ------------------ STUDENT PAGES (TANPA AUTH DULU) ------------------

// Dashboard
Route::get('/dashboard', function () {
    return view('student.dashboard');
})->name('dashboard');

// Course detail
Route::get('/course/{course}', function ($course) {
    return view('student.course-detail', [
        'course'        => (int) $course,
        'currentCourse' => (int) $course,
    ]);
})->name('course.show');

// CLO detail + chat
Route::get('/course/{course}/clo/{clo}', function ($course, $clo) {
    return view('student.chat', [
        'course'        => (int) $course,
        'clo'           => (int) $clo,
        'currentCourse' => (int) $course,
        'currentClo'    => (int) $clo,
    ]);
})->name('course.clo');

// Chat session from history (dummy)
Route::get('/chat/{session}', function ($session) {
    return view('student.chat', [
        'course'        => 4,
        'clo'           => 1,
        'currentCourse' => 4,
        'currentClo'    => 1,
        'session'       => $session,
    ]);
})->name('chat.show');

// History
Route::get('/history', function () {
    return view('student.history');
})->name('history');

// Profile (gada sebenernya wkwk)
Route::get('/profile', function () {
    return view('student.profile');
})->name('profile');

// Logout (sementara cuma clear token session kalau nanti dipakai)
Route::post('/logout', function () {
    session()->forget('auth_token');
    return redirect()->route('login');
})->name('logout');

Route::get('/history', [HistoryController::class, 'index'])->name('history');
Route::get('/history/{id}', [HistoryController::class, 'show'])->name('history.show');


// ------------------ DOSEN PAGES (TANPA AUTH DULU) ------------------

use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\MateriController;

Route::prefix('dosen')->name('dosen.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CREATE
    Route::get('/materi/create', [MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi', [MateriController::class, 'store'])->name('materi.store');

    // EDIT (pages only)
    Route::get('/materi/{id}/edit', [MateriController::class, 'edit'])->name('materi.edit');

    // UPDATE (nanti DB)
    Route::put('/materi/{id}', [MateriController::class, 'update'])->name('materi.update');

    // DELETE (nanti DB, bisa tetap tombol modal sekarang)
    Route::delete('/materi/{id}', [MateriController::class, 'destroy'])->name('materi.destroy');
});

// ------------------ ADMIN PAGES (TANPA AUTH DULU) ------------------
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LearningPathController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    // ✅ COURSE DETAIL
    Route::get('/course/{id}', function ($id) {
        return view('admin.course', [
            'currentCourse' => (int) $id
        ]);
    })->name('course.show');

});




