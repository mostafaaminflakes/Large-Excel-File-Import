<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class JsonProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $chunk;

    /**
     * Create a new job instance.
     */
    public function __construct($chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO: Refactoring needed
        // For faster imports we need to:
        // 1. Find a way to combine the deep nesting EAV queries into fewer amount
        // 2. Find a way to use many-to-many relationships with Eloquent [insert] method
        // 3. Checkout [LOAD DATA INFILE] settings -> https://github.com/ellgreen/laravel-loadfile
        // 4. Dispach product updates notifications

        foreach ($this->chunk as $api_product) {

            // Add products
            $product = Product::firstOrCreate([
                'name' => $api_product['name'],
                'price' => $api_product['price'],
                'image' => $api_product['image'],
                'description' => '',
            ]);

            // $product_attribute = ProductAttribute::firstOrCreate([
            //     'name' => 'Material',
            // ]);
            // $product_attribute = ProductAttribute::all()->modelKeys();

            // Variations
            $variations = $api_product['variations'];

            if (!empty($variations)) {

                foreach ($variations as $variation) {
                    // Add variations and attributes using EAV many-to-many relathionships
                    ProductVariant::firstOrCreate([
                        'product_id' => $product->id,
                        'additional_price' => $variation['quantity'],
                        'quantity' => $variation['quantity'],
                        'sku' => Str::random(8),
                        'image' => '',
                    ])->attributes()->attach([
                        1 => ['value' => $variation['color']],
                        2 => ['value' => $variation['material']]
                    ]);

                    // Add attributes
                    // $variant->attributes()->attach($product_attribute, ['value' => $variation['color']]);
                    // $variant->attributes()->attach([
                    //     1 => ['value' => $variation['color']],
                    //     2 => ['value' => $variation['material']]
                    // ]);
                }
            }
        }
    }
}
