<?php

namespace App\Imports;

use App\Models\CsvProduct;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;             // Import each row to a model
use Maatwebsite\Excel\Concerns\WithBatchInserts;    // Insert models in batches
use Maatwebsite\Excel\Concerns\WithChunkReading;    // Read the sheet in chunks
use Illuminate\Contracts\Queue\ShouldQueue;         // For queues
use Maatwebsite\Excel\Concerns\Importable;          // Add import/queue abilities right on the import class itself
use Maatwebsite\Excel\Concerns\WithHeadingRow;      // Define a row as heading row [Define rows by name instead of IDs]
use Maatwebsite\Excel\Concerns\WithUpserts;         // Allows to upsert models
use Maatwebsite\Excel\Concerns\WithEvents;          // Hook into the parent package to add custom behavior to the import
use Maatwebsite\Excel\Events\AfterImport;           // After import ends, do something
use Maatwebsite\Excel\Events\ImportFailed;          // On failed validation event
use Maatwebsite\Excel\Concerns\SkipsOnFailure;      // Validation, skip errors on failure and combine them in the end
use Maatwebsite\Excel\Concerns\WithValidation;      // Validation
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CsvProductsImport implements
    ToModel,
    WithBatchInserts,
    WithChunkReading,
    WithUpserts,
    WithHeadingRow,
    ShouldQueue,
    WithEvents,
    WithValidation,
    SkipsOnFailure
{
    use Importable; // Trait to allow queuing in controllers or jobs

    public function model(array $row)
    {
        $product = new CsvProduct([
            'product_id'    => $row['id'],
            'name'          => $row['name'],
            'sku'           => $row['sku'], //Hash::make(Str::random(8)),
            'price'         => $row['price'],
            'currency'      => $row['currency'],
            'variations'    => $row['variations'],
            'quantity'      => $row['quantity'],
            'status'        => $row['status'],
        ]);

        return $product;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                DB::table('csv_products')->where('status', 'deleted')->update(['deleted_at' => Carbon::now()]);
                Log::channel('imports')->info('Successfully imported the Excel file.');
            },

            ImportFailed::class => function (ImportFailed $event) {
                Log::channel('imports')->info('Failed to import parts of the Excel file [ImportFailed].');
                // foreach ($event->getException() as $exception) {
                //     Log::channel('imports')->info(print_r($exception->errors(), true));
                // }
                foreach ($event->getException()->failures() as $failure) {
                    Log::channel('imports')->info(print_r($failure->toArray(), true));
                }
            },
        ];
    }

    /**
     * Validate rows when import
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'id'        => 'required',
            'name'      => 'required',
            'price'     => 'required|numeric|min:0',
            'currency'  => 'required|string',
            'quantity'  => 'required|numeric|min:0',
            'status'    => 'in:sale,out,hidden,none,deleted',
        ];
    }

    public function uniqueBy()
    {
        return 'product_id';
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 2000;
    }

    public function onFailure(Failure ...$failures)
    {
        Log::channel('imports')->info('Failed to import parts of the Excel file [onFailure].');

        foreach ($failures as $failure) {
            Log::channel('imports')->info(print_r($failure->toArray(), true));
        }
    }
}
