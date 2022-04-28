<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
        Añadiremos estos dos métodos
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role(){
        return $this->hasOne('App\Models\Lookup','id','lkp_rol_id');
    }

    public function client(){
        return $this->hasOne('App\Models\Client','id','client_id');
    }

    public function scopeRole($query, $search) {
        if($search)
            $query->where('lkp_rol_id',$search);
    }

    public function scopeEmail($query, $search) {
        if($search)
            $query->where('email', 'like', '%' .$search . '%')->orWhere('phone', 'like', '%' .$search.'%');
    }
    
}
