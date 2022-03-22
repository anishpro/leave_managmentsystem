<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/leaves');
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

        $title .="Add New Leave Type";

        $content .='<form id="leavetype_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Leave Type <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="leavetype" id="leavetype" placeholder="Enter Group Name" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Mapping Required ? <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="mapping_required" id="mapping_required" required data-parsley-errors-container=".mappingError" title="-- Select Option --">
                                <option value="no" selected>No</option>
                                <option value="yes">Yes</option>
                            </select>
                            <div class="mappingError"></div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_leavetype">Submit</button>
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
        $sql_check = LeaveType::where('leave_type', '=', $request->input('leavetype'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new LeaveType();
            $model->leave_type = $request->input('leavetype');
            $model->mapping_required = $request->input('mapping_required');
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
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allLeaveTypes = LeaveType::all();
        $output = '';

        if($allLeaveTypes->count() > 0) {
            foreach($allLeaveTypes as $leavetype) { 
                $output .= '<tr id="'.$leavetype->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input leavetype_checkbox" type="checkbox" id="chk-'.$leavetype->id.'" name="leavetype_checkbox[]" value="'.$leavetype->id.'">
                                        <label class="form-check-label" for="chk-'.$leavetype->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($leavetype->leave_type).'</td>
                                <td>'.date('d M, Y', strtotime($leavetype->created_at)).'</td> 
                                <td>
                                <a class="text-warning leavetype_edit" href="javascript:void(0)" data-id="'.$leavetype->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $leavetypeId = $request->leavetype_id;
        $leavetype = LeaveType::find($leavetypeId);

        $content = $title='';

        $title .="Edit Leave Type";

        $content .='<form id="edit_leavetype_form">
                    <div class="result_leavetype"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Leave Type <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_leavetype_name" id="edit_leavetype_name" value="'.$leavetype->leave_type.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="edit_leavetype_id" id="edit_leavetype_id" value="'.$leavetype->id.'">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Mapping Required ? <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="edit_mapping_required" id="edit_mapping_required" required data-parsley-errors-container=".mappingError" title="-- Select Option --">
                                <option value="no"'; if($leavetype->mapping_required == 'no') $content .='selected'; $content .='>No</option>
                                <option value="yes"'; if($leavetype->mapping_required == 'yes') $content .='selected'; $content .='>Yes</option>
                            </select>
                            <div class="mappingError"></div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_leavetype">Submit</button>
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
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {        
        $leavetype = LeaveType::find($request->edit_leavetype_id);
        
        $sql_check = LeaveType::where('leave_type', '=', $request->input('edit_leavetype_name'))->where('id', '!=', $request->input('edit_leavetype_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $leavetype->leave_type=$request->input('edit_leavetype_name');
            $leavetype->mapping_required=$request->input('edit_mapping_required');
            $leavetype->save();

            if($leavetype) { 
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
     * @param  \App\Models\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $leavetypeIds = $request->leavetype_id;
        LeaveType::whereIn('id', $leavetypeIds)->delete();
        return 'success';
    }
}
