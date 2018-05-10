<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Role extends Model
{
    /**
     * add a many to many relationship between role and user
     * 1 role can belong to 1 or more users
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
