<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\CategoryAttributeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/attributes/models', [AttributeController::class, 'getModels']);
Route::get('/categories/{category}/attributes', [CategoryAttributeController::class, 'index']);
