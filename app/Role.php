<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	protected $fillable = [
        'role_name'
    ];

    protected  $hidden = [
        "created_at",
        "updated_at",
    ];

    public static function getRoles() {        
        $roles=Role::all();
        
        return $roles;
    }
    





}

