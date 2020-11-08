<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->command->info('Talents table seeded!');

        $path = 'database/datas/talents.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('talents table seeded!');
    }
}
