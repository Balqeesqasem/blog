<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;

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

// Public routes
Route::post("/register", [RegisteredUserController::class, "store"]);
Route::post("/login", [AuthController::class, "login"]);

// Routes for authenticated users
Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("/user", function (Request $request) {
        return $request->user();
    });
    // Admin routes
    Route::middleware("admin")->group(function () {
        Route::post("/posts", [PostController::class, "store"]); // Create post
        Route::put("/posts/{id}", [PostController::class, "update"]); // Update post
        Route::delete("/posts/{id}", [PostController::class, "destroy"]); // Delete post
    });

    // Public routes
    Route::get("/posts", [PostController::class, "index"]); // List posts
    Route::get("/posts/{id}", [PostController::class, "show"]);
});
