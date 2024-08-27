<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = User::factory(199)->create();

        $posts = Post::factory(300)
            ->recycle($users)
            ->create();

        Comment::factory(500)
            ->recycle($users)
            ->recycle($posts)
            ->create();


        Like::insertOrIgnore($this->getLikesData(500, $users, $posts));


        $media = Media::factory(800)
            ->recycle($users)
            ->create();


        Attachment::insertOrIgnore($this->getAttachmentData(500, $users, $posts, $media));


        Follower::insertOrIgnore($this->getFollowerData(400, $users));


        User::factory()->create([
            'name' => 'Ali Manian',
            'email' => 'alimanian79@gmail.com',
            'password' => Hash::make('A123456b')
        ]);
    }

    private function getLikesData(int $count, $users, $posts): array
    {
        $likeFactory = Like::factory()->recycle($users)->recycle($posts);
        return $likeFactory->count($count)->make()->map(function ($like) {
            return $like->getAttributes();
        })->toArray();
    }

    private function getAttachmentData(int $count, $users, $posts, $media): array
    {
        $attachmentFactory = Attachment::factory()->recycle($users)->recycle($posts)->recycle($media);
        return $attachmentFactory->count($count)->make()->map(function ($attachment) {
            return $attachment->getAttributes();
        })->toArray();
    }

    private function getFollowerData(int $count, $users): array
    {
        $followerFactory = Follower::factory()->recycle($users);
        return $followerFactory->count($count)->make()->map(function ($follower) {
            return $follower->getAttributes();
        })->toArray();
    }
}
