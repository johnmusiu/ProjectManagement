<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;
use App\Department;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id', 'department_id'
    ];

    /**
     * a category can have many tasks 
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * belongs to 0 or 1 department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * a category is created by a certain user
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
