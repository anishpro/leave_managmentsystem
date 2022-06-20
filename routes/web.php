<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicHolidaysController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeContractController;
use App\Http\Controllers\EmployeePositionController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\MapContractLeaveController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\TravelDetailController;
use App\Http\Controllers\RequestTravelController;
use App\Http\Controllers\DutyStationController;
use App\Http\Controllers\MenuPermissionController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Rfoute::group(['middleware'=>'admin_auth'],function() {
Route::get('/', function () {
    return redirect('login');
});

// Route::get('profile', function () {
//     dd('verified')
// })->middleware('verified');

Route::group(['middleware'=>'auth'], function () {
    //DASHBOARD ROUTE
    Route::get('dashboard', [AdminController::class, 'dashboard']);
    Route::get('fetch-employee-report', [AdminController::class, 'show']);
    Route::get('logout', [AdminController::class, 'logout'])->name('logout');

    //GRUOP ROUTE
    Route::get('groups', [GroupController::class, 'index']);
    Route::get('add-group', [GroupController::class, 'create']);
    Route::post('insert-group', [GroupController::class, 'store']);
    Route::get('fetch-group', [GroupController::class, 'show']);
    Route::get('edit-group', [GroupController::class, 'edit']);
    Route::post('update-group', [GroupController::class, 'update']);
    Route::post('delete-group', [GroupController::class, 'destroy']);

    //POSITION ROUTE
    Route::get('positions', [EmployeePositionController::class, 'index']);
    Route::get('add-positions', [EmployeePositionController::class, 'create']);
    Route::post('insert-positions', [EmployeePositionController::class, 'store']);
    Route::get('fetch-positions', [EmployeePositionController::class, 'show']);
    Route::get('edit-positions', [EmployeePositionController::class, 'edit']);
    Route::post('update-positions', [EmployeePositionController::class, 'update']);
    Route::post('delete-positions', [EmployeePositionController::class, 'destroy']);

    //LEAVETYPE ROUTE
    Route::get('leaves', [LeaveTypeController::class, 'index']);
    Route::get('add-leavetype', [LeaveTypeController::class, 'create']);
    Route::post('insert-leavetype', [LeaveTypeController::class, 'store']);
    Route::get('fetch-leavetype', [LeaveTypeController::class, 'show']);
    Route::get('edit-leavetype', [LeaveTypeController::class, 'edit']);
    Route::post('update-leavetype', [LeaveTypeController::class, 'update']);
    Route::post('delete-leavetype', [LeaveTypeController::class, 'destroy']);

    //MAPCONTRACT TO LEAVETYPE ROUTE
    Route::get('map_contract_leave', [MapContractLeaveController::class, 'index']);
    Route::get('add-mapcontract', [MapContractLeaveController::class, 'create']);
    Route::post('insert-mapcontract', [MapContractLeaveController::class, 'store']);
    Route::get('fetch-mapcontract', [MapContractLeaveController::class, 'show']);
    Route::get('edit-mapcontract', [MapContractLeaveController::class, 'edit']);
    Route::post('update-mapcontract', [MapContractLeaveController::class, 'update']);
    Route::post('delete-mapcontract', [MapContractLeaveController::class, 'destroy']);
    
    //HOLIDAYS ROUTE
    Route::get('public_holidays', [PublicHolidaysController::class, 'index']);
    Route::get('add-holiday', [PublicHolidaysController::class, 'create']);
    Route::post('insert-holiday', [PublicHolidaysController::class, 'store']);
    Route::get('fetch-holiday', [PublicHolidaysController::class, 'show']);
    Route::get('view-holiday', [PublicHolidaysController::class, 'view']);
    Route::get('edit-holiday', [PublicHolidaysController::class, 'edit']);
    Route::post('update-holiday', [PublicHolidaysController::class, 'update']);
    Route::post('delete-holiday', [PublicHolidaysController::class, 'destroy']);
    
    //EMPLOYEE ROUTES OLD
    // Route::group(['prefix' => 'employees'], function () {
    //     Route::get('/', [EmployeeController::class, 'index']);
    //     Route::get('/create', [EmployeeController::class, 'create']);
    //     Route::post('/insert-employee', [EmployeeController::class, 'store']);
    //     Route::get('/fetch-employee', [EmployeeController::class, 'show']);
    //     Route::get('/edit/{id}', [EmployeeController::class, 'edit']);
    //     Route::post('/update-employee', [EmployeeController::class, 'update']);
    //     Route::post('delete-employee', [EmployeeController::class, 'destroy']);
    // });

    //EMPLOYEE CONTRACT ROUTE
    Route::get('employees', [EmployeeController::class, 'index']);
    Route::get('add-employee', [EmployeeController::class, 'create']);
    Route::post('insert-employee', [EmployeeController::class, 'store']);
    Route::get('fetch-employee', [EmployeeController::class, 'show']);
    Route::get('edit-employee', [EmployeeController::class, 'edit']);
    Route::post('update-employee', [EmployeeController::class, 'update']);
    Route::post('delete-employee', [EmployeeController::class, 'destroy']);

    //EMPLOYEE CONTRACT ROUTE
    Route::get('employee_contract', [EmployeeContractController::class, 'index']);
    Route::get('add-contract', [EmployeeContractController::class, 'create']);
    Route::post('insert-contract', [EmployeeContractController::class, 'store']);
    Route::get('fetch-contract', [EmployeeContractController::class, 'show']);
    Route::get('edit-contract', [EmployeeContractController::class, 'edit']);
    Route::post('update-contract', [EmployeeContractController::class, 'update']);
    Route::post('delete-contract', [EmployeeContractController::class, 'destroy']);

    //EMPLOYEE LEAVE APPLICATION ROUTE
    Route::group(['prefix' => 'application'], function () {
        Route::get('/', [LeaveApplicationController::class, 'index']);
        Route::get('/create', [LeaveApplicationController::class, 'create']);
        Route::post('/insert-application', [LeaveApplicationController::class, 'store']);
        Route::get('/fetch-leaveapplication', [LeaveApplicationController::class, 'show']);
        Route::get('/edit/{id}', [LeaveApplicationController::class, 'edit']);
        Route::post('/update-application', [LeaveApplicationController::class, 'update']);
        Route::post('/delete-leaveapplication', [LeaveApplicationController::class, 'destroy']);
        Route::get('/application_pdf/pdf/{id}', [LeaveApplicationController::class, 'pdf']);
    });

    //DUTY DEDUCTION
    Route::get('duty_deduction', [DeductionController::class, 'index']);
    Route::get('add-deduction', [DeductionController::class, 'create']);
    Route::post('insert-deduction', [DeductionController::class, 'store']);
    Route::get('fetch-deduction', [DeductionController::class, 'show']);
    Route::get('edit-deduction', [DeductionController::class, 'edit']);
    Route::post('update-deduction', [DeductionController::class, 'update']);
    Route::post('delete-deduction', [DeductionController::class, 'destroy']);

    //DUTY TRAVEL
    Route::group(['prefix' => 'travel'], function () {
        Route::get('/', [TravelDetailController::class, 'index']);
        Route::get('/add/{id}', [TravelDetailController::class, 'create']);
        Route::post('/save-travel', [TravelDetailController::class, 'store']);
        Route::get('/fetch-travel', [TravelDetailController::class, 'show']);
        Route::post('/get-deduction', [TravelDetailController::class, 'getDeduction']);
        Route::get('/edit/{travel_id}', [TravelDetailController::class, 'edit']);
        Route::post('/update-travel', [TravelDetailController::class, 'update']);
        Route::post('/delete-travel', [TravelDetailController::class, 'destroy']);
        Route::get('/dynamic_pdf/pdf/{id}', [TravelDetailController::class, 'pdf']);
    });

    //TRAVEL REQUEST
    Route::group(['prefix' => 'travel_request'], function () {
        Route::get('/', [RequestTravelController::class, 'index']);
        Route::get('/add', [RequestTravelController::class, 'create']);
        Route::get('/fetch_travelrequest', [RequestTravelController::class, 'show']);
        Route::get('/travel_pdf/pdf/{id}', [RequestTravelController::class, 'pdf']);
        Route::post('/insert-request-travel', [RequestTravelController::class, 'store']);
        Route::post('/get-remmendedby-employee-list', [RequestTravelController::class, 'getRecommendedEmployeeList']);
        Route::get('/edit/{id}', [RequestTravelController::class, 'edit']);
        Route::post('/update-request-travel', [RequestTravelController::class, 'update']);
    });

    //DUTY STATION
    Route::get('duty_station', [DutyStationController::class, 'index']);
    Route::get('add-station', [DutyStationController::class, 'create']);
    Route::post('insert-station', [DutyStationController::class, 'store']);
    Route::get('fetch-station', [DutyStationController::class, 'show']);
    Route::get('edit-station', [DutyStationController::class, 'edit']);
    Route::post('update-station', [DutyStationController::class, 'update']);
    Route::post('delete-station', [DutyStationController::class, 'destroy']);
    
    //MENU ROUTE
    // Route::get('menu', [MenuController::class, 'index']);
    // Route::get('add-menu', [MenuController::class, 'create']);
    // Route::post('insert-group', [MenuController::class, 'store']);
    // Route::get('fetch-group', [MenuController::class, 'show']);
    // Route::get('edit-group', [MenuController::class, 'edit']);
    // Route::post('update-group', [MenuController::class, 'update']);
    // Route::post('delete-group', [MenuController::class, 'destroy']);

    //ROLE PERMISSION
    Route::get('access_control', [MenuPermissionController::class, 'index']);
    Route::post('fetch-role-permiision', [MenuPermissionController::class, 'show']);
    Route::post('insert-role-permission', [MenuPermissionController::class, 'store']);

    //LEAVE REPORT
    Route::get('leave_report', [LeaveApplicationController::class, 'leaveReport']);
    Route::get('/leavereport_pdf/pdf/{id}/{leave_type}', [LeaveApplicationController::class, 'pdfReport']);
    Route::get('/admin/{path}', [AdminController::class, 'dashboard'])->where('path', '([A-z\/_.\d-]+)?');
});

//auth routes
Auth::routes();
Auth::routes(['verify' => true]);
