<?php
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RegisterUSer;
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



Route::middleware('auth:sanctum')->group(function () {

    Route::get('posts', [BankController::class, 'index']);

    Route::post('posts/create', [BankController::class, 'store']);

    Route::put('posts/edit/{post}', [BankController::class, 'update']);

    Route::post('posts/creates/{id}', [BankController::class, 'transaction']);


    Route::get('/user', function(Request $request){

        return $request->user();

    });
});
