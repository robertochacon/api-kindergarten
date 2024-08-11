<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthorizedPersonsController;
use App\Http\Controllers\KidController;
use App\Http\Controllers\ApplicantsController;
use App\Http\Controllers\ConcubinesController;
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

use App\Http\Controllers\AuthController;

Route::group(['middleware' => 'api'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});


Route::middleware(['auth:api'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    //kids
    Route::get('/kids/', [KidController::class, 'index']);
    Route::get('/kids/totales/', [KidController::class, 'totales']);
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

    //applicants
    Route::get('/applicants/', [ApplicantsController::class, 'index']);
    Route::get('/applicants/{id}/', [ApplicantsController::class, 'watch']);
    Route::post('/applicants/', [ApplicantsController::class, 'register']);
    Route::put('/applicants/{id}/', [ApplicantsController::class, 'update']);
    Route::delete('/applicants/{id}/', [ApplicantsController::class, 'delete']);

    //concubines
    Route::get('/concubines/', [ConcubinesController::class, 'index']);
    Route::get('/concubines/{id}/', [ConcubinesController::class, 'watch']);
    Route::post('/concubines/', [ConcubinesController::class, 'register']);
    Route::put('/concubines/{id}/', [ConcubinesController::class, 'update']);
    Route::delete('/concubines/{id}/', [ConcubinesController::class, 'delete']);

});
