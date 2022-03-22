<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Settings/duty_deduction');
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

        $title .="Add New Deduction Item";

        $content .='<form id="deduction_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Deduction Item <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="deduction_item" id="deduction_item" placeholder="Enter Deduction Item" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Deduction <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="deduction" id="deduction" placeholder="Enter Deduction" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_deduction">Submit</button>
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
        $sql_check = Deduction::where('deduction_item', $request->input('deduction_item'))->where('deduction', $request->input('deduction'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new Deduction();
            $model->deduction_item = $request->input('deduction_item');
            $model->deduction = $request->input('deduction');
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
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allDeduction = Deduction::all();
        $output = '';

        if($allDeduction->count() > 0) {
            foreach($allDeduction as $value) { 
                $output .= '<tr id="'.$value->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input deduction_checkbox" type="checkbox" id="chk-'.$value->id.'" name="deduction_checkbox[]" value="'.$value->id.'">
                                        <label class="form-check-label" for="chk-'.$value->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($value->deduction_item).'</td>
                                <td>'.$value->deduction.'</td>
                                <td>'.date('d M, Y', strtotime($value->created_at)).'</td> 
                                <td>
                                <a class="text-warning deduction_edit" href="javascript:void(0)" data-id="'.$value->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $deductionId = $request->deduction_id;
        $deduction = Deduction::find($deductionId);

        $content = $title='';

        $title .="Edit Deduction";

        $content .='<form id="edit_deduction_form">
                    <div class="result_group"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Deduction Item <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_deduction_item" id="edit_deduction_item" value="'.$deduction->deduction_item.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="edit_deduction_id" id="edit_deduction_id" value="'.$deduction->id.'">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Deduction <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_deduction" id="edit_deduction" placeholder="Enter Deduction" value="'.$deduction->deduction.'" autocomplete="off" required>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_deduction">Submit</button>
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
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {        
        $deduction = Deduction::find($request->edit_deduction_id);
        
        $sql_check = Deduction::where('deduction_item', $request->input('deduction_item'))->where('deduction', $request->input('deduction'))->where('id', '!=', $request->input('edit_deduction_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $deduction->deduction_item=$request->input('edit_deduction_item');
            $deduction->deduction=$request->input('edit_deduction');
            $deduction->save();

            if($deduction) { 
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
     * @param  \App\Models\Deduction  $deduction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $deductionIds = $request->deduction_id;
        Deduction::whereIn('id', $deductionIds)->delete();
        return 'success';
    }
}
