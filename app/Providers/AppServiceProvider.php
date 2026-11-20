<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        RedirectIfAuthenticated::redirectUsing(function () {
            return Auth::user()?->isAdmin()
                ? route('admin.dashboard')
                : route('aspirasi.index');
        });
    }
}
