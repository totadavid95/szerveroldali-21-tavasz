<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tábla resetelése
        DB::table('categories')->truncate();

        // 7 kategória létrehozása
        Category::factory(7)->create();
    }
}
