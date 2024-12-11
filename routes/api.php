<?php

use App\Http\Controllers\InventoryBook;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

// group middleware untuk API Auth
Route::middleware(ApiAuthMiddleware::class)->group(function () {
	Route::get('/users/current', [UserController::class, 'get']);
	Route::patch('/users/current', [UserController::class, 'update']);
	Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/books', [BookController::class, 'create']);
	Route::get('/books', [BookController::class, 'search']);
	Route::get('/books/{id}', [BookController::class, 'get'])->where('id', '[0-9]+');
	Route::put('/books/{id}', [BookController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/books/{id}', [BookController::class, 'delete'])->where('id', '[0-9]+');

	Route::post('/books/{idBook}/inventoryBooks', [InventoryBook::class, 'create'])->where('idBook', '[0-9]+');
});


