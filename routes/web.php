<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('getCats', [CatsController::class, 'getCats'])->name('getCats');
Route::post('addCats', [CatsController::class, 'addCats'])->name('addCats');
Route::post('storeCats', [CatsController::class, 'storeCats'])->name('storeCats');
Route::post('viewCats', [CatsController::class, 'viewCats'])->name('viewCats');
Route::put('updateCats', [CatsController::class, 'updateCats'])->name('updateCats');
Route::delete('deleteCats', [CatsController::class, 'deleteCats'])->name('deleteCats');