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
//login
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

//logout
Route::middleware('auth:sanctum')->post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout']);


//check role
Route::middleware(['auth:sanctum', 'role:developer|manager|admin|hr|treasurer'])->group(function () {

        //employees
    Route::get('/employees/get', [\App\Http\Controllers\EmployeeController::class, 'index']);
    Route::post('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'store']);
    Route::post('/employees/update', [\App\Http\Controllers\EmployeeController::class, 'update']);
    Route::post('/employees/delete', [\App\Http\Controllers\EmployeeController::class, 'destroy']);
    //get employee by id
    Route::get('/employees/get/{id}', [\App\Http\Controllers\EmployeeController::class, 'getEmployeeById']);
    Route::get('/employees/getId/{id}', [\App\Http\Controllers\EmployeeController::class, 'getEmployeeId']);
    //departments
    Route::get('/departments/get', [\App\Http\Controllers\DepartmentController::class, 'index']);
    Route::post('/departments/create', [\App\Http\Controllers\DepartmentController::class, 'store']);
    Route::post('/departments/update', [\App\Http\Controllers\DepartmentController::class, 'update']);
    Route::post('/departments/delete', [\App\Http\Controllers\DepartmentController::class, 'destroy']);
    //get department by id
    Route::get('/departments/get/{id}', [\App\Http\Controllers\DepartmentController::class, 'getDepartmentById']);
    //get employee by department id
    Route::get('/employees/get/{id}', [\App\Http\Controllers\EmployeeController::class, 'getByDepartment']);
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


    //tabel
    Route::get('/tabel/get', [\App\Http\Controllers\TabelController::class, 'index']);

    //workdays
    Route::get('/workdays/get', [\App\Http\Controllers\WorkdayController::class, 'index']);
    Route::post('/workdays/create', [\App\Http\Controllers\WorkdayController::class, 'store']);
    Route::post('/workdays/update', [\App\Http\Controllers\WorkdayController::class, 'update']);
    Route::post('/workdays/delete', [\App\Http\Controllers\WorkdayController::class, 'destroy']);

    //holidays
    Route::get('/holidays/get', [\App\Http\Controllers\HolidayController::class, 'index']);
    Route::post('/holidays/create', [\App\Http\Controllers\HolidayController::class, 'store']);
    Route::post('/holidays/update', [\App\Http\Controllers\HolidayController::class, 'update']);
    Route::post('/holidays/delete', [\App\Http\Controllers\HolidayController::class, 'destroy']);

    //users
    Route::get('/users/get', [\App\Http\Controllers\UserController::class, 'index']);
    Route::post('/users/create', [\App\Http\Controllers\UserController::class, 'store']);
    Route::post('/users/update', [\App\Http\Controllers\UserController::class, 'update']);
    Route::post('/users/delete', [\App\Http\Controllers\UserController::class, 'destroy']);

    Route::get('/roles/get', [\App\Http\Controllers\UserController::class, 'getAllRoles']);
    Route::post('/roles/assign', [\App\Http\Controllers\UserController::class, 'assignRole']);

    //shifts
    Route::get('/shifts/get', [\App\Http\Controllers\ShiftController::class, 'index']);
    Route::post('/shifts/create', [\App\Http\Controllers\ShiftController::class, 'store']);
    Route::post('/shifts/update', [\App\Http\Controllers\ShiftController::class, 'update']);
    Route::post('/shifts/delete', [\App\Http\Controllers\ShiftController::class, 'destroy']);

    Route::post('/shifts/assign', [\App\Http\Controllers\ShiftController::class, 'generateWorkdays']);


    //groups
    Route::get('/groups/get', [\App\Http\Controllers\GroupController::class, 'index']);
    Route::post('/groups/create', [\App\Http\Controllers\GroupController::class, 'store']);
    Route::post('/groups/update', [\App\Http\Controllers\GroupController::class, 'update']);
    Route::post('/groups/delete', [\App\Http\Controllers\GroupController::class, 'destroy']);

    //absences

    Route::get('/absences/get', [\App\Http\Controllers\AbsenceController::class, 'index']);
    Route::post('/absences/create', [\App\Http\Controllers\AbsenceController::class, 'store']);
    Route::post('/absences/update', [\App\Http\Controllers\AbsenceController::class, 'update']);
    Route::post('/absences/delete', [\App\Http\Controllers\AbsenceController::class, 'destroy']);

    //DepartmentEmployee
    Route::post('/zams/create', [\App\Http\Controllers\DepartmentEmployeeController::class, 'store']);
    Route::post('/zams/delete', [\App\Http\Controllers\DepartmentEmployeeController::class, 'destroy']);

    //getGroupsByDepartmentId
    Route::get('/groups/get/{id}', [\App\Http\Controllers\GroupController::class, 'getGroupsByDepartmentId']);
    

    //cities
    Route::get('/cities/get', [\App\Http\Controllers\CityController::class, 'index']);
    Route::post('/cities/create', [\App\Http\Controllers\CityController::class, 'store']);
    Route::post('/cities/update', [\App\Http\Controllers\CityController::class, 'update']);
    Route::post('/cities/delete', [\App\Http\Controllers\CityController::class, 'destroy']);
});