<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource("categories", CategoryController::class);
    Route::apiResource("apps", AppController::class);
    Route::post("/sign-out", [AuthController::class, "signOut"]);
});

Route::middleware("guest:sanctum")->group(function () {
    Route::post("/sign-in", [AuthController::class, "signIn"]);
    Route::post("/sign-up", [AuthController::class, "signUp"]);
});
