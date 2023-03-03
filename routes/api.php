<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([
    "prefix" => "auth"
], function () {
    Route::post("login", [UserController::class, "login"]);
    Route::post("register", [UserController::class, "register"]);

    Route::get("validate-token", function () {
        return response()->json([
            "valid" => true
        ], 200);
    })->middleware("auth:api");
});

Route::group([
    "prefix" => "users"
], function () {
    Route::get("/", [UserController::class, "list_users"]);
    Route::post("/create", [UserController::class, "store_user"]);
    Route::get("/read/{id}", [UserController::class, "get_user"]);
    Route::put("/update", [UserController::class, "update_user"]);
    Route::post("/delete/{id}", [UserController::class, "delete_user"]);
})->middleware("auth:api");
