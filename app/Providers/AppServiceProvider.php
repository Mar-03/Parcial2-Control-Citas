<?php

namespace App\Providers;

use App\Repositories\Contracts\CitaRepositoryInterface;
use App\Repositories\Eloquent\CitaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CitaRepositoryInterface::class, CitaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
