<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductHandover;
use App\Models\admin;
use App\Models\LeaveType;
use App\Models\MapContractLeave;
use App\Models\EmployeeContract;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth; 

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //echo "hello";
        return view('login');
    }


    /**
     * Auth.
     * 
     */
    public function auth(Request $request) 
    {
        //return $request->post();

        $username=$request->post('username');
        $password=md5($request->post('log_password'));

        $result=Admin::where(['username'=>$username, 'password'=>$password])->get();
        //print_r($result);exit;

        if(isset($result[0]->id)){

            $request=session()->put('ADMIN_LOGIN',true);
            $request=session()->put('ADMIN_ID',$result[0]->id);
            $request=session()->put('ROLE',$result[0]->role_id);
            $request=session()->put('EMP_id',$result[0]->emp_id);
            $request=session()->put('USERNAME', $result[0]->username);
            return redirect('dashboard');

        }else{

            $request->session()->flash('error','Please enter valid login credential');
            return redirect('/');
        }
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show() 
    {
        $leaveType = '';
        $allEmployees = Employee::all();        
        $allLeaves = LeaveType::where('mapping_required', 'yes')->get();
        foreach($allLeaves as $val){
            $leaveType .= $val->leave_type.', ';
        }
        $leaveType = substr($leaveType , 0, -2);
        $output = '';

        if($allEmployees->count() > 0) {
            foreach($allEmployees as $employee) { 
                $totalLeaves = '';
                $totalTakenLeaves = '';
                $allRemainingLeaves = '';
                $empContract = EmployeeContract::where('employee_id', '=', $employee->id)->orderBy('id', 'DESC')->first();
                //dd($empContract);
                if($empContract){
                    
                    foreach($allLeaves as $val){
                        
                        $totalTaken = 0;
                        $remainingLeaves = 0;
                        
                        //taken leaves
                        $takenLeaves = LeaveApplication::where('emp_id', $employee->id)->where('contract_id', $empContract->id)->where('leave_type_id', $val->id)->get();
                        if($takenLeaves){
                            foreach($takenLeaves as $taken){
                                $totalTaken = $totalTaken + $taken->leave_days;
                            }
                            $totalTakenLeaves .= $totalTaken.', ';
                        }else{
                            $totalTakenLeaves .= 'N/A, '; 
                        }

                        //total leave counts
                        $data = MapContractLeave::where('contract_month', '=', $empContract->no_of_months)->where('leave_type_id', '=', $val->id)->first();
                        
                        if($data){
                            $totalLeaves .= $data->leave_days.', '; 
                            $remainingLeaves = ($data->leave_days - $totalTaken);
                        }else{
                            $totalLeaves .= 'N/A, '; 
                        }

                        $allRemainingLeaves .= $remainingLeaves.', ';
                                               
                    }
                    $totalLeaves = substr($totalLeaves , 0, -2);
                    $totalTakenLeaves = substr($totalTakenLeaves , 0, -2);

                   
                    $totalTakenLeavesArray = substr($allRemainingLeaves , 0, -2);

                    $output .= '<tr> 
                                    <td>'.ucwords($employee->name).'</td>
                                    <td>'.$leaveType.'</td> 
                                    <td>'.$totalLeaves.'</td> 
                                    <td>'.$totalTakenLeaves.'</td> 
                                    <td>'.$totalTakenLeavesArray.'</td>
                                </tr>';
                }
            }
        }
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(admin $admin) 
    {
        //
    }

    public function logout(Request $request) { 
        Session::flush();
        
        Auth::logout();

        return redirect('/');
    }
}
