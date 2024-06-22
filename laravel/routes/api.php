<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\SharedListController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return response()->json($request->user());
});

require __DIR__.'/auth.php';

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(['prefix' => 'lists'], function () {
        Route::get('/', [ListController::class, 'getLists']);
        Route::post('/', [ListController::class, 'createList']);
        Route::put('/{id}', [ListController::class, 'updateList']);
        Route::delete('/{id}', [ListController::class, 'deleteList']);
    });

    Route::group(['prefix' => 'items'], function () {
        Route::get('/', [ItemController::class, 'getItems']);
        Route::post('/', [ItemController::class, 'createItem']);
        Route::post('/{id}', [ItemController::class, 'makAsDone']);
        Route::put('/{id}', [ItemController::class, 'updateItem']);
        Route::delete('/{id}', [ItemController::class, 'deleteItem']);
        Route::get('list', [ItemController::class, 'getItemsList']);
    });

    Route::group(['prefix' => 'shared-list'], function () {
        Route::get('/', [SharedListController::class, 'getSharedListUsers']);
        Route::post('/share', [SharedListController::class, 'share']);
        Route::post('/remove', [SharedListController::class, 'removeShare']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('/edit', [UserController::class, 'editProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::post('/change-email', [UserController::class, 'changeEmail']);
    });
});
