<?php

namespace Database\Seeders;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Seeder;

class LikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 100; $i++) {
            $tweet = Tweet::inRandomOrder()->first();
            $user = User::inRandomOrder()->first();
            $tweet->users()->attach($user);
        }
    }
}
