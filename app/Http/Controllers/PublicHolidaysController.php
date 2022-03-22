<?php

namespace App\Http\Controllers;

use App\Models\PublicHolidays;
use App\Models\Group;
use Illuminate\Http\Request;

class PublicHolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/public_holidays');
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

        $title .="Add New Holiday";

        $allGroups = Group::all();

        $content .='<form id="holiday_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="holiday" id="holiday" placeholder="Enter Holiday" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday Description</label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="holiday_desc" id="holiday_desc" placeholder="Enter Holiday Description"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="holiday_date" id="holiday_date" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Groups <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="group_ids[]" id="group_ids" required data-parsley-errors-container=".productError" title="-- Select Groups --" multiple>';
                                foreach($allGroups as $value){
                                    $content .='<option value="'.$value->id.'">'.ucfirst($value->group_name).'</option>';
                                }
                $content .='</select>
                            <div class="productError"></div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_holiday">Submit</button>
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
        $sql_check = PublicHolidays::where('leave_date', '=', $request->input('holiday_date'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new PublicHolidays();
            $model->holidays = $request->input('holiday');
            $model->description = $request->input('holiday_desc');
            $model->leave_date = $request->input('holiday_date');
            $model->group_id = implode(',', $request->input('group_ids'));
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
     * @param  \App\Models\PublicHolidays  $publicHolidays
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allPublicHolidays = PublicHolidays::all();
        $output = '';

        if($allPublicHolidays->count() > 0) {
            foreach($allPublicHolidays as $holiday) { 
                $output .= '<tr id="'.$holiday->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input holiday_checkbox" type="checkbox" id="chk-'.$holiday->id.'" name="holiday_checkbox[]" value="'.$holiday->id.'">
                                        <label class="form-check-label" for="chk-'.$holiday->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($holiday->holidays).'</td>
                                <td>'.date('d M, Y', strtotime($holiday->leave_date)).'</td> 
                                <td>
                                    <a class="text-warning holiday_view" href="javascript:void(0)" tooltip="View Detail" data-id="'.$holiday->id.'"><i class="bx bxs-show bx-sm"></i></a>
                                    <a class="text-warning holiday_edit" href="javascript:void(0)" tooltip="Edit" data-id="'.$holiday->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo $output;
    }

    //VIEW HOLIDAY DETAIL
    public function view(Request $request)
    {
        $holidayId = $request->holiday_id;
        $holiday = PublicHolidays::find($holidayId);

        $allGroups = explode(",", $holiday->group_id);
        foreach($allGroups as $group){
            $group = Group::find($group);

            $groupName = $group->group_name.", ";
        }

        $groupName = substr($groupName, 0, -2);
        

        $content = $title='';

        $title .="View Holiday Detail";

        $content .='<h6>Name : '.ucwords($holiday->holidays).'</h6>
                    <p><b>Date :</b> '.$holiday->leave_date.'</p>
                    <p><b>Groups :</b> '.$groupName.'</p>
                    <div>
                        <p><b>Description</b></p>
                        '.$holiday->description.'
                    </div>';

        $output = array(
            'content' => $content,
            'title' => $title
        );
        echo json_encode($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PublicHolidays  $publicHolidays
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $holidayId = $request->holiday_id;
        $holiday = PublicHolidays::find($holidayId);

        $allGroups = Group::all();

        $content = $title='';

        $title .="Edit Group";

        $content .='<form id="edit_holiday_form">
                    <div class="result_holiday"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_holiday" id="edit_holiday" placeholder="Enter Holiday" value="'.$holiday->holidays.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="holiday_id" id="holiday_id" value="'.$holiday->id.'">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday Description</label>
                        <div class="col-md-12">
                            <textarea class="form-control" name="holiday_desc" id="holiday_desc" rows="4" placeholder="Enter Holiday Description">'.$holiday->description.'</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Holiday Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="holiday_date" id="holiday_date" value="'.$holiday->leave_date.' placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Groups <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="group_ids[]" id="group_ids" required data-parsley-errors-container=".productError" title="-- Select Groups --" multiple>';
                                foreach($allGroups as $value){
                                    $content .='<option value="'.$value->id.'"'; if(in_array($value->id, explode(",", $holiday->group_id))) $content .='selected';$content .='>'.ucfirst($value->group_name).'</option>';
                                }
                $content .='</select>
                            <div class="productError"></div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_holiday">Submit</button>
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
     * @param  \App\Models\PublicHolidays  $publicHolidays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $holiday = PublicHolidays::find($request->holiday_id);
        
        $sql_check = PublicHolidays::where('leave_date', '=', $request->input('holiday_date'))->where('id', '!=', $request->input('holiday_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $holiday->holidays = $request->input('edit_holiday');
            $holiday->description = $request->input('holiday_desc');
            $holiday->leave_date = $request->input('holiday_date');
            $holiday->group_id = implode(',', $request->input('group_ids'));
            $holiday->save();

            if($holiday) { 
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
     * @param  \App\Models\PublicHolidays  $publicHolidays
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $holidayIds = $request->holiday_id;
        PublicHolidays::whereIn('id', $holidayIds)->delete();
        return 'success';
    }
}
