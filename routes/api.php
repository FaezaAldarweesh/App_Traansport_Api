<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\BusController;
use App\Http\Controllers\ApiController\AuthController;
use App\Http\Controllers\ApiController\PathController;
use App\Http\Controllers\ApiController\TripController;
use App\Http\Controllers\ApiController\UserController;
use App\Http\Controllers\ApiController\DriverController;
use App\Http\Controllers\ApiController\StationController;
use App\Http\Controllers\ApiController\StudentController;
use App\Http\Controllers\ApiController\SupervisorController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

Route::group(['middleware' => ['auth:api']], function () {
    // protected routes go here
    Route::post('logout',[AuthController::class ,'logout']); 
    Route::post('refresh', [AuthController::class ,'refresh']);
    
    Route::apiResource('user',UserController::class); 
    Route::get('all_trashed_user', [UserController::class, 'all_trashed_user']);
    Route::get('restore_user/{user_id}', [UserController::class, 'restore']);
    Route::delete('forceDelete_user/{user_id}', [UserController::class, 'forceDelete']);

    Route::apiResource('student',StudentController::class); 
    Route::get('all_trashed_student', [StudentController::class, 'all_trashed_student']);
    Route::get('restore_student/{student_id}', [StudentController::class, 'restore']);
    Route::delete('forceDelete_student/{student_id}', [StudentController::class, 'forceDelete']);

    Route::apiResource('supervisor',SupervisorController::class); 
    Route::get('all_trashed_supervisor', [SupervisorController::class, 'all_trashed_supervisor']);
    Route::get('restore_supervisor/{supervisor_id}', [SupervisorController::class, 'restore']);
    Route::delete('forceDelete_supervisor/{supervisor_id}', [SupervisorController::class, 'forceDelete']);

    Route::apiResource('driver',DriverController::class); 
    Route::get('all_trashed_driver', [DriverController::class, 'all_trashed_driver']);
    Route::get('restore_driver/{driver_id}', [DriverController::class, 'restore']);
    Route::delete('forceDelete_driver/{driver_id}', [DriverController::class, 'forceDelete']);

    Route::apiResource('bus',BusController::class); 
    Route::get('all_trashed_bus', [BusController::class, 'all_trashed_bus']);
    Route::get('restore_bus/{bus_id}', [BusController::class, 'restore']);
    Route::delete('forceDelete_bus/{bus_id}', [BusController::class, 'forceDelete']);

    Route::apiResource('path',PathController::class); 
    Route::get('all_trashed_path', [PathController::class, 'all_trashed_path']);
    Route::get('restore_path/{path_id}', [PathController::class, 'restore']);
    Route::delete('forceDelete_path/{path_id}', [PathController::class, 'forceDelete']);

    Route::apiResource('station',StationController::class); 
    Route::get('all_trashed_station', [StationController::class, 'all_trashed_station']);
    Route::get('restore_station/{station_id}', [StationController::class, 'restore']);
    Route::delete('forceDelete_station/{station_id}', [StationController::class, 'forceDelete']);

    Route::apiResource('trip',TripController::class); 
    Route::get('all_trashed_trip', [TripController::class, 'all_trashed_trip']);
    Route::get('restore_trip/{trip_id}', [TripController::class, 'restore']);
    Route::delete('forceDelete_trip/{trip_id}', [TripController::class, 'forceDelete']);
    Route::patch('update_trip_status/{trip_id}', [TripController::class, 'update_trip_status']);
});
