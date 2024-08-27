<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Services\UserResourceService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserResourceController extends Controller
{
    public function __construct(protected UserResourceService $userResourceService) { }

    public function userPosts(string $userId)
    {
        try {
            $posts = $this->userResourceService->getUserPosts($userId);
            return response()->format($posts, 'User posts received.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }

    public function userComments(string $userId)
    {
        try {
            $comments = $this->userResourceService->getUserComments($userId);
            return response()->format($comments, 'User comments received.', true, 200);
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }

    public function userLikes(string $userId)
    {
        try {
            $likes = $this->userResourceService->getUserLikes($userId);
            return response()->format($likes, 'User likes received.');
        } catch (HttpException $e) {
            return response()->format([], $e->getMessage(), false, $e->getStatusCode());
        }
    }
}
