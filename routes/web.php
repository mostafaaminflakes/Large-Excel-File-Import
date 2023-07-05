<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CsvProductsController;
use App\Http\Controllers\JsonproductsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
