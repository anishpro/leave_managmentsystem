<?php

use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
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
        Route::get('auth_permissions', [PermissionController::class, 'authPermissions']);
        Route::get('auth_roles', [RoleController::class, 'authRoles']);
        Route::get('auth_user', function () {
            return auth()->user()->with('profile')->first();
        });
    }
);
