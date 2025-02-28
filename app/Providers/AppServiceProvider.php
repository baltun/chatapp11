<?php

namespace App\Providers;

use App\Repositories\MessagesRepository;
use App\Repositories\MessagesRepositoryInterface;
use App\Repositories\UsersRepository;
use App\Repositories\UsersRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UsersRepositoryInterface::class, UsersRepository::class);
        $this->app->bind(ChatsRepositoryInterface::class, ChatsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
