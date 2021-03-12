<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PostSeeder::class);

        $users = User::all();
        $users_count = $users->count();

        $categories = Category::all();
        $categories_count = $categories->count();

        Post::all()->each(function ($post) use (&$categories, &$categories_count, &$users, &$users_count) {
            $category_ids = $categories->random(rand(1, $categories_count))->pluck('id')->toArray();
            $post->categories()->attach($category_ids);

            if ($users_count > 0) {
                $post->author()->associate($users->random());
                $post->save();
            }
        });
    }
}
