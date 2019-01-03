<?php

namespace App\Providers;

use Harlekoy\ApiDocs\Providers\ApiDocsApplicationServiceProvider;
use Illuminate\Support\Facades\Gate;

class ApiDocsServiceProvider extends ApiDocsApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate()
    {
        Gate::define('viewApiDocs', function ($user) {
            return in_array($user->email, [
            ]);
        });
    }
}
