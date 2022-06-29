<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(PublicHoliday $public_holidays)
    {
        $this->model = $public_holidays;
    }

    public function index()
    {
        return $this->model->with('groups')->paginate();
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
            'holyday_name' => 'required|unique:public_holidays',
            'leave_date' => 'required',
        ]);
        try {
            $data =  $this->model->create($request->all());
            $data->groups()->attach($request->groups);

            $data['error']=false;
            $data['message']='Public Holiday '.$data->holiday_name.' Has Been Created';
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
            'holiday_name' => 'required|unique:public_holidays,holiday_name,'.$id,
            'leave_date' => 'required',
        ]);
        try {
            $data =  $this->model->findOrFail($id);

            $data->update($request->all());

            try {
                $data->groups()->sync($request->group_ids);
            } catch (\Exception $e) {
                $data['message']= $e->getMessage();
                $data['error']=true;

                return $data;
            }


            $data['error']=false;
            $data['message']='Public Holiday '.$data->holiday_name.' Has Been Updated';
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
            $data['message']='Public Holiday '.$data->holiday_name.' Has Been Deleted';
        } catch (\Exception $exception) {
            $data['message']= $exception->getMessage();
            $data['error']=true;
        }

        return response()->json($data);
    }
}
