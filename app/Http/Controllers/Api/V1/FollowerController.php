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

    public function followers(User $user)
    {
        $followers = $this->followerService->getFollowers($user);
        return response()->format($followers, 'User followers received.');
    }

    public function following(User $user)
    {
        $following = $this->followerService->getFollowing($user);
        return response()->format($following, 'User following received.');
    }

    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->format([], 'You cannot follow yourself.', false, 400);
        }

        $follower = $this->followerService->follow($user);

        if ($follower->wasRecentlyCreated) {
            return response()->format([], 'User followed successfully.', true, 201);
        }

        return response()->format([], 'You are already following this user.', false, 400);
    }

    public function unfollow(User $user)
    {
        $deleted = $this->followerService->unfollow($user);

        if ($deleted) {
            return response()->format([], 'Unfollow was successfully.');
        }

        return response()->format([], 'You are not following this user.', false, 400);
    }
}
