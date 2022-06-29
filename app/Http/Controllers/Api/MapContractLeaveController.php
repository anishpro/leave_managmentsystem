<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapContractLeave;
use Illuminate\Http\Request;

class MapContractLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(MapContractLeave $contract_leave)
    {
        $this->model = $contract_leave;
    }

    public function index()
    {
        return $this->model->with('mapContractLeave')->get();
    }
    public function choice()
    {
        return $this->model->pluck('leave_type', 'id');
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
            'contract_month' => 'required|between:1,12',
            'leave_type_id' => 'required',
            'leave_days' => 'required',
        ]);
        try {
            $data =  $this->model->create($request->all());

            $data['error']=false;
            $data['message']='Contract Successfully Mapped to Leave Type';
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
            'contract_month' => 'required|between:1,12',
            'leave_type_id' => 'required',
            'leave_days' => 'required',
        ]);
        try {
            $data =  $this->model->findOrFail($id);

            $data->update($request->all());
            $data['error']=false;
            $data['message']='Successfully Update Contract to Leave Type Map';
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
            $data['message']='Contract Leave Map '.$data->contract_month.' Has Been Deleted';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
        }

        return response()->json($data);
    }
}
