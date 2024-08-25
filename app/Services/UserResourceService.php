<?php

namespace App\Services;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserResourceService
{
    public function getUserPosts(string $userId)
    {
        try {
            $user = User::findOrFail($userId);
            return $user->posts()->withCount(['likes', 'comments'])
                ->paginate(10);
        } catch (\Exception $e) {
            throw new HttpException(404, 'User not found or an error occurred while fetching posts.');
        }
    }

    public function getUserLikes(string $userId)
    {
        try {
            $user = User::findOrFail($userId);
            return $user->likes()->with('post')->paginate(10);
        } catch (\Exception $e) {
            throw new HttpException(404, 'User not found or an error occurred while fetching likes.');
        }
    }

    public function getUserComments(string $userId)
    {
        try {
            $user = User::findOrFail($userId);
            return $user->comments()->with('post')->paginate(10);
        } catch (\Exception $e) {
            throw new HttpException(404, 'User not found or an error occurred while fetching comments.');
        }
    }
}
