<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use App\Task;
use App\Department;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'department_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * create a one to many relationship between user and tasks
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * a user can be assigned to many tasks
     */
    public function task_assignment()
    {
        return $this->belongsToMany(Task::class, 'task_user');
    }
    /**
     * A user can only belong to one department
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * 1 user can have 1 or more roles (1:M)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * a user has 1 or more reminders (1:M)
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if(is_array($roles)){
            return $this->hasAnyRole($roles) ||
                abort(401, 'This action is unauthorized.');
        }

        return $this->hasRole($roles) ||
            abort(401, 'This action is unauthorized');
    }

    /**
     * check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * check a single role
     * @param boolean
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }

    /**
     * follower relationship
     * @return BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id',
            'follower_id');
    }
    
    /**
     * follower relationship
     * @return BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'followers','follower_id', 
            'followed_id');
    }
}
