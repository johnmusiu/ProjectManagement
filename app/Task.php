<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Progress;
use App\Category;
use App\Department;


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
    

    /**
     * a task can be assigned to many users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }

    /**
     *  a task can have many progress entries
     */
    public function progresses()
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * return the latest progress update on a post
     */
    public function latest_progress()
    {
        return $this->hasOne(Progress::class)->latest();
    }

    /**
     * many departments can be notified on many particular tasks
     * gets the departments listed for notifications for tasks
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'notifications');
    }
}
