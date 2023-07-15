<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //

    public function createFollow(User $user)
    {

        // You can not follow youerself
        if ($user->id == auth()->user()->id) {
            return back()->with('failure', 'You cant follow yourself!');
        }

        //You can not follow anyone already followed by you
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($existCheck) {
            return back()->with('failure', 'You are already following that user!');
        }


        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'You now follow ' . $user->username . '!');
    }

    public function removeFollow(User $user)
    {
        //<nn>
        // Remove one follower based on user_id, and followerid
        //</nn>
        Follow::where([
            ['user_id', '=', auth()->user()->id],
            ['followeduser', '=', $user->id]
        ])->delete();
        return back()->with('success', 'You are no longer following user ' . $user->username);
    }
}
