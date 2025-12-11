<?php

use Illuminate\Support\Facades\Route;

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

// Profile
Route::get('/profile', function () {
    return view('student.profile');
})->name('profile');

// Logout (sementara cuma clear token session kalau nanti dipakai)
Route::post('/logout', function () {
    session()->forget('auth_token');
    return redirect()->route('login');
})->name('logout');
