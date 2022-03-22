<?php

namespace App\Http\Controllers; 

use App\Models\RequestTravel;
use App\Models\RequestTravelDetail;
use App\Models\Employee;
use App\Models\DutyStation;
use App\Models\EmployeePosition;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class RequestTravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('TravelRequest/travel_request'); 
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
        $allRequestTypes = ['Planned', 'Ad hoc', 'Annual Leave'];
        $allTravelTypes = ['In-Country', 'International'];

        return view('TravelRequest/add_travel_detail', [
                'allEmployee' => $allEmployee, 
                'allRequestTypes' => $allRequestTypes, 
                'allTravelTypes' => $allTravelTypes
        ]);
    }

    //GET RECOMMENDED EMPLOYEE LIST
    public function getRecommendedEmployeeList(Request $request)
    {
        $emp_id = $request->emp_id;
        $output = '';

        $empData = Employee::find($emp_id);
        $empPosition = EmployeePosition::find($empData->position);
        $empDutyStation = DutyStation::find($empData->duty_station);


        $allEmployee = Employee::where('id', '!=', $emp_id)->get();
        foreach($allEmployee as $emp){
            $output .='<option value="'.$emp->id.'">'.$emp->name.'</option>';
        }

        $data = array(
            'empList' => $output,
            'postion' => $empPosition->position,
            'duty_station' => $empDutyStation->work_place
        );

        echo json_encode($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sql_check = RequestTravel::where('emp_id', '=', $request->input('employee'))
                                    ->where('PO', '=', $request->input('po'))
                                    ->first();
        //dd($sql_check);
        if (!$sql_check) {

            $night_halt = $request->input('night_halt');

            $model = new RequestTravel();

            $model->emp_id = $request->input('employee');
            $model->recommenedBy_id = $request->input('recommeded_by');
            $model->request_type = $request->input('request_type');
            $model->travel_type = $request->input('travel_type');
            $model->po = $request->input('po');
            $model->travel_purpose = $request->input('purpose');
            $model->contact_address = $request->input('contact_address');
            $model->government_invitation = $request->input('government_invitaion');
            $model->prev_travel_report = $request->input('prev_duty_report');
            $model->security_clearance_obtained = $request->input('security_clearance');            
            $model->follow_up_action = $request->input('follow_up');
            $model->travel_advance_requested = $request->input('travel_advance_request');
            $model->mode_of_travel = $request->input('travel_mode');
            $model->travel_advance_amount = $request->input('advance_amount');            
            $model->official_vehicle_requied = $request->input('vehicle_required');
            $model->hired_vehicle_request = $request->input('hired_vehicle');
            $model->comment = $request->input('comment');
            
            //Travel Attachment
            if($request->hasFile('attachment')) {

                $file = $request->file('attachment');
                
                $allowed_ext = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
                $ext = $file->getClientOriginalExtension();

                if(in_array($ext, $allowed_ext)) {  	
                    $file_name = rand().time().'.'.$ext;
                    if($file->move('uploads/travel_attachment/', $file_name)) {
                        $attachment = $file_name;
                    }
                }
                    
            }else{
                $attachment = 'NULL';
            }

            $model->attachment = $attachment;            
            $model->save();
            
            if($model) { 
                $travelrequest_id = $model->id;

                foreach($night_halt as $key => $data){
                    
                    RequestTravelDetail::insert([ 
                        'travel_request_id' => $travelrequest_id, 
                        'from_date' => $request->input('from_date')[$key],
                        'to_date' => $request->input('to_date')[$key],
                        'place_of_halt' => $data,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
    
                }
                $signal = "inserted";
            }else {
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
     * @param  \App\Models\RequestTravel  $requestTravel
     * @return \Illuminate\Http\Response
     */
    public function show(RequestTravel $requestTravel)
    {
        $allData = RequestTravel::orderBy('id', 'desc')->get();  
        //print_r($allData);      
        $output = '';

        if($allData->count() > 0) {
            foreach($allData as $value) { 
                
                $employee = Employee::find($value->emp_id);

                $output .= '<tr id="'.$value->id.'">       
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input travel_checkbox" type="checkbox" id="chk-'.$value->id.'" name="travel_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.$employee->name.'</td>
                                <td>'.$value->po.'</td>
                                <td>'.$value->request_type.'</td>
                                <td>'.$value->travel_type.'</td> 
                                <td>'.$value->status.'</td> 
                                <td>
                                    <a class="text-warning" target="_blank" href="'.url("travel_request/travel_pdf/pdf/$value->id").'" tooltip="Pdf Download" data-id="'.$value->id.'"><i class="bx bxs-download bx-sm"></i></a>
                                    <a class="text-warning" href="travel_request/edit/'.$value->id.'" tooltip="Edit" data-id="'.$value->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td> 
                            </tr>';
            }
        }
        echo $output;
    }

    //TRAVEL REQUEST PDF
    function pdf($id){
        
        //echo $this->convert_travel_request_data_to_html($id);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($this->convert_travel_request_data_to_html($id));
        return $pdf->stream();
    }

    function convert_travel_request_data_to_html($id){

        $requestTravelData = RequestTravel::find($id);
        $requestTravelDetail = RequestTravelDetail::where('travel_request_id', $requestTravelData->id)->get();
        $empDetail  = Employee::find($requestTravelData->emp_id);
        $empPosition = EmployeePosition::find($empDetail->position);
        $empDutyStation = DutyStation::find($empDetail->duty_station);

        $recommendedBy = Employee::find($requestTravelData->recommenedBy_id);        
        $recPosition = EmployeePosition::find($recommendedBy->position);
        $recDutyStation = DutyStation::find($recommendedBy->duty_station);

        $allRequestTypes = ['Planned', 'Ad hoc', 'Annual Leave'];
        $allTravelTypes = ['In-Country', 'International'];
        $requestStatus = ['Pending', 'Approved', 'Rejected'];
        $imageArray = ["jpg", "jpeg", "png"];
        $fileArray = ["pdf", "doc", "docx"];

        if($empDetail->signature != NULL){
            $signature = '<img src="'.url('uploads/signature').'/'.$empDetail->signature.'" height="30px>';
        }else{
            $signature = '<span style="
            border-bottom: 2px dotted black;
            width: 150px;
            height: 1px;
            display: inline-block;"></span>';
        }


        $output = '
        <style>
            table th, table td{
                padding:3px;
                text-align : left!important;
            }
            .pdf-chkbox {
                margin-top:7px;
            }
        </style>
        <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:20px;font-size:14px;">
                    <tr>
                        <th width="45%" style="text-align:right!important;padding-right:15px;">
                            <img src="http://dellsushil/LeaveManagementSystem/public/admin_assets/img/who-logo.jpg" style="width:150px;">
                        </th>
                        <th width="55%" style="text-align:left;">
                            <h2 style="margin-bottom:5px;">Duty Travel Request Form</h2>
                            <h4 style="margin-top:5px;">(In-country & International Travel)</h4>
                        </th>
                    </tr>
                </table>'; 

        $output .= '<table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:15px;">
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">Name of SSA Holder</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">'.$empDetail->name.', '.$empPosition->position.', '.$empDutyStation->work_place.'</th>
                        </tr>

                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">Request Type</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">
                                <div class="children">';
                                    foreach($allRequestTypes as $requestType){
                                        $output .= '<input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->request_type == $requestType) $output .='checked'; $output .='>'.$requestType.'';
                                    }
                    $output .='</div>
                            </th>
                        </tr>
                        
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">Travel Type</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">
                                <div class="children">';
                                    foreach($allTravelTypes as $travelType){
                                        $output .= '<input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->travel_type == $travelType) $output .='checked'; $output .='>'.$travelType.'';
                                    }
                    $output .='</div>
                            </th>
                        </tr>
                        
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">Purchase Order No.</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">'.$requestTravelData->po.'</th>
                        </tr>
                        
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">Purpose/Objective</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">'.$requestTravelData->travel_purpose.'</th>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:15px;">
                        <tr>
                            <th rowspan="2" style="border: 1px solid; font-size:13px;padding:5px;">S.N.</th>
                            <th colspan="2" style="border: 1px solid; font-size:13px;padding:5px;">Dates</th>
                            <th rowspan="2" style="border: 1px solid; font-size:13px;padding:5px;">Place(s)</th>
                            <th rowspan="2" style="border: 1px solid; font-size:13px;padding:5px;">Contact Address</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">From Dates</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px;">To Dates</th>
                        </tr>';
                        $count = 0;
                        $colspan = count($requestTravelDetail);
                        foreach($requestTravelDetail as $value){
                            $count++;
                            $output .='<tr>
                                        <th style="border: 1px solid; font-size:13px;padding:5px;">'.$count.'.</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px;">'.$value->from_date.'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px;">'.$value->to_date.'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px;">'.$value->place_of_halt.'</th>';
                                        if($count == 1){
                                            $output .='<th rowspan="'.$colspan.'" style="border: 1px solid; font-size:13px;padding:5px;">'.$requestTravelData->contact_address.'</th>';
                                        }
                                        
                            $output .='</tr>';
                        }

            $output .='<tr>
                            <th colspan="3" style="text-align:left;border: 1px solid; font-size:13px;padding:5px;">Invitation from Government</th>
                            <th colspan="2" style="border: 1px solid; font-size:13px;padding:5px;">
                                <div class="children">
                                    <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->government_invitation == 'Yes') $output .='checked'; $output .='>Yes
                                    <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->government_invitation == 'No') $output .='checked'; $output .='>No
                                </div>

                                <div class="attachment">';

                                if($requestTravelData->attachment != 'NULL'){
                                    if(in_array(pathinfo($requestTravelData->attachment, PATHINFO_EXTENSION), $imageArray) ){
                                        $output .='<img src="'.url('uploads/travel_attachment').'/'.$requestTravelData->attachment.'" class="rounded" style="height:80px;">';
                                    }else{
                                        $output .='<a target="_blank" href="'.url('uploads/travel_attachment').'/'.$requestTravelData->attachment.'">'.$requestTravelData->attachment.'</a>';
                                    }
                                }
                                
                    $output .='</div>
                            </th>
                        </tr>

                        <tr>
                            <th colspan="3" style="text-align:left;border: 1px solid; font-size:13px;padding:5px;">Previous Duty Travel Report(s) Submitted</th>
                            <th colspan="2" style="border: 1px solid; font-size:13px;padding:5px;">
                                <div class="children">
                                    <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->prev_travel_report == 'Yes') $output .='checked'; $output .='>Yes
                                    <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->prev_travel_report == 'No') $output .='checked'; $output .='>No
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <th colspan="3" style="text-align:left;border: 1px solid; font-size:13px;padding:5px;">Follow-up action(s) taken / recommended implemented</th>
                            <th colspan="2" style="border: 1px solid; font-size:13px;padding:5px;">
                                '.$requestTravelData->follow_up_action.'
                            </th>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:10px;">                        
                        <tr>
                            <th colspan="2" style="text-align:left;font-size:13px;padding:5px;">Security clearance obtained:</th>
                            <th style="font-size:13px;padding:5px;">
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->security_clearance_obtained == 'Yes') $output .='checked'; $output .='>Yes
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->security_clearance_obtained == 'No') $output .='checked'; $output .='>No
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->security_clearance_obtained == 'in_process') $output .='checked'; $output .='>In Process
                            </th>
                        </tr>
                                               
                        <tr>
                            <th colspan="3" style="text-align:left;font-size:16px;padding:5px;">Counterpart and/or other person participating in the filed:</th>
                        </tr> 

                        <tr>
                            <th style="text-align:left;font-size:13px;padding:5px;">Travel advance requested:</th>
                            <th style="font-size:13px;padding:5px;">
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->travel_advance_requested == 'Yes') $output .='checked'; $output .='>Yes
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->travel_advance_requested == 'No') $output .='checked'; $output .='>No
                            </th>
                            <th style="font-size:13px;padding:5px;">Amount NPR: <input style="border:0;border-bottom:1px;font-size:13px;" type="text"  value="'.$requestTravelData->travel_advance_amount.'"></th>
                        </tr> 

                        <tr>
                            <th colspan="2" style="text-align:left;font-size:13px;padding:5px;">Mode of Travel:</th>
                            <th style="font-size:13px;padding:5px;">
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->mode_of_travel == 'by_road') $output .='checked'; $output .='>By Road
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->mode_of_travel == 'by_air') $output .='checked'; $output .='>By Air
                            </th>
                        </tr> 

                        <tr>
                            <th colspan="2" style="text-align:left;font-size:13px;padding:5px;">If By Air, Official Vehicle for Airport Drop/Pick-up required:</th>
                            <th style="font-size:13px;padding:5px;">
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->official_vehicle_requied == 'Yes') $output .='checked'; $output .='>Yes
                                <input class="pdf-chkbox" type="checkbox"'; if($requestTravelData->official_vehicle_requied == 'No') $output .='checked'; $output .='>No
                            </th>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:10px;">                        
                        <tr>
                            <th colspan="3" style="text-align:left;font-size:13px;padding:5px;">Request for Hired Vehicle : '.$requestTravelData->hired_vehicle_request.'</th>
                        </tr>
                    </table>
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:10px;">                        
                        <tr>
                            <th style="text-align:left;font-size:13px;padding:5px;">Signature of Applicant : '.$signature.'</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;font-size:13px;padding:5px;">Recommended By : '.$recommendedBy->name.'</th>
                        </tr>
                        <tr>
                            <th style="text-align:left;font-size:13px;padding:5px;">Comments : '.$requestTravelData->comment.' </th>
                        </tr>                        
                    </table>
                    
                    
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:10px;">                        
                        <tr>
                            <th width="70%"></th>
                            <th width="30%">
                                <table>
                                    <tr>
                                        <th style="text-align:left;font-size:13px;">Approval:</th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:left;font-size:13px;">
                                            <div style="margin-top:30px;border-bottom: 2px dotted black;widtj:100%;
                                            height: 1px;"></div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align:left;font-size:13px;">
                                            <p>Allison Gocotano (Eugenio)</p>
                                            <p>Team Lead/WHE</p>
                                        </th> 
                                    </tr>
                                    <tr>                       
                                        <th style="text-align:left;font-size:13px;">Date:</th>
                                    </tr>
                                </table>
                            </th>
                            
                        </tr>

                        <tr>
                            <th colspan="2" style="text-align:left;font-size:14px;">*SSA holders need RD\'s approval for International Travel</th>
                        </tr>
                    </table>';
        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequestTravel  $requestTravel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $selectedDeduction = '';
        $requestTravelData = RequestTravel::find($id);
        $requestTravelDetail = RequestTravelDetail::where('travel_request_id', $requestTravelData->id)->get();
        $allEmployee  = Employee::all();
        $allRequestTypes = ['Planned', 'Ad hoc', 'Annual Leave'];
        $allTravelTypes = ['In-Country', 'International'];
        $recommendedEmployee  = Employee::where('id', '!=', $id)->get();
        $requestStatus = ['Pending', 'Approved', 'Rejected'];

        $imageArray = ["jpg", "jpeg", "png"];
        $fileArray = ["pdf", "doc", "docx"];
        
        return view('TravelRequest/edit_travel_request', [
            'requestTravelData' => $requestTravelData, 
            'requestTravelDetail' => $requestTravelDetail,
            'allEmployee' => $allEmployee,
            'allRequestTypes' => $allRequestTypes,
            'allTravelTypes' => $allTravelTypes,
            'recommendedEmployee' => $recommendedEmployee,
            'imageArray' => $imageArray,
            'fileArray' => $fileArray,
            'requestStatus' => $requestStatus
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequestTravel  $requestTravel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $requestDetail = RequestTravel::find($request->request_id);
            
        $requestDetail->emp_id = $request->input('employee');
        $requestDetail->recommenedBy_id = $request->input('recommeded_by');
        $requestDetail->request_type = $request->input('request_type');
        $requestDetail->travel_type = $request->input('travel_type');
        $requestDetail->po = $request->input('po');
        $requestDetail->travel_purpose = $request->input('purpose');
        $requestDetail->contact_address = $request->input('contact_address');
        $requestDetail->government_invitation = $request->input('government_invitaion');
        $requestDetail->prev_travel_report = $request->input('prev_duty_report');
        $requestDetail->security_clearance_obtained = $request->input('security_clearance');            
        $requestDetail->follow_up_action = $request->input('follow_up');
        $requestDetail->travel_advance_requested = $request->input('travel_advance_request');
        $requestDetail->mode_of_travel = $request->input('travel_mode');
        $requestDetail->travel_advance_amount = $request->input('advance_amount');            
        $requestDetail->official_vehicle_requied = $request->input('vehicle_required');
        $requestDetail->hired_vehicle_request = $request->input('hired_vehicle');
        $requestDetail->comment = $request->input('comment');
        $requestDetail->status = $request->input('request_status');

        //Travel Attachment
        if($request->hasFile('attachment')) {

            $file = $request->file('attachment');
            
            $allowed_ext = array("jpg", "jpeg", "png", "pdf", "doc", "docx");
            $ext = $file->getClientOriginalExtension();

            if(in_array($ext, $allowed_ext)) {  	
                $file_name = rand().time().'.'.$ext;
                if($file->move('uploads/travel_attachment/', $file_name)) {
                    $attachment = $file_name;
                }
            }
                
        }else{
            $attachment = $request->old_attachment;
        }

        $requestDetail->attachment = $attachment;   
        $requestDetail->save();

        if($requestDetail) {
            
            $removeIds = explode(",",$request->input('removeIds'));
            $night_halt = $request->input('night_halt');

            

            foreach($night_halt as $key => $data){

                $detailId = $request->input('detailIds')[$key];
                //print_r($detailId);
                if($detailId !=''){

                    RequestTravelDetail::whereIn('id', $removeIds)->delete();

                    RequestTravelDetail::where('id', '=', $detailId)
                        ->update([
                            'from_date' => $request->input('from_date')[$key],                 
                            'to_date' => $request->input('to_date')[$key],
                            'place_of_halt' => $data,
                            'created_at' => Carbon::now()->toDateTimeString(),
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);

                }else{

                    RequestTravelDetail::insert([ 
                        'travel_request_id' => $request->request_id, 
                        'from_date' => $request->input('from_date')[$key],
                        'to_date' => $request->input('to_date')[$key],
                        'place_of_halt' => $data,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);

                }
                
            }
            
            $signal = "updated";
        }else {
            $signal = "not_updated";
        }

        return response()->json($signal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequestTravel  $requestTravel
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestTravel $requestTravel)
    {
        //
    }
}
