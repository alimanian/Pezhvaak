<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Follower;
use App\Models\User;
use App\Services\FollowerService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller implements HasMiddleware
{
    public function __construct(protected FollowerService $followerService) { }

    public static function middleware()
    {
        return [new Middleware('auth:sanctum', only: ['follow', 'unfollow'])];
    }

    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->apiResponse([], false, 'You cannot follow yourself.', 400);
        }

        $follower = Follower::firstOrCreate([
            'follower_id' => Auth::id(),
            'following_id' => $user->id
        ]);

        if ($follower->wasRecentlyCreated) {
            return response()->apiResponse([], true, 'User followed successfully.', 201);
        }

        return response()->apiResponse([], false, 'You are already following this user.', 400);
    }

    public function unfollow(User $user)
    {
        $deleted = Follower::where('follower_id', Auth::id())
            ->where('following_id', $user->id)
            ->delete();

        if ($deleted) {
            return response()->apiResponse([], true, 'User unfollowed successfully.', 200);
        }

        return response()->apiResponse([], false, 'You are not following this user.', 400);
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->with('follower')->paginate(10);
        return response()->apiResponse($followers, true, '', 200);
    }

    public function following(User $user)
    {
        $following = $user->following()->with('following')->paginate(10);
        return response()->apiResponse($following, true, '', 200);
    }
}
