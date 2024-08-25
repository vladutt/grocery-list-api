<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SharedListController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();

    return response()->json([
            'email' => $user->email,
            'name' => $user->name,
            'avatar' => $user->avatarPath,
            'id' => $user->id
        ]);
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

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [NotificationController::class, 'notifications']);
        Route::get('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    });
});

Route::get('/google', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');
