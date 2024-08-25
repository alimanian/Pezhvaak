<?php

namespace App\Services;

use App\Models\Follower;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowerService
{
    public function follow(User $user)
    {
        return Follower::firstOrCreate([
            'follower_id' => Auth::id(),
            'following_id' => $user->id
        ]);
    }

    public function unfollow(User $user)
    {
        return Follower::where('follower_id', Auth::id())
            ->where('following_id', $user->id)
            ->delete();
    }

    public function getFollowers(User $user)
    {
        return $user->followers()->with('follower')->paginate(10);
    }

    public function getFollowing(User $user)
    {
        return $user->following()->with('following')->paginate(10);
    }
}
