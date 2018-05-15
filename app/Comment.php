<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Task;

class Comment extends Model
{
    /**
     * a comment is created by 1 user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * a comment belongs to one task
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * get latest comment for a task
     * @param Task $task
     * @return Comment $comment
     */
    public function latest_comment($task)
    {
        return Comment::where('task_id', $task->id)
            ->latest()
            ->first();
    }
}
