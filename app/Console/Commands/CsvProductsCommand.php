<?php

namespace App\Console\Commands;

use App\Http\Controllers\CsvProductsController;
use Illuminate\Console\Command;

class CsvProductsCommand extends Command
{
    private $csv_file = 'products.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a CSV file from [/storage/csv/*.csv] into the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        (new CsvProductsController)->import($this->csv_file);
    }
}
