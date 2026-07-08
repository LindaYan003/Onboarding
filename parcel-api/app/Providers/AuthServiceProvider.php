<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        // This tells Lumen how to parse incoming requests guarded by 'auth' middleware
        $this->app['auth']->viaRequest('api', function ($request) {
            // Simply check if the token can authenticate a user model instance
            try {
                return JWTAuth::parseToken()->authenticate();
            } catch (\Exception $e) {
                return null;
            }
        });


    }
}
