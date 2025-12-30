<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\HistoryController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\MateriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Student\ChatController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
| Redirect berdasarkan status login & role
*/
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }

    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH (GUEST ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| LOGOUT (AUTH ONLY)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */

    // Dashboard
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('dashboard');

    // Course detail
    Route::get('/course/{id}', [StudentController::class, 'courseShow'])
        ->name('course.show');

    // CLO detail + chatbot
    Route::get('/course/{courseId}/clo/{cloId}', [StudentController::class, 'courseClo'])
        ->name('course.clo');

    // Chat history session
    Route::get('/chat/{session}', function ($session) {
        return view('student.chat', [
            'session' => $session,
        ]);
    })->name('chat.show');

    // History
    Route::get('/history', [HistoryController::class, 'index'])
        ->name('history');
    Route::post('/history/{id}/ask', [ChatController::class, 'askFromSession'])->name('history.ask');

    Route::get('/history/{id}', [HistoryController::class, 'show'])
        ->name('history.show');
    // chat sekarang
    

Route::post('/chat/{courseId}/clo/{cloId}/ask', [ChatController::class, 'ask'])
    ->name('chat.ask');

    // Profile
    Route::get('/profile', function () {
        return view('student.profile');
    })->name('profile');


    /*
    |--------------------------------------------------------------------------
    | DOSEN
    |--------------------------------------------------------------------------
    */
    Route::prefix('dosen')->name('dosen.')->group(function () {

        Route::get('/dashboard', [DosenDashboardController::class, 'index'])
            ->name('dashboard');

        // Materi
        Route::get('/materi/create', [MateriController::class, 'create'])
            ->name('materi.create');

        Route::post('/materi', [MateriController::class, 'store'])
            ->name('materi.store');

        Route::get('/materi/{id}/edit', [MateriController::class, 'edit'])
            ->name('materi.edit');

        Route::put('/materi/{id}', [MateriController::class, 'update'])
            ->name('materi.update');

        Route::delete('/materi/{id}', [MateriController::class, 'destroy'])
            ->name('materi.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // CSRF token endpoint
        Route::get('/csrf-token', function () {
            return response()->json(['csrf_token' => csrf_token()]);
        })->name('csrf-token');
        Route::get('/course/create', [AdminController::class, 'createCourse'])
        ->name('course.create');
        Route::post('/course', [AdminController::class, 'storeCourse'])
        ->name('course.store');

        // Course detail (SATU-SATUNYA ROUTE COURSE ADMIN)
        Route::get('/course/{id}', [AdminController::class, 'showCourse'])
            ->name('course.show');
        
    Route::get('/courses/{id}/json', [AdminController::class, 'courseDetail'])
        ->name('course.json');

    Route::get('/lecturers', [AdminController::class, 'searchLecturers'])
        ->name('lecturers.search');

    Route::post('/coordinator', [AdminController::class, 'assignCoordinator'])
        ->name('coordinator.assign');
    Route::get('/course/{courseId}/clo/{cloId}/materi/create', [AdminController::class, 'createMateri'])
        ->name('materi.create');


    // OPTIONAL tapi sangat dibutuhkan untuk "hapus dosen dari course"
    Route::delete('/course/{courseId}/coordinator/{userId}', [AdminController::class, 'removeCoordinator'])
        ->name('coordinator.remove');

        Route::post('/course/{id}/clos', [AdminController::class, 'addClo'])
    ->name('clo.store');

        // Update summaries
        Route::patch('/course/{id}/summary', [AdminController::class, 'updateCourseSummary']);
        Route::patch('/clo/{id}/summary', [AdminController::class, 'updateCloSummary']);

        // Materials
        Route::post('/clo/{id}/materials', [AdminController::class, 'addMaterial']);
        Route::post('/material/{id}/upload', [AdminController::class, 'uploadMaterialPdf']);
        // Materi edit page (pakai view yang sama)
// ===== Materi Edit page
Route::get('/material/{id}/edit', [AdminController::class, 'editMateri'])
    ->name('materi.edit');

// ===== Update materi (judul/desc)
Route::patch('/material/{id}', [AdminController::class, 'updateMaterial'])
    ->name('materi.update');

// ===== Hapus materi (opsional kalau mau)
Route::delete('/material/{id}', [AdminController::class, 'deleteMaterial'])
    ->name('materi.destroy');

// ===== Hapus file PDF dari materi
Route::delete('/material-file/{id}', [AdminController::class, 'deleteMaterialFile'])
    ->name('material-file.destroy');
    });
    

});
