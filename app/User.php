<?php

namespace Bsmma;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAuthUser()
    {
        return 2;
    }

    // Relationships --------------------------------------
    public function userRole()
    {
        return $this->belongsTo('Bsmma\UserRole');
    }

    public function contests()
    {
        return $this->belongsToMany('Bsmma\Contest');
    }

    public function picks()
    {
        return $this->belongsToMany('Bsmma\Pick');
    }

}
