<?php

namespace App\Http\Controllers; 

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$allGroups = Group::all();
        //return view('groups', ['allGroups' => $allGroups]);
        return view('Settings/groups');
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

        $title .="Add New Group";

        $content .='<form id="group_form">
                    <div class="result"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Group Name <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="group" id="group" placeholder="Enter Group Name" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Weekened <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-saturday" name="weekened[]" value="Sat">
                                <label class="form-check-label" for="chk-saturday">Saturday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-sunday" name="weekened[]" value="Sun">
                                <label class="form-check-label" for="chk-sunday">Sunday</label>
                            </div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="save_group">Submit</button>
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
        $sql_check = Group::where('group_name', '=', $request->input('group'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            $model = new Group();
            $model->group_name = $request->input('group');
            $weekeneds = $request->input('weekened');
            $weekened = '';
            foreach($weekeneds as $wek){            
                $weekened .= $wek.',';
            }
            $model->weekened = substr($weekened, 0,-1);
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $allGroups = Group::all();
        $output = '';

        if($allGroups->count() > 0) {
            foreach($allGroups as $group) { 
                $output .= '<tr id="'.$group->id.'">      
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input group_checkbox" type="checkbox" id="chk-'.$group->id.'" name="group_checkbox[]" value="'.$group->id.'">
                                        <label class="form-check-label" for="chk-'.$group->id.'"></label>
                                    </div>
                                </td>
                                <td>'.ucwords($group->group_name).'</td>
                                <td>'.$group->weekened.'</td>
                                <td>'.date('d M, Y', strtotime($group->created_at)).'</td> 
                                <td>
                                <a class="text-warning group_edit" href="javascript:void(0)" data-id="'.$group->id.'"><i class="bx bxs-edit bx-sm"></i></a>
                                </td>
                            </tr>';
            }
        }
        
        echo $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $groupId = $request->group_id;
        $group = Group::find($groupId);
        $weekened = explode(",", $group->weekened);
        $content = $title='';

        $title .="Edit Group";

        $content .='<form id="edit_group_form">
                    <div class="result_group"></div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Group Name <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="edit_group_name" id="edit_group_name" value="'.$group->group_name.'" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="edit_group_id" id="edit_group_id" value="'.$group->id.'">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Weekened <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-saturday" name="weekened[]" value="Sat"'; if(in_array('Sat', $weekened)) $content.='checked'; $content .='>
                                <label class="form-check-label" for="chk-saturday">Saturday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-sunday" name="weekened[]" value="Sun"'; if(in_array('Sun', $weekened)) $content.='checked'; $content .='>
                                <label class="form-check-label" for="chk-sunday">Sunday</label>
                            </div>
                        </div>
                    </div>
                            
                    <div class="form-group row text-right mb-0">
                        <label for="inputPassword3" class="col-md-12 col-form-label"></label>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-danger mr-1 btn-md mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info btn-md" id="update_group">Submit</button>
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {        
        //return $request;
        $group = Group::find($request->edit_group_id);
        
        $sql_check = Group::where('group_name', '=', $request->input('edit_group_name'))->where('id', '!=', $request->input('edit_group_id'))->first();
        //dd($sql_check);
        if (!$sql_check) {
            
            $group->group_name=$request->input('edit_group_name');
            $weekeneds = $request->input('weekened');
            $weekened = '';
            foreach($weekeneds as $wek){            
                $weekened .= $wek.',';
            }
            $group->weekened = substr($weekened, 0,-1);
            $group->save();

            if($group) { 
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
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $groupIds = $request->group_id;
        Group::whereIn('id', $groupIds)->delete();
        return 'success';
    }
}
