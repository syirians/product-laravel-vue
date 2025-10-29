<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;

Route::post('products/getDataProduct', [ProductController::class, 'index']);
Route::post('products/createProduct', [ProductController::class, 'store']);
Route::post('products/getDataProductDetail', [ProductController::class, 'show']);
Route::post('products/editProduct', [ProductController::class, 'update']);
Route::delete('products/deleteProduct', [ProductController::class, 'destroy']);
// Route::middleware('auth:sanctum')->group(function () {
//     // Route::apiResource('products', ProductController::class);
// });
