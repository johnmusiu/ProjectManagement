<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * create a comment form view display
     * @param none
     * @return view
     */
    public function index()
    {
        return view('comments.index');
    }

    /**
     * create a comment ie save comment
     * @param Request $request
     * @return redirect 
     */
    public function comment(Request $request, $task_id)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return redirect('/task/comment')
                        ->withErrors($validator)
                        ->withInput();
        }
        $comment = new Comment();
        $comment->comment = $request['comment'];
        $comment->user_id = \Auth::User()->id;
        $comment->task_id = $task_id;

        if(!$comment->save()){
            session()->flash("error", "Comment not saved");
            return back()->withInput();
        }
        session()->flash("message", "Comment added.");
        return redirect(route('view_task', $task_id));
    }
}
