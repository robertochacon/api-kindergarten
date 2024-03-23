<?php

use App\Http\Controllers\KidController;
use App\Http\Controllers\TutorsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//kids
Route::get('/kids/', [KidController::class, 'index']);
Route::get('/kids/{id}/', [KidController::class, 'watch']);
Route::post('/kids/', [KidController::class, 'register']);
Route::put('/kids/{id}/', [KidController::class, 'update']);
Route::delete('/kids/{id}/', [KidController::class, 'delete']);

//tutors
Route::get('/tutors/', [TutorsController::class, 'index']);
Route::get('/tutors/{id}/', [TutorsController::class, 'watch']);
Route::post('/tutors/', [TutorsController::class, 'register']);
Route::put('/tutors/{id}/', [TutorsController::class, 'update']);
Route::delete('/tutors/{id}/', [TutorsController::class, 'delete']);
