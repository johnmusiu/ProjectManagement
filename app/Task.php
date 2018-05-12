<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Task extends Model
{
    /**
     * define a many to one relationship between task and user
     *  a task can only be created by 1 user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A task belongs only to one category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
}
