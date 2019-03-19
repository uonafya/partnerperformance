<?php

namespace App;

use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $connection = 'mysql_wr';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Automatically creates hash for the user password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

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

    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }

    public function getResetPasswordLinkAttribute()
    {
        $change_url = URL::temporarySignedRoute('reset.password', now()->addDays(7), ['user' => $this->id]);

        \Illuminate\Support\Facades\URL::forceScheme('http');

        $url = URL::temporarySignedRoute('reset.password', now()->addDays(7), ['user' => $this->id]);

        \Illuminate\Support\Facades\URL::forceScheme('https');

        $new_signature = str_after($url, 'expires=');
        $old_signature = str_after($change_url, 'expires=');
        
        $change_url = str_replace($old_signature, $new_signature, $change_url);

        return $change_url;
    }
}
