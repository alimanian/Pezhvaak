<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Comment\StoreCommentRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CommentService
{
    public function createComment(StoreCommentRequest $request)
    {
        try {
            Gate::authorize('create', Comment::class);
            return Comment::create($request->validated() + ['user_id' => Auth::id()]);
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while creating the comment.');
        }
    }

    public function deleteComment(string $id)
    {
        $comment = Comment::find($id);

        if ($comment === null) {
            throw new HttpException(404, 'comment does not exists!');
        }

        if (!Gate::allows('delete', $comment)) {
            throw new HttpException(403, 'You do not have permission to delete this comment.');
        }

        try {
            $comment->delete();
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while deleting the comment.');
        }
    }
}
