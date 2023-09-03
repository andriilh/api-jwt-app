<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ChecklistItemController;
use App\Models\ChecklistItem;
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

Route::get('/', function () {
    $data = [
        'message' => "Welcome to our API",
        'application' => "Laravel V." . app()->version()
    ];
    return response()->json($data, 200);
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::get('refresh', 'refresh');
        Route::get('me', 'getUser');
        Route::post('logout', 'logout');
    });
});

Route::group(['middleware' => 'jwt.verify'], function () {
    Route::prefix('checklist')->group(function () {
        Route::controller(ChecklistController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::delete('/{id}', 'destroy');
        });
        Route::controller(ChecklistItemController::class)->group(function () {
            Route::get('/{checklistId}/item', 'index');
            Route::post('/{checklistId}/item', 'store');
            Route::get('/{checklistId}/item/{checklistItemId}', 'show');
            Route::put('/{checklistId}/item/{checklistItemId}', 'updateStatus');
            Route::delete('/{checklistId}/item/{checklistItemId}', 'destroy');
            Route::put('/{checklistId}/rename/{checklistItemId}', 'rename');
        });
    });
});

Route::middleware('jwt.verify')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Welcome to dashboard'], 200);
    });
});
