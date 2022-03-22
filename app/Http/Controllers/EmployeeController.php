<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Group;
use App\Models\Admin;
use App\Models\Role;
use App\Models\EmployeePosition;
use App\Models\DutyStation;
use Illuminate\Http\Request; 
use DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Employee/index');
        
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

        $title .="Add New Employee";

        $allGroups = Group::all();
        $allRoles = Role::all();
        $allPositions = EmployeePosition::all();
        $allStations = DutyStation::all();
        
        return view('Employee/add_employee', [
                            'allGroups' => $allGroups, 
                            'allRoles' => $allRoles, 
                            'allPositions' => $allPositions, 
                            'allStations' => $allStations
        ]);
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
        $sql_check = Employee::where('email', '=', $request->input('email'))->orWhere('emp_code', $request->input('employee_code'))->first();
        //dd($sql_check);
        if (!$sql_check) {

            $model = new Employee();

            //Profile Attachment
            if($request->hasFile('attachment')) {

                $file = $request->file('attachment');
                
                $allowed_ext = array("jpg", "jpeg", "png");
                $ext = $file->getClientOriginalExtension();

                if(in_array($ext, $allowed_ext)) {  	
                    $file_name = rand().time().'.'.$ext;
                    if($file->move('uploads/profile/', $file_name)) {
                        $attachment = $file_name;
                    }
                }
                    
            }else{
                $attachment = 'NULL';
            }

            //Application Signature
            if($request->hasFile('signature')) {

                $file = $request->file('signature');
                
                $allowed_ext = array("jpg", "jpeg", "png");
                $ext = $file->getClientOriginalExtension();

                if(in_array($ext, $allowed_ext)) {  	
                    $file_name = rand().time().'.'.$ext;
                    if($file->move('uploads/signature/', $file_name)) {
                        $signature = $file_name;
                    }
                }
                    
            }else{
                $signature = 'NULL';
            }

            $model->role_id = $request->input('role_id');
            $model->emp_code = $request->input('employee_code');
            $model->group_id = $request->input('group_id');
            $model->name = $request->input('employee');
            $model->position = $request->input('position');
            $model->duty_station = $request->input('duty_station');
            $model->phone = $request->input('contact');
            $model->email = $request->input('email');
            $model->address = $request->input('address');
            $model->signature = $signature;
            $model->profile = $attachment;
            $model->save();

            if($model) {   

                $employee_id = $model->id;
                             
                $data = new Admin();
                $data->emp_id = $employee_id;
                $data->role_id = $request->input('role_id');
                $data->username = $request->input('username');
                $data->password = md5($request->input('username'));
                $data->save();
                
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
     * @param  \App\Models\Employee  $employee 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allEmployees = Employee::all();
        $output = '';

        if($allEmployees->count() > 0) {
            foreach($allEmployees as $employee) { 

                $position = EmployeePosition::find($employee->position);
                $station = DutyStation::find($employee->duty_station);
                $group = Group::find($employee->group_id);

                $output .= '<tr id="'.$employee->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input employee_checkbox" type="checkbox" id="chk-'.$employee->id.'" name="employee_checkbox[]" value="'.$employee->id.'">
                                        <label class="form-check-label" for="chk-'.$employee->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($employee->name).'</td>
                                <td>'.$employee->email.'</td> 
                                <td>'.$group->group_name.'</td> 
                                <td>'.$position->position.'</td> 
                                <td>'.$station->work_place.'</td>
                                <td>'.$employee->phone.'</td> 
                                <td>
                                    <a class="text-warning" href="employees/edit/'.$employee->id.'" tooltip="Edit"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        $allGroups = Group::all();
        $allRoles = Role::all();
        $allPositions = EmployeePosition::all();
        $allStations = DutyStation::all();
        $admin = Admin::where('emp_id', $id)->first();

        if($admin){
            $username = $admin->username;
        }else{
            $username = '';
        }

        return view('Employee/edit_employee',[
            'employee' => $employee,
            'allGroups' => $allGroups,
            'allRoles' => $allRoles,
            'allPositions' => $allPositions,
            'allStations' => $allStations,
            'admin' => $admin,
            'username' => $username
        ]);

        //return view('test/abc');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        //print_r('empl'.$request->employee_id);
        $employee = Employee::find($request->employee_id);
        DB::enableQueryLog();
        $sql_check = Employee::where('email', '=', $request->input('email'))->where('id', '!=', $request->employee_id)->first();
        //dd($employee);
        //get query printed
        // $quries = DB::getQueryLog();
        // dd($quries);

        if (!$sql_check) {

            //Profile Attachment
            if($request->hasFile('attachment')) {

                $file = $request->file('attachment');
                
                $allowed_ext = array("jpg", "jpeg", "png");
                $ext = $file->getClientOriginalExtension();

                if(in_array($ext, $allowed_ext)) {  	
                    $file_name = rand().time().'.'.$ext;
                    if($file->move('uploads/profile/', $file_name)) {
                        $attachment = $file_name;
                    }
                }
                    
            }else{
                $attachment = $request->old_profile;
            }

            //Application Signature
            if($request->hasFile('signature')) {

                $file = $request->file('signature');
                
                $allowed_ext = array("jpg", "jpeg", "png");
                $ext = $file->getClientOriginalExtension();

                if(in_array($ext, $allowed_ext)) {  	
                    $file_name = rand().time().'.'.$ext;
                    if($file->move('uploads/signature/', $file_name)) {
                        $signature = $file_name;
                    }
                }
                    
            }else{
                $signature = $request->old_signature;
            }
            
            $employee->role_id = $request->input('role_id');
            $employee->emp_code = $request->input('employee_code');
            $employee->group_id = $request->input('group_id');
            $employee->name = $request->input('employee');
            $employee->position = $request->input('position');
            $employee->duty_station = $request->input('duty_station');
            $employee->phone = $request->input('contact');
            $employee->email  = $request->input('email');
            $employee->address = $request->input('address');
            $employee->signature = $signature;
            $employee->profile = $attachment;
            $employee->save();

            if($employee) { 

                $loginData = Admin::where('emp_id', $request->employee_id)->first();
                if($loginData){
                    
                    $loginData->role_id = $request->input('role_id');
                    $loginData->username = $request->input('username');
                    $loginData->password = md5($request->input('username'));
                    $loginData->save();

                }else{

                    $admin = new Admin();
                    $admin->emp_id = $request->input('employee_id');
                    $admin->role_id = $request->input('role_id');
                    $admin->username = $request->input('username');
                    $admin->password = md5($request->input('username'));
                    $admin->save();
                }

                $signal = "updated";
            }else {
                $signal = "not_updated";
            }
        }else {

            $query_check = Employee::where('emp_code', '=', $request->input('employee_code'))->where('id', '!=', $request->employee_id)->first();
            if(!$query_check){
                $signal = "updated";
            }else{
                $signal = "exist";
            }
            
        }
       
        return response()->json($signal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $empIds = $request->emp_id;
        Employee::whereIn('id', $empIds)->delete();
        return 'success';
    }
}
