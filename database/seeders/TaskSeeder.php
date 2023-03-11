<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->insert([
            [
                'name' => Str::random(10),
                'user_id' => 1,
            ],
            [
                'name' => Str::random(10),
                'user_id' => 2,
            ],
            [
                'name' => Str::random(10),
                'user_id' => 3,
            ],
        ]);
    }
}
