<?php

namespace App\Providers;

use App\Services\Api\ApiProjectService;
use App\Services\Api\ApiTaskService;
use App\Services\ProjectService;
use App\Services\TaskService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectService::class, function ($app) {
            return new ProjectService($app->make(ApiProjectService::class));
        });
        $this->app->bind(TaskService::class, function ($app) {
            return new TaskService($app->make(ApiTaskService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
