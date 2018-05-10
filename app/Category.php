<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;

class Category extends Model
{
    /**
     * a category can have many tasks 
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
