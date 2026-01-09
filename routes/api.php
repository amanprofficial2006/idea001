<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrganisationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TaskController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/toggle-online', [UserController::class, 'toggleOnlineStatus']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::get('/task/{id}', [TaskController::class, 'show']);
    Route::get('/post-task', [TaskController::class, 'postedTasks']);
});

Route::get('/organisation', [OrganisationController::class, 'index']);

// Categories
Route::get('categories/index', [CategoryController::class, 'index']);
