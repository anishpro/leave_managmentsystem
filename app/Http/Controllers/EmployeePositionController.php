<?php

namespace App\Http\Controllers;

use App\Models\EmployeePosition;
use Illuminate\Http\Request;

class EmployeePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/positions');
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

        $title .="Add New Position";

        $content .='<form id="position_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Position <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="position" id="position" placeholder="Enter Position" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_position">Submit</button>
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
        $sql_check = EmployeePosition::where('position', '=', $request->input('position'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new EmployeePosition();
            $model->position = $request->input('position');
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
     * @param  \App\Models\EmployeePosition  $employeePosition
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allPositions = EmployeePosition::all();
        $output = '';

        if($allPositions->count() > 0) {
            foreach($allPositions as $position) { 
                $output .= '<tr id="'.$position->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input position_checkbox" type="checkbox" id="chk-'.$position->id.'" name="position_checkbox[]" value="'.$position->id.'">
                                        <label class="form-check-label" for="chk-'.$position->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($position->position).'</td>
                                <td>'.date('d M, Y', strtotime($position->created_at)).'</td> 
                                <td>
                                <a class="text-warning position_edit" href="javascript:void(0)" data-id="'.$position->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeePosition  $employeePosition
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $positionId = $request->position_id;
        $position = EmployeePosition::find($positionId);

        $content = $title='';

        $title .="Edit Position";

        $content .='<form id="edit_position_form">
                    <div class="result_position"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Position <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_position_name" id="edit_position_name" value="'.$position->position.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="edit_position_id" id="edit_position_id" value="'.$position->id.'">
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_position">Submit</button>
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
     * @param  \App\Models\EmployeePosition  $employeePosition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {        
        $position = EmployeePosition::find($request->edit_position_id);
        
        $sql_check = EmployeePosition::where('position', '=', $request->input('edit_position_name'))->where('id', '!=', $request->input('edit_position_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $position->position=$request->input('edit_position_name');
            $position->save();

            if($position) { 
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
     * @param  \App\Models\EmployeePosition  $employeePosition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $positionIds = $request->position_id;
        EmployeePosition::whereIn('id', $positionIds)->delete();
        return 'success';
    }
}
