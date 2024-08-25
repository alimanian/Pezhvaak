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
        // User::factory(10)->create();

        $users = User::factory(99)->create();

        $posts = Post::factory(150)
            ->recycle($users)
            ->create();

        Comment::factory(200)
            ->recycle($users)
            ->recycle($posts)
            ->create();

        try {
            Like::factory(200)
                ->recycle($users)
                ->recycle($posts)
                ->create();
        } catch (\Exception $exception) {

        }

        $media = Media::factory(100)
            ->recycle($users)
            ->create();


        try {
            Attachment::factory(100)
                ->recycle($users)
                ->recycle($posts)
                ->recycle($media)
                ->create();
        } catch (\Exception $exception) {

        }

        try {
            Follower::factory(150)
                ->recycle($users)
                ->create();
        } catch (\Exception $exception) {

        }

        User::factory()->create([
            'name' => 'Ali Manian',
            'email' => 'alimanian79@gmail.com',
            'password' => Hash::make('A123456b')
        ]);
    }
}
