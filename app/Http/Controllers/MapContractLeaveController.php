<?php

namespace App\Http\Controllers; 

use App\Models\MapContractLeave;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class MapContractLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/map_contract_leave');
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
        $leaveTypes = LeaveType::where("mapping_required", 'yes')->get();

        $title .="Map Contract To Leave Type";

        $content .='<form id="contract_form">
                    <div class="result"></div>                    

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Contract Month <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="contrat_month" id="contrat_month" placeholder="Enter Contrat Month" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Leave Type <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <select class="form-control" name="leave_type_id" id="leave_type_id" required data-parsley-errors-container=".mappingError" title="-- Select Option --">';
                                foreach($leaveTypes as $type){
                                    $content .=' <option value="'.$type['id'].'">'.ucwords($type['leave_type']).'</option>';
                                }
                $content .=' </select>
                            <div class="mappingError"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Leave Days <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="leave_days" id="leave_days" placeholder="Enter Leave Days" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_contract">Submit</button>
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
        //return $request;
        $sql_check = MapContractLeave::where('contract_month', '=', $request->input('contrat_month'))->where('leave_type_id', '=', $request->input('leave_type_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new MapContractLeave();
            $model->leave_type_id = $request->input('leave_type_id');
            $model->contract_month = $request->input('contrat_month');
            $model->leave_days = $request->input('leave_days');
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
     * @param  \App\Models\MapContractLeave  $mapContractLeave
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allContractLeaves = MapContractLeave::all();
        $output = '';

        if($allContractLeaves->count() > 0) {
            foreach($allContractLeaves as $value) { 

                $leaveType = LeaveType::find($value->leave_type_id);

                $output .= '<tr id="'.$value->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input contract_checkbox" type="checkbox" id="chk-'.$value->id.'" name="contract_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($value->contract_month).'</td>
                                <td>'.$leaveType->leave_type.'</td>
                                <td>'.$value->leave_days.'</td> 
                                <td>'.date('d M, Y', strtotime($value->created_at)).'</td> 
                                
                            </tr>';
            }
        }
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MapContractLeave  $mapContractLeave
     * @return \Illuminate\Http\Response
     */
    public function edit(MapContractLeave $mapContractLeave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MapContractLeave  $mapContractLeave
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MapContractLeave $mapContractLeave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MapContractLeave  $mapContractLeave
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        $Ids = $request->contract_id;
        MapContractLeave::whereIn('id', $Ids)->delete();
        return 'success';
    }
}
