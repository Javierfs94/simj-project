<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/users/list', [UserController::class, 'list']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class)->middleware('can:admin-only');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/calendar', [TaskController::class, 'calendar'])->middleware('auth')->name('calendar');
    Route::get('/projects/list', [ProjectController::class, 'list']);
    Route::get('/tasks', [TaskController::class, 'index'])->middleware('auth');
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('auth');
    Route::get('/tasks/report', [TaskController::class, 'report'])->name('tasks.report')->middleware('auth');
});

require __DIR__ . '/auth.php';
