<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\CsvProductsImport;

class CsvProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $csv_file;

    /**
     * Create a new job instance.
     */
    public function __construct($csv_file)
    {
        $this->csv_file = $csv_file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // The regular way without queue names
        // Excel::import(new CsvProductsImport, $this->csv_file, 'csv_disk');

        // We can use [->queue] for chunked imports because [Importable] trait is used in [app/Imports/CsvProductsImport.php]
        (new CsvProductsImport)->queue($this->csv_file, 'csv_disk')->allOnQueue('csv')->chain([
            // Dispatch job to notify the admin of completed import
        ]);
    }
}
