<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\CheckRoomMembership;
use App\Http\Middleware\RoomAccessMiddleware;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout',   [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::middleware(['admin.access'])->group(function () {
        Route::resource("/room", RoomController::class)->only(["update", "destroy"]);
        Route::post("/access", [AccessController::class, 'store']);
    });
    Route::put("/access/{access}", [AccessController::class, 'update']);
    Route::resource("/room", RoomController::class)->only(["show", "index"])->middleware(RoomAccessMiddleware::class);
    // Route::post("/room", [RoomController::class,"store"]);

    Route::post("/room", [RoomController::class, "store"]);
    Route::resource("room.post", PostController::class)->middleware(CheckRoomMembership::class);
    Route::get("/user/query", [UsersController::class, 'index']);
    Route::get("/user/room", [RoomController::class, "personal_index"]);
    Route::get("/user/pending", [RoomController::class, "personal_to_accept"]);

    Route::post("/user/markasread", [UsersController::class, 'markAsRead']);
    Route::post("/user/markallasread", [UsersController::class, 'markAllAsRead']);
    Route::post("/user/setimage", [UsersController::class, 'setImage']);
});



Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);
