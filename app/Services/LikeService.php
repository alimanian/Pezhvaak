<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Like\StoreLikeRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LikeService
{
    public function createLike(StoreLikeRequest $request)
    {
        if (!Gate::allows('create', Like::class)) {
            throw new HttpException(403, 'You do not have permission to create this like.');
        }

        $post = Post::findOrFail($request['post_id']);

        $existingLike = Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            throw new HttpException(400, 'You have already liked this post.');
        }

        try {
            return Like::create($request->validated() + ['user_id' => Auth::id()]);
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while creating the like.');
        }
    }

    public function deleteLike(string $likeId)
    {
        $like = Like::find($likeId);

        if($like === null) {
            throw new HttpException(404, 'like does not exists!');
        }

        if (!Gate::allows('delete', $like)) {
            throw new HttpException(403, 'You do not have permission to delete this like.');
        }

        try {
            $like->delete();
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while deleting the like.');
        }
    }
}
