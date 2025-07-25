<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user.profile');

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/{productId}', [ProductController::class, 'show'])->name('products.show');
        Route::put('/{productId}', [ProductController::class, 'update'])->name('products.update');

        Route::get('/{productId}/history', [ProductController::class, 'history'])->name('products.history');
        Route::get('/{productId}/history/{versionId}', [ProductController::class, 'showVersion'])->name('products.history.show_version');
    });

    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/{categoryId}/move', [CategoryController::class, 'move'])->name('categories.move');
        Route::get('/{categoryId}/children', [CategoryController::class, 'children'])->name('categories.children');
        Route::get('/{categoryId}/descendants', [CategoryController::class, 'descendants'])->name('categories.descendants');
        Route::get('/{categoryId}/ancestors', [CategoryController::class, 'ancestors'])->name('categories.ancestors');
    });
});
