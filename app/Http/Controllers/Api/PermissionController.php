<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionValidation;
use App\Http\Requests\Permission\UpdatePermissionValidation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $panel = 'Permissions';
    protected $guard_name = 'api';

    public function __construct(Permission $model)
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
        $data =[];
        $data['permissions']= $this->model->select('name', 'guard_name', 'id')->latest()->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionValidation $request)
    {
        $data['error']='true';
        try {
            $permission =  $this->model->create(['name'=>$request['name'],'guard_name'=>$request['guard_name']]);

            $data['error']='false';
            $data['message']='Permission '.$permission->name.' Has Been Created';
        } catch (\Exception $exception) {
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
    public function update(UpdatePermissionValidation $request, $id)
    {
        $data=[];
        $data['error']='true';

        try {
            $permission =  $this->model->findOrFail($id);

            $data['error']='false';
            $permission->name = $request['name'];
            $permission->guard_name=$request['guard_name'];
            $permission->update();

            $data['message']='Permission Info! Has Been Updated';
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
        try {
            $permission =  $this->model->findOrFail($id);
            //delete the permission
            $permission->delete();
        } catch (\Exception $e) {
            return ['message' =>$e->getMessage()];
        }

        return ['message' =>'Permission Deleted'];
    }
    public function authPermissions()
    {
        return auth()->user()->getAllPermissions()->pluck('name');
    }
}
