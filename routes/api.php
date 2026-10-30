<?php

use Illuminate\Support\Facades\Route;

Route::get('/price', [\App\Http\Controllers\Api\PriceController::class, 'show']);
