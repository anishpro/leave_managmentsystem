<?php

namespace App\Http\Controllers;

use App\Models\TravelDetail;
use App\Models\Travel;
use App\Models\RequestTravel;
use App\Models\RequestTravelDetail;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\EmployeePosition;
use App\Models\DutyStation;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;
use DB;

class TravelDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return view('TravelRequest/travel_detail'); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //
        $requestData = RequestTravel::find($id);
        $empData = Employee::find($requestData->emp_id);
        $allDeductions = Deduction::all();
        $travelRequestDetail = RequestTravelDetail::where('travel_request_id', $id)->get();

        return view('TravelRequest/travel_expense', [
                'empData' => $empData, 
                'requestData' => $requestData, 
                'allDeductions' => $allDeductions,
                'travelRequestDetail' => $travelRequestDetail
        ]);
    }

    // GET DEDUCTION

    public function getDeduction(Request $request){
        $deduction = Deduction::find($request->deduction_id);

        //print_r($deduction);
        echo json_encode($deduction->deduction);
    }

    /**
     * Store a newly created resource in storage. 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        //DB::enableQueryLog();
        //print_r($request->travel_request_id);
        $sql_check = Travel::where('travel_request_id', $request->input('travel_request_id'))->where('PO', '=', $request->input('po'))->first();
        // $quries = DB::getQueryLog(); 
        // dd($quries);

        if (!$sql_check) {

            $model = new Travel();
            $requestData = RequestTravel::find($request->input('travel_request_id'));

            $model->emp_id = $request->input('employee');
            $model->travel_request_id = $request->input('travel_request_id');
            $model->travel_month = $request->input('travel_month');
            $model->PO = $request->input('po');
            $model->misc_expense = $request->input('misc_expense');
            $night_halt = $request->input('night_halt');
            $totalAmount = 0;            

            foreach($night_halt as $key => $data){
                $totalAmount = $totalAmount + $request->input('total_amount')[$key];
            }

            $model->amount = $totalAmount;            
            $model->save();
            if($model) { 

                $travel_id = $model->id;

                foreach($night_halt as $key => $data){

                    TravelDetail::insert([ 
                        'travel_id' => $travel_id, 
                        'deduction_id' => $request->input('deduction')[$key],                       
                        'night_halt' => $data,
                        'date_from' => $request->input('from_date')[$key],
                        'date_to' => $request->input('to_date')[$key],
                        'per_deim_rate' => $request->input('per_diem_rate')[$key],
                        'no_of_days' => $request->input('no_of_days')[$key],
                        'deduction_amount' => $request->input('deduction_amount')[$key],
                        'total_amount' => $request->input('total_amount')[$key],
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }

                //Update Travel Request Payment Status
                $requestData->invoice_status = 'Complete';
                $requestData->payment_status = 'Complete';
                $requestData->save();

                $signal = "inserted";
            }else{
                $signal = "not_inserted";
            }

        }else{
            $signal = "exist";
        }
        return response()->json($signal);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TravelDetail  $travelDetail
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $approvedRequest = RequestTravel::where('status', 'Approved')->get();        
        //print_r($approvedRequest);      
        $output = '';

        if($approvedRequest->count() > 0) {
            foreach($approvedRequest as $value) { 
                
                $travelData = Travel::where('emp_id', $value->id)->where('PO', $value->po)->first();
                //print_r($travelData);exit;
                if($travelData){
                    $travel_month = $travelData->travel_month;
                    $amount = $travelData->amount;
                }else{
                    $travel_month = '-';
                    $amount = '-';
                }
                $employee = Employee::find($value->emp_id);

                $output .= '<tr id="'.$value->id.'">       
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input travel_checkbox" type="checkbox" id="chk-'.$value->id.'" name="travel_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.$employee->name.'</td>
                                <td>'.$travel_month.'</td>
                                <td>'.$value->po.'</td>
                                <td>'.$amount.'</td> 
                                <td>';
                                    if($travelData){
                                        $output .= '<a class="text-warning" target="_blank" href="'.url("travel/dynamic_pdf/pdf/$travelData->id").'" tooltip="Pdf Download" data-id="'.$travelData->id.'"><i class="bx bxs-download bx-sm"></i></a>';
                                        //<a class="text-warning" href="travel/edit/'.$travelData->id.'" tooltip="Edit" data-id="'.$travelData->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                    }else{
                                        $output .='<a href="travel/add/1" class="text-info" tooltip="Generate Invoice"><i class="bx bx-plus-circle bx-sm"></i></a>';
                                    }
                    $output .= '</td> 
                            </tr>';
            }
        }
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TravelDetail  $travelDetail
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $selectedDeduction = '';
        $travelData = Travel::find($id);
        $travelDetail = TravelDetail::where('travel_id', $travelData->id)->get();
        //print_r($travelDetail);
        $employee = Employee::find($travelData->emp_id);
        $allDeductions = Deduction::all();

        foreach($travelDetail as $data){

            $selected = Deduction::find($data->deduction_id);
            $selectedDeduction = $selected->deduction.',';

        }
        
        return view('TravelRequest/edit_travel', [
                'travelData' => $travelData, 
                'travelDetail' => $travelDetail, 
                'employee' => $employee, 
                'selectedDeduction' => $selectedDeduction,
                'allDeductions' => $allDeductions,
            ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TravelDetail  $travelDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $travelDetail = TravelDetail::find($request->travel_id);
        
        $travelDetail->deduction_id = $request->input('deduction');
        $travelDetail->travel_month = $request->input('travel_month');
        $travelDetail->PO = $request->input('po');            
        $travelDetail->misc_expense = $request->input('misc_expense');
        $travelDetail->night_halt = $request->input('night_halt');
        $travelDetail->date_from = $request->input('from_date');
        $travelDetail->date_to = $request->input('to_date');
        $travelDetail->no_of_days = $request->input('no_of_days');
        $travelDetail->per_deim_rate = $request->input('per_diem_rate');
        $travelDetail->deduction_amount = $request->input('deduction_amount');
        $travelDetail->total_amount = $request->input('total_amount');
        $travelDetail->save();

        if($travelDetail) { 
            $signal = "inserted";
        }else{
            $signal = "not_inserted";
        }
        return response()->json($signal);
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
        $travelData = Travel::find($id);
        $employee = Employee::find($travelData->emp_id);
        $employeePos = EmployeePosition::find($employee->position);
        $station = DutyStation::find($employee->duty_station);
        $travelDetail = TravelDetail::where('travel_id', $travelData->id)->get(); 

        if($travelData->misc_expense != 'NULL'){
            $expense = $travelData->misc_expense;
            $totalAmount = $travelData->amount + $travelData->misc_expense;
        }else{
            $expense = '-';
            $totalAmount = $travelData->amount;
        }

        $output = '
                <h3 align="center">
                    <img src="http://dellsushil/LeaveManagementSystem/public/admin_assets/img/who-logo.jpg" style="width:100px;">
                </h3>
                <h4 align="center">DSA Calculation for SSA Holders</h4>
                <h4 align="center">OFFICE USE ONLY</h4>
                <table width="100%" style="border-collapse: collapse; border: 0px;">
                    <tr>
                        <th style="padding:5px;text-align:left;" width="50%">Name : '.$employee->name.'</th>
                        <th style="padding:5px;text-align:right;" width="50%">Duty Station : '.$station->work_place.'</th>
                    </tr>
                    <tr>
                        <th style="padding:5px;text-align:left;" width="50%">Position : '.$employeePos->position.'</th>
                        <th style="padding:5px;text-align:right;" width="50%">For the month of : '.$travelData->travel_month.'</th>
                    </tr>
                    <tr>
                        <th style="padding:5px;text-align:left;" width="100%">PO : #'.$travelData->PO.'</th>
                    </tr>';    
        $output .= '</table>
                    <br/>
                    <table width="100%" style="border-collapse: collapse; border: 0px;margin-bottom:100px;">
                        <tr>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="10%">S.N</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">PLACE OF NIGHT HALT</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="30%">DATES</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">NO. OF DAYS</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">PER DIEM RATE</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">AMOUNT</th>
                        </tr>';
                        $count = 0;
                        foreach($travelDetail as $value){

                            $date_from = $value->date_from;
                            $date_to = $value->date_to;
                            
                            $date = Carbon::parse($date_from)->format('d').' to '.Carbon::parse($date_to)->format('d').' '.Carbon::parse($date_to)->format('F').' '.Carbon::parse($date_to)->format('Y');

                            $count++;
                            $output .='<tr>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="10%">'.$count.'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="30%">'.$value->night_halt.'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">
                                            <p>'.$date.'</p>
                                            <p>Last Day: '.Carbon::parse($date_to)->format('d').' '.Carbon::parse($date_to)->format('F').' '.Carbon::parse($date_to)->format('Y').'</p>
                                        </th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">'.$value->no_of_days.'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">'.number_format($value->per_deim_rate, 2).'</th>
                                        <th style="border: 1px solid; font-size:13px;padding:5px; " width="15%">'.number_format($value->total_amount, 2).'</th>
                                    </tr>';
                        }
                        
                        $output .= '<tr>
                                    <th colspan="5" style="border: 1px solid; font-size:13px;padding:5px; " width="10%">TOTAL DSA PAYABLE</th>
                                    <th style="border: 1px solid; font-size:13px;padding:5px; " width="10%">'.number_format($travelData->amount, 2).'</th>
                                </tr>';
        $output .= '</table>
                    <h5 style="width:100%;">Daily Allowance <span style="float:right;">in NPR '.number_format($travelData->amount, 2).'</span></h5>
                    <h5 style="width:100%;">Travel Cost and Misc. Expenses <span style="float:right;">'.number_format($expense, 2).'</span></h5>
                    <table width="100%" style="border-collapse: collapse; border: 0px;">
                        <tr>
                            <th colspan="6" style="border: 1px solid; font-size:13px;padding:5px; " width="10%">Total Payable</th>
                            <th style="border: 1px solid; font-size:13px;padding:5px; " width="10%">'.number_format($totalAmount, 2).'</th>
                        </tr>
                    </table>
                    <p style="float:right;font-size:11px;">System generated</p>';
        return $output;
    } 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TravelDetail  $travelDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $travelIds = $request->travel_id;
        TravelDetail::whereIn('id', $travelIds)->delete();
        return 'success';
    }
}
