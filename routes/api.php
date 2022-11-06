<?php

use App\Http\Controllers\AbonamentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// protect routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //films
    Route::resource('films', FilmController::class);
    Route::get('/films/search/{name}', [FilmController::class, 'search']);

    // users
    Route::get('/users/{type}', [UsersController::class, 'index']);
    Route::get('/users/{id}', [UsersController::class, 'show']);
    Route::put('/users/{id}', [UsersController::class, 'update']);
    Route::delete('/users/{id}', [UsersController::class, 'destroy']);

    // user history
    Route::get('/users/history/{user_id}', [UsersController::class, 'getHistory']);
    Route::get('/users/history/{user_id}/{film_id}', [UsersController::class, 'AddHistory']);

    // user ToWatch
    Route::get('/users/towatch/{user_id}', [UsersController::class, 'getToWatch']);
    Route::get('/users/towatch/{user_id}/{film_id}', [UsersController::class, 'AddToWatch']);
    Route::delete('/users/towatch/{id}', [UsersController::class, 'deleteToWatch']);

    // Abonament
    Route::resource('/comments', CommentController::class);

    //Rating
    Route::get('/film/rate/{film_id}', [FilmController::class, 'getRating']);
    Route::get('/film/rate/{film_id}/{rate}', [FilmController::class, 'addRating']);

    // Comments
    Route::resource('/abonaments', AbonamentController::class);

    //logout
    Route::get('/logout', [AuthController::class, 'logout']);

});
