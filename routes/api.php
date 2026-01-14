<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndicatorsController;

Route::prefix("indicators")->controller(IndicatorsController::class)->group(function () {
    Route::post('/import', 'import');
    Route::get('/summary', 'summary');
});