<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/reset', [AccountController::class, 'reset']);
Route::get('/balance', [AccountController::class, 'getBalance']);
Route::post('/event', [AccountController::class, 'postEvent']);