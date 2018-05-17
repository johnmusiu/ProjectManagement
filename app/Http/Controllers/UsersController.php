<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * follow a user
     * @param User $user
     */
    public function follow(User $user)
    {
        $user->followers()->attach(\Auth::User()->id);

        return redirect()->back()->with('message', "Successfully followed ".$user->name);
    }
    
    /**
     * follow a user
     * @param User $user
     */
    public function unfollow(User $user)
    {
        $user->followers()->detach(\Auth::User()->id);

        return redirect()->back()->with('message', "Successfully unfollowed ".$user->name);
    }

}
