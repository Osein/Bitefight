<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTalentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:talents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import talent data for talents table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		DB::unprepared(file_get_contents(base_path('database'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'talent_data.sql')));
		return true;
    }
}
