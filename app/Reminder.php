<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Task;

class Reminder extends Model
{
    /**
     * A reminder belongs to a single task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * A reminder belongs to a single user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
