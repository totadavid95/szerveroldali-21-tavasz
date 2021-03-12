<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        /*
            user1 - user1@szerveroldali.hu - q
            user2 - user2@szerveroldali.hu - q
            ....
        */

        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => 'user'.$i,
                'email' => 'user'.$i.'@szerveroldali.hu',
                'password' => bcrypt('q'), // Hash::make('q')
            ]);
        }

    }
}
