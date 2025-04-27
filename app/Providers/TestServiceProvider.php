<?php

namespace App\Providers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    use RefreshDatabase;
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
