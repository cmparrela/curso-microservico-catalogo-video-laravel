<?php

use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run()
    {
        factory(Seeder::class, 100)->create();
    }
}
