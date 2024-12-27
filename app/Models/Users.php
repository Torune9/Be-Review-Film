<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasFactory, HasUuids;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

   /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Properti yang disembunyikan saat serialisasi ke array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role() {
        return $this->belongsTo(Roles::class);
    }

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function reviews() {
        return $this->hasMany(Reviews::class);
    }

    public function otp() {
        return $this->hasOne(Otp::class,'user_id');
    }

}
