<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShowController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::get("/register", [AuthController::class, "getRegister"])->name("register");
    Route::post("/register", [AuthController::class, "postRegister"])->name("post.register");
    Route::get("/login", [AuthController::class, "getLogin"])->name("login");
    Route::post("/login", [AuthController::class, "postLogin"])->name("post.login");
    Route::get("/logout", [AuthController::class, "getLogout"])->name("logout");
});

// Routes that require authentication
Route::middleware('auth')->group(function () {
    // Home route
    Route::get("/", [TodoController::class, "index"])->name("home");

    Route::prefix('todo')->group(function () {
        Route::post("/add", [TodoController::class, "postAdd"])->name("post.todo.add");
        Route::post("/edit", [TodoController::class, "postEdit"])->name("post.todo.edit");
        Route::post("/delete", [TodoController::class, "postDelete"])->name("post.todo.delete");
        Route::post('/{id}/increment-progress', [TodoController::class, 'incrementProgress'])->name('todo.increment-progress');
        Route::get('/{id}', [ShowController::class, 'show'])->name('todo.show'); // Route for showing the detail of a todo
    });

    // Shop routes
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index'); // Route for shop page
    Route::post('/shop/redeem', [ShopController::class, 'redeem'])->name('shop.redeem'); // Route for redeem action
});
