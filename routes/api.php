<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthorizedPersonsController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\ParentsController;
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

//authorizations
Route::get('/authorizations/', [AuthorizedPersonsController::class, 'index']);
Route::get('/authorizations/{id}/', [AuthorizedPersonsController::class, 'watch']);
Route::post('/authorizations/', [AuthorizedPersonsController::class, 'register']);
Route::put('/authorizations/{id}/', [AuthorizedPersonsController::class, 'update']);
Route::delete('/authorizations/{id}/', [AuthorizedPersonsController::class, 'delete']);

//attendances
Route::get('/attendances/', [AttendanceController::class, 'index']);
Route::get('/attendances/{id}/', [AttendanceController::class, 'watch']);
Route::post('/attendances/', [AttendanceController::class, 'register']);
Route::put('/attendances/{id}/', [AttendanceController::class, 'update']);
Route::delete('/attendances/{id}/', [AttendanceController::class, 'delete']);

//parents
Route::get('/parents/', [ParentsController::class, 'index']);
Route::get('/parents/{id}/', [ParentsController::class, 'watch']);
Route::post('/parents/', [ParentsController::class, 'register']);
Route::put('/parents/{id}/', [ParentsController::class, 'update']);
Route::delete('/parents/{id}/', [ParentsController::class, 'delete']);
