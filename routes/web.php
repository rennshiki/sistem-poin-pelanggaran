<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\ViolationController as GuruViolationController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => abort(403),
        };
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('violation-types', \App\Http\Controllers\Admin\ViolationTypeController::class)->except(['show']);
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/violations/students', [GuruViolationController::class, 'students'])->name('violations.students');
    Route::get('/violations/create', [GuruViolationController::class, 'create'])->name('violations.create');
    Route::post('/violations', [GuruViolationController::class, 'store'])->name('violations.store');
    Route::get('/violations', [GuruViolationController::class, 'index'])->name('violations.index');
    Route::get('/violations/{violation}/photo', [GuruViolationController::class, 'photo'])->name('violations.photo');
    Route::delete('/violations/{violation}', [GuruViolationController::class, 'destroy'])->name('violations.destroy');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

require __DIR__.'/auth.php';
