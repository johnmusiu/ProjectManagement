<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Progress extends Model
{
    /**
     *  a task progress submission belongs to one user
     * @return relationship 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
