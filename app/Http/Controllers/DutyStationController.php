<?php

namespace App\Http\Controllers;

use App\Models\DutyStation;
use Illuminate\Http\Request;

class DutyStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/duty_station'); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $content = $title='';

        $title .="Add New Duty Station";

        $content .='<form id="station_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Duty Station <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="duty_station" id="duty_station" placeholder="Enter Duty Station" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_station">Submit</button>
                        </div> 
                    </div>
                </form>';

        $output = array(
            'content' => $content,
            'title' => $title
        );
        echo json_encode($output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sql_check = DutyStation::where('work_place', $request->input('duty_station'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new DutyStation();
            $model->work_place = $request->input('duty_station');
            $model->save();

            if($model) { 
                $signal = "inserted";
            }else{
                $signal = "not_inserted";
            }
        }else {
            $signal = "exist";
        }
        return response()->json($signal);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DutyStation  $dutyStation
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allStations = DutyStation::all();
        $output = '';

        if($allStations->count() > 0) {
            foreach($allStations as $value) { 
                $output .= '<tr id="'.$value->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input station_checkbox" type="checkbox" id="chk-'.$value->id.'" name="station_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($value->work_place).'</td>
                                <td>
                                    <a class="text-warning station_edit" href="javascript:void(0)" data-id="'.$value->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        
        echo $output;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DutyStation  $dutyStation
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $stationId = $request->station_id;
        $dutyStation = DutyStation::find($stationId);

        $content = $title='';

        $title .="Edit Duty Station";

        $content .='<form id="edit_station_form">
                    <div class="result_group"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Duty Station <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_station" id="edit_station" value="'.$dutyStation->work_place.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="edit_station_id" id="edit_station_id" value="'.$dutyStation->id.'">
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_station">Submit</button>
                        </div> 
                    </div>
                </form>';

        $output = array(
            'content' => $content,
            'title' => $title
        );
        echo json_encode($output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DutyStation  $dutyStation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $station = DutyStation::find($request->edit_station_id);
        
        $sql_check = DutyStation::where('work_place', $request->input('edit_station'))->where('id', '!=', $request->input('edit_station_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $station->work_place=$request->input('edit_station');
            $station->save();

            if($station) { 
                $signal = "updated";
            }else {
                $signal = "not_updated";
            }
        }else {
            $signal = "exist";
        }
       
        return response()->json($signal);
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DutyStation  $dutyStation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $stationIds = $request->station_id;
        DutyStation::whereIn('id', $stationIds)->delete();
        return 'success';
    }
}
