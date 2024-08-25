<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PostService
{
    public function getPaginatedPosts()
    {
        return Post::with(['user'])
            ->withCount(['likes', 'comments'])
            ->paginate(10);
    }

    public function getPostById(string $id)
    {
        return Post::with(['user', 'comments', 'attachments.media'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($id);
    }

    public function createPost(StorePostRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $post = Auth::user()->posts()->create([
                    'content' => $request['content'],
                ]);

                if ($request->hasFile('attachments')) {
                    $this->handleAttachments($request->file('attachments'), $post);
                }

                return $post->load('user', 'attachments.media');
            });
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while creating the post.');
        }
    }

    public function updatePost(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);

        if ($post === null) {
            throw new HttpException(404, 'post does not exists!');
        }

        if(! Gate::allows('update', $post)) {
            throw new HttpException(403, 'You do not have permission to update this post.');
        }

        try {
            $post->fill($request->validated())->save();
            return $post;
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while updating the post.');
        }
    }

    public function deletePost(string $id)
    {
        $post = Post::find($id);

        if ($post === null) {
            throw new HttpException(404, 'post does not exists!');
        }

        if(! Gate::allows('delete', $post)) {
            throw new HttpException(403, 'You do not have permission to delete this post.');
        }

        try {
            DB::transaction(function () use ($post) {
                $this->deleteAttachments($post);
                $post->comments()->delete();
                $post->likes()->delete();
                $post->delete();
            });
        } catch (\Exception $e) {
            throw new HttpException(500, 'An error occurred while deleting the post.');
        }
    }

    protected function handleAttachments($files, Post $post)
    {
        foreach ($files as $file) {
            $media = $post->user->media()->create([
                'type' => $file->getMimeType() === 'video/mp4' ? 'video' : 'image',
                'url' => $file->store('attachments', 'public'),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $post->attachments()->create([
                'user_id' => Auth::id(),
                'media_id' => $media->id,
            ]);
        }
    }

    protected function deleteAttachments(Post $post)
    {
        foreach ($post->attachments as $attachment) {
            if ($attachment->media) {
                Storage::disk('public')->delete($attachment->media->url);
                $attachment->media->delete();
            }
            $attachment->delete();
        }
    }
}
