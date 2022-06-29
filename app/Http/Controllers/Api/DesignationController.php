<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Designation $designation)
    {
        $this->model = $designation;
    }

    public function index()
    {
        return $this->model->paginate();
    }

    public function choice()
    {
        return $this->model->pluck('name', 'id');
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
            'name' => 'required|unique:designations',
        ]);
        try {
            $data =  $this->model->create($request->all());
            $data['error']=false;
            $data['message']='Designation '.$data->name.' Has Been Created';
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
            'name' => 'required|unique:designations,name,'.$id,
        ]);
        try {
            $data =  $this->model->findOrFail($id);
            $data->update($request->all());
            $data['error']=false;
            $data['message']='Designation '.$data->name.' Has Been Updated';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
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
            $data =  $this->model->findOrFail($id);
            $data->delete();
            $data['error']=false;
            $data['message']='Designation '.$data->name.' Has Been Deleted';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
        }

        return response()->json($data);
    }
}
