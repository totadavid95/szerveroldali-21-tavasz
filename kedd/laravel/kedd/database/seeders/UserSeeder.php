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

        for ($i = 1; $i <= 3; $i++) {
            User::factory()->create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@szerveroldali.hu',
                //'password' => brcypt('q'),
                'password' => Hash::make('q'),
            ]);
        }
    }
}
