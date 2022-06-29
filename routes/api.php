<?php

use App\Http\Controllers\Api\ContractTypeController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\DutyStationController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\LeaveTypeController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ContractController;

use App\Models\User;
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


Route::group(
    ['middleware' => 'auth:api','namespace'=>'Api\\'],
    function () {
        Route::apiResources(['user'         =>'UserController']);

        Route::apiResources(['role'         =>'RoleController']);

        Route::apiResources(['permission'   =>'PermissionController']);
        Route::post('updatePassword', 'ProfileController@updatePassword');

        Route::apiResources(['profile'      =>'ProfileController']);

        // Route::apiResources(['profile'      =>'ProfileController']);
        Route::get('auth-permissions', [PermissionController::class, 'authPermissions']);
        Route::get('auth-roles', [RoleController::class, 'authRoles']);
        Route::get('auth-user', function () {
            return User::where('id', auth()->user()->id)->with('profile')->first();
        });

        Route::group(['middleware' => ['permission:update_profile']], function () {
            Route::put('admin_updated_profile/{id}', [ProfileController::class, 'adminUpdatedProfile']);
        });

        Route::apiResources(['groups' =>'GroupController']);
        Route::apiResources(['leave-types' =>'LeaveTypeController']);
        Route::apiResources(['contract-leave' =>'MapContractLeaveController']);
        Route::apiResources(['public-holiday' =>'PublicHolidayController']);
        Route::apiResources(['duty-station' =>'DutyStationController']);
        Route::apiResources(['designation' =>'DesignationController']);
        Route::apiResources(['contract-type' =>'ContractTypeController']);
        Route::apiResources(['contract' =>'ContractController']);



        Route::get('choice-role', [RoleController::class, 'choice']);
        Route::get('choice-group', [GroupController::class, 'choice']);
        Route::get('choice-permission', [PermissionController::class, 'choice']);
        Route::get('choice-leave-type', [LeaveTypeController::class, 'choice']);
        Route::get('choice-duty-station', [DutyStationController::class, 'choice']);
        Route::get('choice-designation', [DesignationController::class, 'choice']);
        Route::get('choice-supervisor', [SupervisorController::class, 'index']);
        Route::get('choice-contract-type', [ContractTypeController::class, 'choice']);
    }
);
