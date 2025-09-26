<?php

use App\Http\Controllers\Api\ValidationController;
use Illuminate\Support\Facades\Route;

// Email and mobile validation routes
Route::post('/check-email', [ValidationController::class, 'checkEmail']);
Route::post('/check-mobile', [ValidationController::class, 'checkMobile']);