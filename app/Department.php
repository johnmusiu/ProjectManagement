<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Department extends Model
{
    /**
     * a department has many employees (1:M)
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
