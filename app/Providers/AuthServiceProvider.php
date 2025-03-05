<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Configura Sanctum para usar el modelo PersonalAccessToken
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
