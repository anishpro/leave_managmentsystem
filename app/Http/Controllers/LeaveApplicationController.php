<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplication;
use App\Models\LeaveDetail;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\MapContractLeave;
use App\Models\PublicHolidays;
use App\Models\LeaveType;
use App\Models\DutyStation;
use App\Models\Group;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Application/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        //
        $allEmployee = Employee::all();
        $allLeaveType = LeaveType::all();
        $allShiftType = [
            'First Half' => 'first_half', 
            'Second Half' => 'second_half', 
            'Full Day' => 'full_day'
        ];
        return view('Application/addFrom', ['allEmployee' => $allEmployee, 'allLeaveType' => $allLeaveType, 'allShiftType' => $allShiftType]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->to_date;
        $output='';
        $data = '';
        $sql_check = LeaveApplication::where('emp_id', $request->input('employee'))
                                    ->whereBetween('from_date', [$request->input('application_from_date'), $request->input('application_to_date')])
                                    ->whereBetween('to_date', [$request->input('application_from_date'), $request->input('application_to_date')])
                                    ->first();

        if(!$sql_check){

            $model = new LeaveApplication(); 

            $leaveTaken = 0;
            $leaveTime = 0;

            $leaveType = LeaveType::find($request->input('leave_type'));
            $empContract = EmployeeContract::where('employee_id', '=', $request->input('employee'))->orderBy('id', 'DESC')->take(1)->first();

            if($empContract){

                $mapContract = MapContractLeave::where('leave_type_id', '=', $request->input('leave_type'))->where('contract_month', '=', $empContract->no_of_months)->first();
                $employeeData = Employee::find($request->input('employee'));
                $publicHolidays = PublicHolidays::where('group_id', '=', $employeeData->group_id)->get();
                $existingLeave = LeaveApplication::where('contract_id', '=', $empContract->id)->where('emp_id', '=', $request->input('employee'))->where('leave_type_id', '=', $request->input('leave_type'))->get();
                
                $weekenedDays = Group::find($employeeData->group_id);
                $weekened_day = explode(",", $weekenedDays->weekened);
                //dd($weekenedDays->weekened);

                //Application Attachment
                if($request->hasFile('attachment')) {

                    $file = $request->file('attachment');
                    
                    $allowed_ext = array("jpg", "jpeg", "png", "docx", "doc", "pdf");
                    $ext = $file->getClientOriginalExtension();

                    if(in_array($ext, $allowed_ext)) {  	
                        $file_name = rand().time().'.'.$ext;
                        if($file->move('uploads/profile/', $file_name)) { 
                            $attachment = $file_name;
                        }
                    }
                        
                }else{
                    $attachment = '';
                }

                //Date Calculation
                $from_dates = $request->input('from_date');
                $total_days = 0;
                $leaveTime = 0;
                $dateIssue = 0;
                $no_of_days = 0;
                $total_no_of_days = 0;
                $lastFromDate = null;
                $lastToDate = null;
                $date_from_array = array();
                $date_to_array = array();
                $total_no_of_days_arrays = array();
                $shift_type_arrays = array();

                foreach($from_dates as $key => $from_date){
                    
                    $weekened = 0;
                    $holidays = 0;
                    $dateArray = array();
                    $date_from = Carbon::parse($from_date);
                    $date_to = Carbon::parse($request->input('to_date')[$key]);
                    $shift_type = $request->input('shift_type')[$key];  

                    if($key > 0){
                        $lastToDate = $request->input('to_date')[$key - 1];

                        if ($from_date === $lastToDate) {
                            $dateIssue = $dateIssue + 1;
                        }else if($from_date < $lastToDate){
                            $dateIssue = $dateIssue + 1;
                        }
                    }

                    if($date_from === $date_to){
                        $no_of_days = 1 ;
                    }else{
                        $no_of_days = $date_from->diffInDays($date_to) + 1;
                    } 

                    if($shift_type == 'first_half'){
                        $leaveTime = 0.5; 
                    }else if($shift_type == 'second_half'){
                        $leaveTime =  0.5; 
                    }else{
                        $leaveTime = 1; 
                    }
                    
                    //Weekened Calculation
                    $dateRange = CarbonPeriod::create($date_from, $date_to);
                    foreach($dateRange as $value){ 
                        $day = date('D', strtotime($value));
                        $dateArray[] = date('Y-m-d', strtotime($value));

                        if(in_array($day, $weekened_day)) {
                            $weekened = $weekened + 1;
                        }                            
                    }
                    
                    //Public Holiday 
                    foreach($publicHolidays as $public){
                        if(in_array($public, $dateArray)) {
                            $holidays = $holidays + 1;
                        }
                    }

                    $no_of_days = $no_of_days - ($weekened + $holidays);
                    $total_no_of_days = $no_of_days * $leaveTime;                    
                    $total_days = $total_days + $total_no_of_days;
                    
                    //Pushing Data To Array
                    array_push($date_from_array, $date_from);
                    array_push($date_to_array, $date_to);
                    array_push($total_no_of_days_arrays, $total_no_of_days);
                    array_push($shift_type_arrays, $shift_type);
                }

                if($total_days > $request->input('total_days_count')){
                    $signal = "invalid";
                }else{
                    
                    if($dateIssue > 0){

                        $signal = 'issue_in_date';

                    }else{

                        $model->contract_id = $empContract->id;
                        $model->emp_id = $request->input('employee');
                        $model->leave_type_id = $request->input('leave_type');
                        $model->day_count = $request->input('total_days_count');
                        $model->leave_days = $total_days;
                        $model->attachment = $attachment;
                        $model->from_date = $request->application_from_date;
                        $model->to_date = $request->application_to_date;
                        $model->project = $request->input('project');
                        $model->address = $request->input('address');
                        $model->contact = $request->input('contact'); 
                        $model->approval_date = Carbon::now()->toDateTimeString(); 

                        if($leaveType->mapping_required == 'yes'){
                            if($mapContract){  
                                $model->save();
                                if($model){

                                    $application_id = $model->id;
                
                                    //Leave Detail Insertion
                                    foreach($date_from_array as $index => $datefrom){     
                
                                        LeaveDetail::insert([ 
                                            'application_id' => $application_id, 
                                            'from_date' => $datefrom,                 
                                            'to_date' => $date_to_array[$index],
                                            'no_of_days' => $total_no_of_days_arrays[$index],
                                            'leave_shift' => $shift_type_arrays[$index],
                                            'created_at' => Carbon::now()->toDateTimeString(),
                                            'updated_at' => Carbon::now()->toDateTimeString(),
                                        ]);
                
                                    }
                                    
                                    $signal = "inserted";
                
                                }else{
                
                                    $signal = "not_inserted";
                                }
                                
                            }else{
                                $signal = 'not_valid';
                                $data = 'Leave days are not yet mapped';
                            }
                        }else{
                            $model->save();
                            if($model){

                                $application_id = $model->id;
            
                                //Leave Detail Insertion
                                foreach($date_from_array as $index => $datefrom){     
            
                                    LeaveDetail::insert([ 
                                        'application_id' => $application_id, 
                                        'from_date' => $datefrom,                 
                                        'to_date' => $date_to_array[$index],
                                        'no_of_days' => $total_no_of_days_arrays[$index],
                                        'leave_shift' => $shift_type_arrays[$index],
                                        'created_at' => Carbon::now()->toDateTimeString(),
                                        'updated_at' => Carbon::now()->toDateTimeString(),
                                    ]);
            
                                }
                                
                                $signal = "inserted";
            
                            }else{
            
                                $signal = "not_inserted";
                            }
                        }  
                        
                    }
                }
            }else{
                $signal = 'no_contract';
                $data = 'Contract is not mapped to this employee';
            }           

        }else{
            $signal = 'exists';
        }

        $output = array(
            'signal' => $signal,
            'data' => $data
        );

        return response()->json($output);
        
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allApplication = LeaveApplication::all();
        //return $allApplication;
        $output = '';

        if($allApplication->count() > 0) {
            foreach($allApplication as $value) { 

                //print_r($value->emp_id) ;
                $leaveType = LeaveType::find($value->leave_type_id); 
                $employee = Employee::find($value->emp_id);
                //return $employee;

                $output .= '<tr id="'.$value->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input application_checkbox" type="checkbox" id="chk-'.$value->id.'" name="application_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.$employee->name.'</td>
                                <td>'.$leaveType->leave_type.'</td>
                                <td>'.date('d M, Y', strtotime($value->from_date)).'</td> 
                                <td>'.date('d M, Y', strtotime($value->to_date)).'</td> 
                                <td>'.$value->leave_days.'</td>
                                <td>
                                    <a class="text-warning" href="application/edit/'.$value->id.'" tooltip="Edit" data-id="'.$value->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                    <a class="text-info" target="_blank" href="'.url("application/application_pdf/pdf/$value->id").'" tooltip="Pdf Download" data-id="'.$value->id.'"><i class="bx bxs-download bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        echo $output;
    } 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $applicationData = LeaveApplication::find($id);
        $allEmployee = Employee::all();
        $allLeaveType = LeaveType::all();
        $applicationDetail = LeaveDetail::where('application_id', $id)->get();

        $allShiftType = [
            'First Half' => 'first_half', 
            'Second Half' => 'second_half', 
            'Full Day' => 'full_day'
        ];

        $imageArray = ["jpg", "jpeg", "png"];
        $fileArray = ["pdf", "doc", "docx"];

        return view('Application/editForm', [
            'applicationData' => $applicationData, 
            'applicationDetail' => $applicationDetail, 
            'allEmployee' => $allEmployee, 
            'allLeaveType' => $allLeaveType, 
            'allShiftType' => $allShiftType,
            'imageArray' => $imageArray,
            'fileArray' => $fileArray
        ]);
    } 

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $output='';
        $data = '';
        $applicationData = LeaveApplication::find($request->application_id);

        $sql_check = LeaveApplication::where('emp_id', $request->input('employee'))
                                    ->whereBetween('from_date', [$request->input('application_from_date'), $request->input('application_to_date')])
                                    ->whereBetween('to_date', [$request->input('application_from_date'), $request->input('application_to_date')])
                                    ->where('id', '!=', $request->input('application_id'))
                                    ->first();

        if(!$sql_check){

            $leaveTaken = 0;
            $leaveTime = 0;
            $attachment = '';

            $leaveType = LeaveType::find($request->input('leave_type'));
            $empContract = EmployeeContract::where('employee_id', '=', $request->input('employee'))->orderBy('id', 'DESC')->take(1)->first();

            if($empContract){

                $mapContract = MapContractLeave::where('leave_type_id', '=', $request->input('leave_type'))->where('contract_month', '=', $empContract->no_of_months)->first();
                $employeeData = Employee::find($request->input('employee'));
                $publicHolidays = PublicHolidays::where('group_id', '=', $employeeData->group_id)->get();
                $existingLeave = LeaveApplication::where('contract_id', '=', $empContract->id)->where('emp_id', '=', $request->input('employee'))->where('leave_type_id', '=', $request->input('leave_type'))->get();
                
                $weekenedDays = Group::find($employeeData->group_id);
                $weekened_day = explode(",", $weekenedDays->weekened);
                //dd($weekenedDays->weekened);

                //Application Attachment
                if($request->hasFile('attachment')) {

                    $file = $request->file('attachment');
                    
                    $allowed_ext = array("jpg", "jpeg", "png", "docx", "doc", "pdf");
                    $ext = $file->getClientOriginalExtension();

                    if(in_array($ext, $allowed_ext)) {  	
                        $file_name = rand().time().'.'.$ext;
                        if($file->move('uploads/profile/', $file_name)) { 
                            $attachment = $file_name;
                        }
                    }
                        
                }else{
                    $attachment = $request->old_attachment;
                }

                //Date Calculation
                $from_dates = $request->input('from_date');
                $total_days = 0;
                $leaveTime = 0;
                $dateIssue = 0;
                $no_of_days = 0;
                $total_no_of_days = 0;
                $lastFromDate = null;
                $lastToDate = null;
                $date_from_array = array();
                $date_to_array = array();
                $total_no_of_days_arrays = array();
                $shift_type_arrays = array();
                $detail_id_arrays = array();

                foreach($from_dates as $key => $from_date){
                    
                    $weekened = 0;
                    $holidays = 0;
                    $dateArray = array();
                    $detail_id = $request->input('detailIds')[$key];  
                    $date_from = Carbon::parse($from_date);
                    $date_to = Carbon::parse($request->input('to_date')[$key]);
                    $shift_type = $request->input('shift_type')[$key];  

                    if($key > 0){
                        $lastToDate = $request->input('to_date')[$key - 1];

                        if ($from_date === $lastToDate) {
                            $dateIssue = $dateIssue + 1;
                        }else if($from_date < $lastToDate){
                            $dateIssue = $dateIssue + 1;
                        }
                    }

                    if($date_from === $date_to){
                        $no_of_days = 1 ;
                    }else{
                        $no_of_days = $date_from->diffInDays($date_to) + 1;
                    } 

                    if($shift_type == 'first_half'){
                        $leaveTime = 0.5; 
                    }else if($shift_type == 'second_half'){
                        $leaveTime =  0.5; 
                    }else{
                        $leaveTime = 1; 
                    }
                    
                    //Weekened Calculation
                    $dateRange = CarbonPeriod::create($date_from, $date_to);
                    foreach($dateRange as $value){ 
                        $day = date('D', strtotime($value));
                        $dateArray[] = date('Y-m-d', strtotime($value));

                        if(in_array($day, $weekened_day)) {
                            $weekened = $weekened + 1;
                        }                            
                    }
                    
                    //Public Holiday 
                    foreach($publicHolidays as $public){
                        if(in_array($public, $dateArray)) {
                            $holidays = $holidays + 1;
                        }
                    }

                    $no_of_days = $no_of_days - ($weekened + $holidays);
                    $total_no_of_days = $no_of_days * $leaveTime;                    
                    $total_days = $total_days + $total_no_of_days;
                    
                    //Pushing Data To Array
                    array_push($detail_id_arrays, $detail_id);
                    array_push($date_from_array, $date_from);
                    array_push($date_to_array, $date_to);
                    array_push($total_no_of_days_arrays, $total_no_of_days);
                    array_push($shift_type_arrays, $shift_type);
                }

                //print_r($detail_id_arrays);

                if($total_days > $request->input('total_days_count')){
                    $signal = "invalid";
                }else{
                    
                    if($dateIssue > 0){

                        $signal = 'issue_in_date';

                    }else{

                        $applicationData->contract_id = $empContract->id;
                        $applicationData->emp_id = $request->input('employee');
                        $applicationData->leave_type_id = $request->input('leave_type');
                        $applicationData->day_count = $request->input('total_days_count');
                        $applicationData->leave_days = $total_days;
                        $applicationData->attachment = $attachment;
                        $applicationData->from_date = $request->application_from_date;
                        $applicationData->to_date = $request->application_to_date;
                        $applicationData->project = $request->input('project');
                        $applicationData->address = $request->input('address');
                        $applicationData->contact = $request->input('contact'); 

                        

                        if($leaveType->mapping_required == 'yes'){
                            if($mapContract){  
                                $applicationData->save();
                                if($applicationData){

                                    $removeIds = explode(",",$request->input('removeIds'));
                                    $application_id = $applicationData->id;
                
                                    //Leave Detail Insertion
                                    foreach($date_from_array as $index => $datefrom){  
                                        
                                        if($detail_id_arrays[$index] !=''){

                                            LeaveDetail::whereIn('id', $removeIds)->delete();
                                            
                                            LeaveDetail::where('id', '=', $detail_id_arrays[$index])

                                                ->update([
                                                    'application_id' => $application_id, 
                                                    'from_date' => $datefrom,                 
                                                    'to_date' => $date_to_array[$index],
                                                    'no_of_days' => $total_no_of_days_arrays[$index],
                                                    'leave_shift' => $shift_type_arrays[$index],
                                                    'created_at' => Carbon::now()->toDateTimeString(),
                                                    'updated_at' => Carbon::now()->toDateTimeString()
                                                ]);

                                        }else{

                                            LeaveDetail::insert([ 
                                                'application_id' => $application_id, 
                                                'from_date' => $datefrom,                 
                                                'to_date' => $date_to_array[$index],
                                                'no_of_days' => $total_no_of_days_arrays[$index],
                                                'leave_shift' => $shift_type_arrays[$index],
                                                'created_at' => Carbon::now()->toDateTimeString(),
                                                'updated_at' => Carbon::now()->toDateTimeString(),
                                            ]);

                                        }
                                    }
                                    
                                    $signal = "inserted";
                
                                }else{
                
                                    $signal = "not_inserted";
                                }
                                
                            }else{
                                $signal = 'not_valid';
                                $data = 'Leave days are not yet mapped';
                            }
                        }else{
                            $model->save();
                            if($model){

                                $application_id = $model->id;
            
                                //Leave Detail Insertion
                                foreach($date_from_array as $index => $datefrom){     
            
                                    LeaveDetail::insert([ 
                                        'application_id' => $application_id, 
                                        'from_date' => $datefrom,                 
                                        'to_date' => $date_to_array[$index],
                                        'no_of_days' => $total_no_of_days_arrays[$index],
                                        'leave_shift' => $shift_type_arrays[$index],
                                        'created_at' => Carbon::now()->toDateTimeString(),
                                        'updated_at' => Carbon::now()->toDateTimeString(),
                                    ]);
            
                                }
                                
                                $signal = "inserted";
            
                            }else{
            
                                $signal = "not_inserted";
                            }
                        }  
                        
                    }
                }
            }else{
                $signal = 'no_contract';
                $data = 'Contract is not mapped to this employee';
            }           

        }else{
            $signal = 'exists';
        }

        $output = array(
            'signal' => $signal,
            'data' => $data
        );

        return response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeaveApplication  $leaveApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $applicationIds = $request->application_id;
        LeaveApplication::whereIn('id', $applicationIds)->delete();
        return 'success';
    }

    //PDF VIEW
    function pdf($id)
    { 
        //echo $this->convert_customer_data_to_html($id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_customer_data_to_html($id));
        return $pdf->stream();
    }

    function convert_customer_data_to_html($id)
    {
        //
        $applicationData = LeaveApplication::find($id);
        //print_r($applicationData);
        $applicationDetail = LeaveDetail::where('application_id', $id)->get();
        //print_r($applicationData);
        $empData = Employee::find($applicationData->emp_id);
        $leaveData = LeaveType::find($applicationData->leave_type_id);
        $dutyStation = DutyStation::find($empData->duty_station);
        $allLeaves = LeaveType::where('mapping_required', 'yes')->get();
        
        $totalLeaves = '';
        $totalTakenLeaves = '';
        $allRemainingLeaves = '';
        $empContract = EmployeeContract::where('employee_id', '=', $applicationData->emp_id)->orderBy('id', 'DESC')->first();        

        $output = '
        <style>
        .pdf-chkbox {
            margin-top:7px;
        }
        </style>
        <p style="font-size:10px;text-align:right;">System Generated</p>
        <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:0px;font-size:13px;">
                        <tr>
                            <th width="40%" style="text-align:right;padding-right:15px;">
                                <img src="http://dellsushil/LeaveManagementSystem/public/admin_assets/img/who-logo.jpg" style="width:150px;">
                            </th>
                            <th width="60%" style="text-align:left;">
                                <h3 style="margin-bottom: 0px;text-decoration:underline">REQUEST FOR LEAVE FOR SSA Holders</h3>
                                <p style="margin-top: 5px;margin-left: 70px;">(to be completed in duplicate)</p>
                            </th>
                        </tr>
                    </table>
                
                    <table width="100%" style="border: 1px solid;padding:15px;margin-bottom: 10px;">
                        <tr style="margin-bottom:10px;">
                            <td><h3>A. <u>REQUEST</u></h3></td>
                        </tr>
                        <tr>
                            <td width="50%">
                                <table>
                                    <tr>
                                        <td style="padding-bottom: 10px;">(1)	Name:</td>
                                        <td style="width: 10px"></td>
                                        <td style="padding-bottom: 10px;">
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" value="'.$empData->name.'">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%"> 
                                <table>
                                    <tr>
                                        <td style="padding-bottom: 10px;">(2)	Project:</td>
                                        <td style="width: 10px"></td>
                                        <td style="padding-bottom: 10px;">
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text"  value="'.$applicationData->project.'">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">(3)	Type of leave requested:</td> 
                            <td style="padding-bottom: 10px;">
                                <div class="children">
                                <input type="checkbox" class="pdf-chkbox" checked>'.ucwords($leaveData->leave_type).'</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">(4)	Duration of leave requested:</td>
                            <td style="padding-bottom: 10px;">
                                <table width="100%" style="border-collapse: collapse; border: 0px;">
                                    <tr>
                                        <th style="border:1px solid;width:100px;font-size:13px;">From Date</th>
                                        <th style="border:1px solid;width:100px;font-size:13px;">To Date</th>
                                        <th style="border:1px solid;width:100px;font-size:13px;">Leave Days</th>
                                        <th style="border:1px solid;width:100px;font-size:13px;">LeaveShift</th>
                                    </tr>';
                                    foreach($applicationDetail as $detail){
                                        //print_r($detail);exit;
                                        if($detail->leave_shift == 'first_half'){
                                            $leave_shift = 'First Half';
                                        }else if($detail->leave_shift == 'second_half'){
                                            $leave_shift = 'Second Half';
                                        }else if($detail->leave_shift == 'full_day'){
                                            $leave_shift = 'Full Day';
                                        }

                                        $output .='<tr>
                                                    <td style="border:1px solid;text-align: center;font-size:13px;">'.$detail->from_date.'</td>
                                                    <td style="border:1px solid;text-align: center;font-size:13px;">'.$detail->from_date.'</td>
                                                    <td style="border:1px solid;text-align: center;font-size:13px;">'.$detail->no_of_days.'</td>
                                                    <td style="border:1px solid;text-align: center;font-size:13px;">'.$leave_shift.'</td>
                                                </tr>';
                                    }                                    
                        $output .='</table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">(5) Address during Leave:</td>
                            <td style="padding-bottom: 10px;">
                                <input style="border:0;border-bottom:1px;width:100%;font-size:13px;" type="text" value="'.$applicationData->address.'">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">(6) Contact:</td>
                            <td style="padding-bottom: 10px;">
                                <input style="border:0;border-bottom:1px;width:100%;font-size:13px;" type="text" value="'.$applicationData->contact.'">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">(7) Duty Station:</td>
                            <td style="padding-bottom: 10px;">
                                <input style="border:0;border-bottom:1px;width:100%;font-size:13px;" type="text" value="'.$dutyStation->work_place.'">
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding-bottom: 10px;">
                                <table>
                                    <tr>
                                        <td>Date:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" value="'.date('Y-m-d', strtotime($dutyStation->created_at)).'">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%" style="padding-bottom: 10px;">
                                <table>
                                    <tr>
                                        <td>Signature:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <img src="" height= "30px">
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" value="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border: 1px solid;padding:15px;margin-bottom: 10px;">
                        <tr style="margin-bottom:15px;">
                            <td><h3>B. <u>RECOMMENDATIONS</u></h3></td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 10px;">Supervisor or Project Leader:</td> 
                            <td style="padding-bottom: 10px;">
                                <div class="children">
                                    <input type="checkbox" class="pdf-chkbox" name="name">Recommended
                                
                                    <input type="checkbox" class="pdf-chkbox" name="name">Not Recommended
                                    (see comments overleaf)
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding-bottom: 10px;">
                                <table>
                                    <tr>
                                        <td>Date:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" name="firstname" value="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="50%" style="padding-bottom: 10px;">
                                <table>
                                    <tr>
                                        <td>Signature:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" name="firstname" value="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;">
                        
                        <tr>
                            <td style="padding-bottom: 10px;border: 1px solid;">
                                <table width="100%" style="border-collapse: collapse; border: 0px;padding:15px;">
                                    <tr>
                                        <td colspan="3">
                                            <h3 style="margin-bottom:0px;">C. <u>DECISION</u></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <h3><u>At Country Level</u></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 10px;">TL-WHE:</td> 
                                        <td style="padding-bottom: 10px;" colspan="2">
                                            <div class="children">
                                                <input type="checkbox" class="pdf-chkbox" name="name">Approved
                                            
                                                <input type="checkbox" class="pdf-chkbox" name="name">Not Approved 
                                            <br>
                                                <input type="checkbox" class="pdf-chkbox" name="name">Referred to SEARO
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" name="firstname" value="">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Signature:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" name="firstname" value="">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="padding-bottom: 10px;border:1px solid;">
                                <table width="100%" style="border-collapse: collapse; border: 0px;padding:15px;">
                                    <tr style="margin-bottom:15px;">
                                        <td colspan="3"><h3>Leave Balance (OFFICE USE ONLY)</h3></td>
                                    </tr>';

                                    foreach($allLeaves as $val){

                                        $totalTaken = 0;
                                        $remainingLeaves = 0;

                                        //taken leaves
                                        $takenLeaves = LeaveApplication::where('emp_id', $applicationData->emp_id)->where('contract_id', $empContract->id)->where('leave_type_id', $val->id)->get();
                                        if($takenLeaves){
                                            foreach($takenLeaves as $taken){
                                                $totalTaken = $totalTaken + $taken->leave_days;
                                            }
                                        }



                                        //total leave counts
                                        $data = MapContractLeave::where('contract_month', '=',$empContract->no_of_months)->where('leave_type_id', '=',$val->id)->first();
                                        
                                        if($data){
                                            $remainingLeaves = ($data->leave_days - $totalTaken);
                                        }

                                        $output .= '<tr>
                                                        <td>'.ucwords($val->leave_type).':</td>
                                                        <td style="width: 10px"></td>
                                                        <td>
                                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" value="'.$remainingLeaves.'">
                                                        </td>
                                                    </tr>';
                                    }
                        
                        $output .=' <tr>
                                        <td>Leave Recorder:</td>
                                        <td style="width: 10px"></td>
                                        <td>
                                            <input style="border:0;border-bottom:1px;font-size:13px;" type="text" name="firstname" value="">
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </table>';
        return $output;
    }


    function leaveReport(){

        $allEmployee = Employee::all();
        return view('Application/leave_report', ['allEmployee' => $allEmployee ]);
    }

    function pdfReport($id, $leave_type){ 

        // echo $this->convert_leavereport_data_to_html($id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_leavereport_data_to_html($id, $leave_type));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    function convert_leavereport_data_to_html($id, $leave_type){
        $output = '';

        $employee = Employee::find($id);
        $leaveApplication = LeaveApplication::where('emp_id','=', $id)->first();
        $contract = EmployeeContract::where('employee_id','=', $id)->first();
        $contractLeaves = MapContractLeave::where('contract_month','=', $contract->no_of_months)->where('leave_type_id','=', 1)->first();

        //taken leaves
        $takenLeaves = LeaveApplication::where('emp_id', $employee->id)->where('contract_id', $contract->id)->where('leave_type_id', 1)->get();

        $totalTaken = 0;
        $balanceLeave = 0;
        $column = '';
        $columnLeaveTaken = '';
        $leavetakencolumn = '';
        if($takenLeaves){
            $no_of_days = 0;
            $balanceLeaves = 0;
            foreach($takenLeaves as $index => $taken){
                
                $totalTaken = $totalTaken + $taken->leave_days;

                $from_date = Carbon::parse($taken->from_date);
                $to_date = Carbon::parse($taken->to_date);
                $no_of_days = $from_date->diffInDays($to_date) + 1;
                if($index == 0){

                    $creditLeaves = $contractLeaves->leave_days;
                    
                }else{
                    $creditLeaves = $balanceLeaves;
                }

                $balanceLeaves = $creditLeaves - $no_of_days;
                
                $column .='<tr>
                            <th>'.$taken->from_date.'</th>
                            <th>'.$taken->to_date.'</th>
                            <th>A</th>
                            <th></th>
                            <th></th>
                            <th>'.date('Y-m-d', strtotime($taken->approval_date)).'</th>                 
                        </tr>';
                $columnLeaveTaken .= '<tr>
                                        <th>'.$taken->from_date.'</th>
                                        <th>'.$taken->to_date.'</th>
                                        <th>'.$no_of_days.'</th>
                                        <th>'.$creditLeaves.'</th>
                                        <th>'.$balanceLeaves.'</th>
                                        <th></th>
                                        <th></th>
                                    </tr>';
            }
        }

        $balanceLeave = $contractLeaves->leave_days - $totalTaken;

        //SICK LEAVE

        $contractSickLeaves = MapContractLeave::where('contract_month','=', $contract->no_of_months)->where('leave_type_id','=', 2)->first();
        
        //taken leaves
        $takenSickLeaves = LeaveApplication::where('emp_id', $employee->id)->where('contract_id', $contract->id)->where('leave_type_id', 1)->get();

        if($takenSickLeaves){
            $no_of_days = 0;
            $balanceLeaves = 0;
            $totalSickTaken = 0;
            $cumulative = 0;
            $columnSick = '';
            $rowCount = 0;
            foreach($takenSickLeaves as $index => $sickTaken){
                $rowCount++;
                $totalSickTaken = $totalSickTaken + $taken->leave_days;

                $from_date = Carbon::parse($sickTaken->from_date);
                $to_date = Carbon::parse($sickTaken->to_date);
                $no_of_days = $from_date->diffInDays($to_date) + 1;
                if($index == 0){

                    $cumulative = $no_of_days;
                    
                }else{
                    $cumulative = $cumulative + $no_of_days;
                }

                $balanceLeaves = $cumulative - $no_of_days;
                
                $columnSick .='<tr>
                                <td style="text-align:center;">'.$taken->from_date.'</td>
                                <td style="text-align:center;">'.$taken->to_date.'</td>
                                <td></td>
                                <td></td>
                                <td style="text-align:center;">'.$no_of_days.'</td>
                                <td style="text-align:center;">'.$cumulative.'</td>
                                <td></td>                 
                                <td></td>                 
                                <td></td>                        
                            </tr>';
            }
        }

        $output .='<style>
                    table{
                        border: 1px solid #b30505;
                        border-collapse: collapse;
                        width:100%;
                        font-size:12px;
                    }                    
                    th{
                        border: 1px solid #b30505;
                        border-collapse: collapse;
                        width: auto;
                        height: 25px;
                    } 
                    td{
                        
                        border-collapse: collapse;
                        width: auto;
                        height: 25px;
                    }
                    #annual_column td{
                        border: 1px solid #b30505;
                    }
                    .td_content{
                        text-align:center; 
                        font-weight:600;
                    }
                    .horizontal_bar{
                        height:1px;
                        width:100%;
                        background-color:#000;
                    }
                    
                </style>';
                
                if($leave_type == 'annual'){
                    $output .='<table border="1" style="border-collapse: collapse;color:#800000;">
                            <tr>
                                <th colspan="6">
                                    <table style="border:0px;border-collapse: collapse;">
                                        <tr>
                                            <td class="td_content" colspan="2">IDENTIFICATION OF <u>FIXED-TERM</u> STAFF MEMBER</td>
                                        </tr>
                                        <tr>
                                            <td>Name: '.$employee->name.'</td>
                                            <td>Staff No.: '.$employee->emp_code.'</td>
                                        </tr>
                                        <tr>
                                            <td>Unit/Project:';
                                            if($leaveApplication) 
                                            $output .= $leaveApplication->project;
                                $output .='</td>
                                            <td>Office/Region: SERO</td>
                                        </tr>
                                        <tr>
                                            <td>Entry on duty date : '.$contract->contract_start_date.'</td>
                                            <td>Contract expiry date : '.$contract->contract_end_date.'</td>
                                        </tr>
                                        <tr>
                                            <td class="horizontal_bar" colspan="2"></td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Exact date home leave due: 
                                                <p style="margin-top:0px;"><small>(day, month, year)</small></p>
                                            </td>
                                            <td>Taken : '.$totalTaken.'</td>
                                        </tr>                                   
                                        
                                        <tr>
                                            <td>
                                                Home leave station:
                                                <p style="margin-top:0px;"><small>(town, country)</small></p>
                                            </td>
                                        </tr>
                                    </table>
                                </th>
                                <th colspan="6">
                                    <table style="border:0px;border-collapse: collapse;" id="annual_column">
                                        <tr>
                                            <td colspan="4" class="td_content">ANNUAL LEAVE CREDIT BALANCE - YEAR:</td>
                                        </tr>
                                        <tr>
                                            <td>Balance from previous card</td>
                                            <td>0</td>
                                            <td rowspan="3" colspan="2">CREDIT</td>
                                        </tr>
                                        <tr>
                                            <td>Leave credit for current period</td>
                                            <td>'.$contractLeaves->leave_days.'</td>
                                        </tr>
                                        <tr>
                                            <td>Total</td>
                                            <td>'.$contractLeaves->leave_days.'</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Leave taken for current period</td>
                                            <td>'.$totalTaken.'</td>
                                            <td style="border:0px;"></td>
                                        </tr>                                    
                                        <tr>
                                            <td colspan="2">Balance at end of current period</td>
                                            <td>'.$balanceLeave.'</td>
                                            <td style="border:0px;"></td>
                                        </tr>
                                    </table>
                                </th>
                                <th>
                                    PERIOD
                                    <p>from</p>
                                    <p>to</p>
                                </th>             
                            </tr>
                        </table>
                        
                        <table id="annual_column" style="border:0px;border-collapse: collapse;">
                            <tr>
                                <th style="width:50%;border:0px;border-collapse: collapse;">
                                    <table>
                                        <tr>
                                            <th colspan="6">LEAVE REQUESTS/APPROVALS <br><small>(A= annula H= home. For sick leave see back of card)</small></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Inclusive dates</td>
                                            <td rowspan="2" style="width:50px;">A or H</td>
                                            <td colspan="2">Full Signature</td>
                                            <td rowspan="2" style="width:70px;">Date approved</td>
                                        </tr>  
                                        <tr>
                                            <td style="width:60px;">From</td>
                                            <td style="width:60px;">To</td>
                                            <td style="width:80px;">Staff</td>
                                            <td style="width:80px;">Signature</td>
                                        </tr>
                                        '.$column.'
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>                 
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>                 
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>                 
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>                 
                                        </tr>
                                        
                                    </table>
                                </th>
                                <th style="border:0px;border-collapse: collapse; width:50%">
                                    <table id="annual_column" style="border:0px;border-collapse: collapse;">
                                        <tr>
                                            <th colspan="7">LEAVE TAKEN<small>(for completion after leave taken)</small></th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">inclusive dates</td>
                                            <td rowspan="2">number of working days</td>
                                            <td rowspan="2">Credit</td>
                                            <td rowspan="2">Balance</td>
                                            <td rowspan="2">initials attendance clerk</td>
                                            <td rowspan="2" style="width:100px;">remarks</td>
                                        </tr> 
                                        
                                        <tr>
                                            <td style="width:100px;">from</td>
                                            <td style="width:100px;">to</td>
                                        </tr>
                                        '.$columnLeaveTaken.'
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th rowspan="4"><small>I have examined this record (both sdes of card) and agree with the entires, debits and balances.</small>
                                            ..................................................................
                                            <small>Staff member\'s signature and date</small>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th>'.$totalTaken.'</th>
                                            <th colspan="3">'.$balanceLeave.'</th>
                                        </tr>
                                    </table>
                                </th>           
                            </tr>
                            <tr>
                                <th><small>Leave request beyond the period covered by this record</small></th>
                                <th>Note: <small>Official Holidays in current year (include discretionary days on back of card)</small></th>
                            </tr> 
                        </table>';
                }else{
                $output .='<table id="annual_column" style="border:0px;border-collapse: collapse;margin-top:20px;">
                                <tr>
                                    <th colspan="13" style="text-align:center;"><h4>SICK LEAVE CONFIRMATION AND CUMULATIVE RECORD</h4></th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:center;">inclusive dates</th>
                                    <th colspan="2" style="text-align:center;">initials</th>
                                    <th rowspan="2" style="text-align:center;">number of working days</th>
                                    <th rowspan="2" style="text-align:center;">cumulative total</th>
                                    <th rowspan="2" style="text-align:center;">check if certified</th>
                                    <th rowspan="2" style="text-align:center;">initials attendance check</th>
                                    <th rowspan="2" style="text-align:center;">remarks</th>
                                    <th colspan="4" rowspan="'.($rowCount + 2).'">
                                        <h3 style="text-align:left;">Note:</h3>
                                        <ol type="1">
                                            <li>Only 7 days of uncertified sick leave may be taken in any calendar year</li>
                                            <li>More than 3 days of consecutive sick leave require certification</li>
                                        </ol>
                                        <h4 style="text-align:left;">
                                        Cumulative record for previous 4 years</h4>
                                    </th>
                                </tr>
                                <tr>
                                    <td style="text-align:center;width:80px;">from</td>
                                    <td style="text-align:center;width:80px;">to</td>
                                    <td style="text-align:center;">staff</td>
                                    <td style="text-align:center;">supervisior</td>
                                </tr>
                                '.$columnSick.'
                                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">SICK LEAVE</td>
                                    <td style="text-align:center;" colspan="2">ABSENCES
                                        <br>
                                        <small>service incurred</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">20</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">20</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">20</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">20</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">Total</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align:center;">Current year 20</td>
                                    <td></td>
                                    <td colspan="2"></td>
                                </tr>
                
                
                                <tr>
                                    <td colspan="13" style="text-align:center;"><h4>OTHER ABSENCES THAN ANNUAL LEAVE*</h4></td>
                                </tr>
                
                
                                <tr>
                                    <td colspan="2" style="text-align:center;">inclusive dates</td>
                                    <td colspan="4" rowspan="2" style="text-align:center;">reason</td>
                                    <td colspan="2" style="text-align:center;">initials</td>
                                    <td colspan="5" rowspan="2" style="text-align:center;">remarks</td>
                                </tr>
                
                
                                <tr>
                                    <td style="text-align:center;">from</td>
                                    <td style="text-align:center;">to</td>
                                    <td style="text-align:center;">staff</td>
                                    <td style="text-align:center;">supervisior</td>
                                </tr>
                
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="5"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="5"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="5"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="5"></td>
                                </tr>
                
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td colspan="4"></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="5">discretionary day(in lieu of official holiday)</td>
                                </tr>
                
                
                            </table>';
                }

        return $output;
    }
    
}
