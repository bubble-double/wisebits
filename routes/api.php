<?php

use App\Http\Controllers\API\StatisticsController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(
    [
        'namespace' => 'Api',
        'middleware' => 'api',
    ],
    static function() {
        Route::post('/statistics/{cityCode}', [StatisticsController::class, 'update'])->where('cityCode', '[a-z]+');
        Route::get('/statistics/', [StatisticsController::class, 'get']);
    }
);
