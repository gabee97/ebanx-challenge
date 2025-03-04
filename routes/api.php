<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::post('/reset', [AccountController::class, 'reset']);
Route::get('/balance', [AccountController::class, 'getBalance']);
Route::post('/event', [AccountController::class, 'postEvent']);

