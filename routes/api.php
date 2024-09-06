<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
Route::post("/register", [RegisteredUserController::class, "store"]); // Register user
Route::post("/login", [AuthController::class, "login"]); // Login user

// Routes for authenticated users
Route::middleware(["auth:sanctum"])->group(function () {
    Route::get("/user", function (Request $request) {
        return $request->user(); // Get authenticated user
    });

    // Admin routes for managing posts (Admins cannot manage comments)
    Route::middleware("admin")->group(function () {
        Route::post("/posts", [PostController::class, "store"]); // Create post
        Route::put("/posts/{id}", [PostController::class, "update"]); // Update post
        Route::delete("/posts/{id}", [PostController::class, "destroy"]); // Delete post
    });

    // Routes for managing comments (accessible to regular users only)
    Route::middleware("role:user")->group(function () {
        Route::post("/posts/{postId}/comments", [CommentController::class, "store"]); // Create comment
        Route::put("/posts/{postId}/comments/{id}", [CommentController::class, "update"]); // Update comment
        Route::delete("/posts/{postId}/comments/{id}", [CommentController::class, "destroy"]); // Delete comment
    });

    // Routes accessible to all authenticated users (including admins and users)
    Route::get("/posts", [PostController::class, "index"]); // List posts
    Route::get("/posts/{id}", [PostController::class, "show"]); // Show single post
    Route::get("/posts/{postId}/comments", [CommentController::class, "index"]); // List comments for a post
    Route::get("/posts/{postId}/comments/{id}", [
        CommentController::class,
        "show",
    ]); // Show single comment for a post
});
