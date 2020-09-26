<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Nantaburi\Mongodb\MongoNativeDriver\Model as NanModel ;

class User_nan extends NanModel
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection = "mongodb";

    protected $database = "Abpon" ;
    
    protected $collection = 'users';

    protected $fillable = [
        'id', 'name', 'email', 'password','address', 'phone'
    ];

    protected $schema = [
        'users' => [
            "id" => [
                'AutoInc' => true,
                // 'AutoIncStartwith' => 10,
                'Index' => true,
                'Unique' => true
            ],
            "name",
            "password",
            "email" => [
                'Unique' => true
            ],
            "status",
            "address",
            "phone",
            "created_at",
            "updated_at",
        ],
    ];

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

    public function isAdmin()
    {
        return $this->admin;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function session()
    {
        return $this->hasMany(Session::class);
    }
}
