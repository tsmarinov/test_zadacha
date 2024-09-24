<?php

use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {

    // Projects
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/', 'index')->name('projects.index');
        Route::get('/projects', 'index')->name('projects.index');
    });
    Route::prefix('project')->group(function () {
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
        Route::put('/edit/{id}', [ProjectController::class, 'update'])->name('project.update');
        Route::delete('/delete/{id}', [ProjectController::class, 'delete'])->name('project.delete');
        Route::get('/new', [ProjectController::class, 'new'])->name('project.new');
        Route::post('/new', [ProjectController::class, 'store'])->name('project.store');
    });

    // Tasks
    Route::get('/tasks/{projectId}', [TaskController::class, 'index'])->name('tasks.index');
    Route::prefix('task')->group(function () {
        Route::get('/edit/{id}', [TaskController::class, 'edit'])->name('task.edit');
        Route::put('/edit/{id}', [TaskController::class, 'update'])->name('task.update');
        Route::delete('/delete/{id}', [TaskController::class, 'delete'])->name('project.delete');
        Route::get('/new/{projectId}', [TaskController::class, 'new'])->name('task.new');
        Route::post('/new', [TaskController::class, 'store'])->name('task.store');
    });
});

