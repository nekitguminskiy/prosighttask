<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Repositories\SalesmanRepositoryInterface;
use App\Contracts\Services\SalesmanServiceInterface;
use App\Repositories\SalesmanRepository;
use App\Services\SalesmanService;
use Illuminate\Support\ServiceProvider;

final class SalesmanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind repository interface to implementation
        $this->app->bind(
            SalesmanRepositoryInterface::class,
            SalesmanRepository::class
        );

        // Bind service interface to implementation
        $this->app->bind(
            SalesmanServiceInterface::class,
            SalesmanService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
