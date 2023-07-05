<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\CsvProductJob;
use Illuminate\Support\Facades\File;

class CsvProductsController extends Controller
{
    public function import($csv_file)
    {
        // Validate file exists
        if (File::exists(storage_path('csv/' . $csv_file))) {
            // Queue import
            CsvProductJob::dispatch($csv_file)->onQueue('csv');
        }
    }
}
