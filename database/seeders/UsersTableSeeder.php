<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1, 12) as $key) {
            $userData = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            
            $userData['user_id'] = $key == 1 ? null : floor($key / 2);
            $userData['points'] = $key == 1 ? null : ($key % 2 == 0 ? 200 : 100);
            DB::table('users')->insert($userData);
        }
    }
}
