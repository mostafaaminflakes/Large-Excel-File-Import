<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsvProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'name', 'sku', 'price', 'currency', 'variations', 'quantity', 'status'];
}
