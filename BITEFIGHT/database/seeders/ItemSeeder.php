<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Item table being seeded...');

        $path = 'database/datas/items.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Item table seeded!');
    }
}
