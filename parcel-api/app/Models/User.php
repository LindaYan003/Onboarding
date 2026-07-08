<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
// 1. IMPORT THE CONTRACT:
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    // =========================================================================
    // 2. ADD THE TWO JWT REQUIRED METHODS BELOW:
    // =========================================================================

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * (This tells the package to use your user's ID number to identify them).
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returns the user's id (e.g., 1, 2, 3)
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * (You can leave this empty, or add data like user roles here).
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
