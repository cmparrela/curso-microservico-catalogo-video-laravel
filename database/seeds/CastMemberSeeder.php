<?php

use App\Models\CastMember;
use Illuminate\Database\Seeder;

class CastMemberSeeder extends Seeder
{
    public function run()
    {
        factory(CastMember::class, 100)->create();
    }
}
