<?php

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

//employees
Route::get('/employees/get', [\App\Http\Controllers\EmployeeController::class, 'index']);
Route::post('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'store']);
Route::post('/employees/update', [\App\Http\Controllers\EmployeeController::class, 'update']);
Route::post('/employees/delete', [\App\Http\Controllers\EmployeeController::class, 'destroy']);

//departments
Route::get('/departments/get', [\App\Http\Controllers\DepartmentController::class, 'index']);
Route::post('/departments/create', [\App\Http\Controllers\DepartmentController::class, 'store']);
Route::post('/departments/update', [\App\Http\Controllers\DepartmentController::class, 'update']);
Route::post('/departments/delete', [\App\Http\Controllers\DepartmentController::class, 'destroy']);

//positions
Route::get('/positions/get', [\App\Http\Controllers\PositionController::class, 'index']);
Route::post('/positions/create', [\App\Http\Controllers\PositionController::class, 'store']);
Route::post('/positions/update', [\App\Http\Controllers\PositionController::class, 'update']);
Route::post('/positions/delete', [\App\Http\Controllers\PositionController::class, 'destroy']);

//companies
Route::get('/companies/get', [\App\Http\Controllers\CompanyController::class, 'index']);
Route::post('/companies/create', [\App\Http\Controllers\CompanyController::class, 'store']);
Route::post('/companies/update', [\App\Http\Controllers\CompanyController::class, 'update']);
Route::post('/companies/delete', [\App\Http\Controllers\CompanyController::class, 'destroy']);