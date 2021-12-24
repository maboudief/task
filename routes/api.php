<?php

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

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;

Route::post("/register",[AuthController::class,'register']);
Route::post("/login",[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group( function () {
   Route::post('updatetask/{id}', [TaskController::class, 'updateTask']);  
   Route::resource('tasks', TaskController::class);
 });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });




