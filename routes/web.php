<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/breed', 'App\Http\Controllers\Breed@listAllBreed');
Route::get('/breed/random/', 'App\Http\Controllers\Breed@randomBreed');
Route::get('/breed/{id}', 'App\Http\Controllers\Breed@breedById');
Route::get('/breed/{id}/image', 'App\Http\Controllers\Breed@breedByIdWithImage');
/*
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
*/
