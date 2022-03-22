<?php

namespace App\Http\Controllers;

use App\Models\EmployeeContract;
use App\Models\Employee;
use Illuminate\Http\Request;

use Carbon\Carbon;
 
class EmployeeContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('EmployeeContract/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response 
     */
    public function create()
    {
        $content = $title='';
        $allEmployees = Employee::all();

        $title .="Add New Contract";

        $content .='<form id="employeeContract_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Employee <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                        <select class="form-control" name="employee" id="employee" required data-parsley-errors-container=".contractError" title="-- Select Employee --">';
                        foreach($allEmployees as $value){
                            $content .='<option value="'.$value->id.'">'.ucfirst($value->name).'</option>';
                        }
        $content .='</select>
                    <div class="contractError"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Start Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="contract_start_date" id="contract_start_date" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">End Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="contract_end_date" id="contract_end_date" placeholder="YYYY-MM-DD" autocomplete="off" required>
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
        //
        $sql_check = EmployeeContract::where('employee_id', '=', $request->input('employee'))
                                        ->where('contract_start_date', '=', $request->input('contract_start_date'))
                                        ->where('contract_end_date', '=', $request->input('contract_end_date'))
                                        ->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new EmployeeContract();
            $model->employee_id = $request->input('employee');
            $model->contract_start_date = $request->input('contract_start_date');
            $model->contract_end_date = $request->input('contract_end_date');

            $contract_end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('contract_end_date'));
            $contract_start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('contract_start_date'));
            $total_months = $contract_end_date->diffInDays($contract_start_date)/30;
            $model->no_of_months = round($total_months);
            //print_r('months'.$total_months);exit;
            // $model->no_of_months = $contract_end_date->diffInMonths($contract_start_date);

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
     * @param  \App\Models\EmployeeContract  $employeeContract
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allContracts = EmployeeContract::all();
        $output = '';

        if($allContracts->count() > 0) {
            foreach($allContracts as $contract) { 

                $employee = Employee::find($contract->employee_id);

                $output .= '<tr id="'.$contract->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input contract_checkbox" type="checkbox" id="chk-'.$contract->id.'" name="contract_checkbox[]" value="'.$contract->id.'">
                                        <label class="form-check-label" for="chk-'.$contract->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($employee->name).'</td>
                                <td>'.date('d M, Y', strtotime($contract->contract_start_date)).'</td> 
                                <td>'.date('d M, Y', strtotime($contract->contract_end_date)).'</td> 
                                <td>'.$contract->no_of_months.'</td> 
                                <td>
                                    <a class="text-warning contract_edit" href="javascript:void(0)" tooltip="Edit" data-id="'.$contract->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeContract  $employeeContract
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $contractId = $request->contract_id;
        $contract = EmployeeContract::find($contractId);
        $content = $title='';

        $allEmployees = Employee::all();

        $title .="Edit Contract";

        $content .='<form id="edit_employeeContract_form">
                    <input type="hidden" class="form-control" value="'.$contract->id.'" name="contract_id">
                    <div class="result_contract"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Employee <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                        <select class="form-control" name="employee" id="employee" required data-parsley-errors-container=".contractError" title="-- Select Employee --" disabled>';
                        foreach($allEmployees as $value){
                            $content .='<option value="'.$value->id.'"'; if($contract->employee_id == $value->id) $content .='selected'; $content .='>'.ucfirst($value->name).'</option>';
                        }
        $content .='</select>
                    <div class="contractError"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Start Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" value="'.$contract->contract_start_date.'" name="contract_start_date" id="contract_start_date" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">End Date <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" value="'.$contract->contract_end_date.'" name="contract_end_date" id="contract_end_date" placeholder="YYYY-MM-DD" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_contract">Submit</button>
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
     * @param  \App\Models\EmployeeContract  $employeeContract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $contract = EmployeeContract::find($request->contract_id);
        
        $sql_check = EmployeeContract::where('employee_id', '=', $request->input('employee'))
                                    ->where('contract_start_date', '=', $request->input('contract_start_date'))
                                    ->where('contract_end_date', '=', $request->input('contract_end_date'))
                                    ->where('id', '!=', $request->input('contract_id'))
                                    ->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $contract->contract_start_date = $request->input('contract_start_date');
            $contract->contract_end_date = $request->input('contract_end_date');
            
            $contract_end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('contract_end_date'));
            $contract_start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('contract_start_date'));
            //$contract->no_of_months = $contract_end_date->diffInMonths($contract_start_date);
            $total_months = $contract_end_date->diffInDays($contract_start_date)/30;
            $contract->no_of_months = round($total_months);

            $contract->save();

            if($contract) { 
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
     * @param  \App\Models\EmployeeContract  $employeeContract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $contractIds = $request->empContract_id;
        EmployeeContract::whereIn('id', $contractIds)->delete();
        return 'success';
    }

}
