<?php

use App\Http\Controllers\Rest\ProjectController;
use App\Http\Controllers\Rest\Auth\AuthController;
use App\Http\Controllers\Rest\TaskController;

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');
});

Route::middleware(['auth:sanctum'])->group(function () {

    //Projects
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('api.projects.index');
    });
    Route::prefix('project')->group(function () {
        Route::post('/', [ProjectController::class, 'store'])->name('api.project.store');
        Route::get('/{id}', [ProjectController::class, 'show'])->name('api.project.show');
        Route::put('/{id}', [ProjectController::class, 'update'])->name('api.project.update');
        Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('api.project.destroy');
    });

    //Tasks
    Route::prefix('project/{projectId}')->group(function () {
        Route::post('/task', [TaskController::class, 'store'])->name('api.task.store');
        Route::get('/tasks', [TaskController::class, 'index'])->name('api.tasks.index');
    });
    Route::prefix('task')->group(function () {
        Route::get('/{id}', [TaskController::class, 'show'])->name('api.task.show');
        Route::put('/{id}', [TaskController::class, 'update'])->name('api.task.update');
        Route::delete('/{id}', [TaskController::class, 'destroy'])->name('api.task.destroy');
    });

});
