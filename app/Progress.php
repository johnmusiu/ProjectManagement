<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Task;

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

    /**
     * a progress entry belongs to only 1 task
     * @return relationship
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
