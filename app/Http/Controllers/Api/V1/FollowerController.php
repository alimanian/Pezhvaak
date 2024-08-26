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

        $follower = $this->followerService->follow($user);

        if ($follower->wasRecentlyCreated) {
            return response()->apiResponse([], true, 'User followed successfully.', 201);
        }

        return response()->apiResponse([], false, 'You are already following this user.', 400);
    }

    public function unfollow(User $user)
    {
        $deleted = $this->followerService->unfollow($user);

        if ($deleted) {
            return response()->json([], 204);
        }

        return response()->apiResponse([], false, 'You are not following this user.', 400);
    }

    public function followers(User $user)
    {
        $followers = $this->followerService->getFollowers($user);
        return response()->apiResponse($followers, true, '', 200);
    }

    public function following(User $user)
    {
        $following = $this->followerService->getFollowing($user);
        return response()->apiResponse($following, true, '', 200);
    }
}
