<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
Route::prefix('auth')->group(function () {
    Route::get("/register", [AuthController::class, "getRegister"])->name("register");
    Route::post("/register", [AuthController::class, "postRegister"])->name("post.register");
    Route::get("/login", [AuthController::class, "getLogin"])->name("login");
    Route::post("/login", [AuthController::class, "postLogin"])->name("post.login");
    Route::get("/logout", [AuthController::class, "getLogout"])->name("logout");
});
Route::middleware('auth')->group(function () {
    Route::get("/", [TodoController::class, "index"])->name("home");
    Route::prefix('todo')->group(function () {
        Route::post("/add", [TodoController::class, "postAdd"])->name("post.todo.add");
        Route::post("/edit", [TodoController::class, "postEdit"])->name("post.todo.edit");
        Route::post("/delete", [TodoController::class, "postDelete"])->name("post.todo.delete");
        Route::post('/todo/{id}/increment-progress', [TodoController::class, 'incrementProgress'])->name('todo.increment-progress');

    });
});