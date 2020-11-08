<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Talents table being seeded...');

        $path = 'database/datas/talents.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Talent table seeded!');
    }
}
