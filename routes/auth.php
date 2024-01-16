<?php

use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function() {

    Route::post('/signup', [AuthController::class, 'storeUser'])->name('signup');

    Route::post('/login', [AuthController::class, 'loginUser'])->name('login');

    Route::get('/user/delete/{id}', [AuthController::class, 'deleteUser']);
    Route::post('/user/delete/users', [AuthController::class, 'deleteUsers']);

    Route::get('/user/{email}', [AuthController::class, 'getUser']);
    Route::post('/user/edit', [AuthController::class, 'EditUser']);

    Route::get('/users', [AuthController::class, 'getAllUsers']);
    Route::get('/roles', [AuthController::class, 'getAllRoles']);

    Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/', function (){
        return "authenticate";
    });
});

//Route::get('/images/{filename}', [PhotoController::class, 'showImage'])->where('filename', '.*');
Route::post('/upload-image', [PhotoController::class, 'uploadImage']);
