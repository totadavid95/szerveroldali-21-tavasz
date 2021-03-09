<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tábla resetelése
        DB::table('posts')->truncate();

        // 10 post létrehozása
        Post::factory(10)->create();
    }
}
