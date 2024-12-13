<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/category', [CategoryController::class, 'index']);
Route::post('/addCategory', [CategoryController::class, 'store']);
Route::put('/categoryEdit/{id}', [CategoryController::class, 'update']);
Route::delete('/categoryDelete/{id}', [CategoryController::class, 'destroy']);


Route::get('/product', [ProductController::class, 'index']);
Route::post('/addProduct', [ProductController::class, 'store']);
Route::put('/productEdit/{id}', [ProductController::class, 'update']);
Route::delete('/productDelete/{id}', [ProductController::class, 'destroy']);
