<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleValidation as RoleStoreRoleValidation;
use App\Http\Requests\Role\UpdateRoleValidation as RoleUpdateRoleValidation;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $panel = 'Roles';
    protected $guard_name = 'api';

    public function __construct(Role $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['roles'] = $this->model->with('permissions:name')->get();
        $data['permissions']= Permission::select('name', 'name')->get();
        return ([
            'roles' => $data['roles'],
            'permissions' => $data['permissions'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleStoreRoleValidation $request)
    {
        $data['error']='true';
        try {
            $role = $this->model->create(['name'=>$request['name']]);
            $permissions= $request['permissions'];
            $role->syncPermissions($permissions);

            $data['error']='false';
            $data['message']='Role '.$role->name.' Has Been Created';
        } catch (Exception $exception) {
            $data['message']='Something went Wrong!';
        }

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRoleValidation $request, $id)
    {
        $data['error']='true';

        try {
            $role = $this->model->findOrFail($id);

            $data['error']='false';
            $role->name = $request['name'];
            $role->update();

            $permissions= $request['permissions'];
            $role->syncPermissions($permissions);

            $data['message']='Role Info! Has Been Updated';
        } catch (\Exception $exception) {
            $data['error']='true';
            $data['message']=$exception->getMessage();
        }

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response=[];
        $response['error']='true';
        try {
            $role = $this->model->findOrFail($id);
            
            $role->delete();

            $response['error']='false';
            $response['message']='Role Deleted';
        } catch (\Exception $e) {
            $response['message']=$e->getMessage();
        }
        return $response;
    }
    public function authRoles()
    {
        return auth()->user()->getRoleNames();
    }
}
