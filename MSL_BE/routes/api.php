<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FinalRanksController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\PhotoController;
use App\Http\Middleware\EnsureTokenIsValid;

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

Route::middleware('auth:api')->get('/a t', function (Request $request) {
    return $request->user();
});


Route::get('/teams', [TeamController::class, 'index']);
Route::get('/teams/seasonly', [TeamController::class, 'getTeamsSeasonly']);
Route::get('/teams/active', [TeamController::class, 'getActiveTeam']);
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/final/updateDate', [GameController::class, 'updateFinalDate']);
Route::get('/statsCounts', [GameController::class, 'getStatsCounts']);
Route::get('/stats', [GameController::class, 'getStats']);
Route::get('/seasons', [SeasonController::class, 'index']);
Route::get('/players', [PlayerController::class, 'index']);
Route::get('/players/active', [PlayerController::class, 'getActivePlayers']);
Route::get('/finalRanks', [FinalRanksController::class, 'index']);
Route::get('/photos', [PhotoController::class, 'index']);


Route::middleware([EnsureTokenIsValid::class])->group(function () {
    Route::prefix('/team/active')->group(function () {
        Route::delete('/{id}', [TeamController::class, 'teamActiveDestroy']);
        Route::post('/store', [TeamController::class, 'teamActiveStore']);
    });

    Route::prefix('/game')->group(function () {
        Route::put('/{id}', [GameController::class, 'update']);
        Route::delete('/{id}', [GameController::class, 'destroy']);
        Route::post('/store', [GameController::class, 'store']);
    });

    Route::prefix('/stat')->group(function () {
        Route::put('/{id}', [GameController::class, 'updateStat']);
        Route::delete('/{id}', [GameController::class, 'destroyStat']);
        Route::post('/store', [GameController::class, 'storeStat']);
    });

    Route::prefix('/season')->group(function () {
        Route::post('/store', [SeasonController::class, 'store']);
        Route::put('/{id}', [SeasonController::class, 'update']);
        Route::put('/active/{id}', [SeasonController::class, 'updateActive']);
    });


    Route::prefix('/player')->group(function () {
        Route::post('/store', [PlayerController::class, 'store']);
        Route::delete('/{id}', [PlayerController::class, 'destroy']);
        Route::put('/{id}', [PlayerController::class, 'update']);
    });

    Route::prefix('/rank')->group(function () {
        Route::delete('/{id}', [FinalRanksController::class, 'destroy']);
        Route::post('/store', [FinalRanksController::class, 'store']);
    });
});
Route::post('/photos/{id}/store', [PhotoController::class, 'store']);


Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'getUser']);
