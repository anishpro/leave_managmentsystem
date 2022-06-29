<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Group $group)
    {
        $this->model = $group;
    }

    public function index()
    {
        return $this->model->paginate();
    }
    public function choice()
    {
        return $this->model->pluck('group_name', 'id');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'group_name' => 'required|unique:groups',
        ]);
        try {
            $data =  $this->model->create($request->all());

            $data['error']=false;
            $data['message']='Group '.$data->group_name.' Has Been Created';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
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
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'group_name' => 'required|unique:groups,group_name,'.$id,
        ]);
        try {
            $data =  $this->model->findOrFail($id);
            $data->update();
            $data['error']=false;
            $data['message']='Group '.$data->group_name.' Has Been Updated';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
        }
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
            $data =  $this->model->findOrFail($id);

            $data->delete();

            $data['error']=false;
            $data['message']='Group '.$data->group_name.' Has Been Deleted';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
        }

        return response()->json($data);
    }
}
