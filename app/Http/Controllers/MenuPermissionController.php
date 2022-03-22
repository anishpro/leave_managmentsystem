<?php

namespace App\Http\Controllers;

use App\Models\MenuPermission;
use App\Models\Role;
use App\Models\Module;
use App\Models\Menu; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MenuPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allRoles = Role::all();
        return view('RolePermission.access_control', [
            'allRoles' => $allRoles]);
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
        //DELETE UNSELECTED ROLE PERMISSION FOR UNSELECTED MODULE
        if(count($request->delete_permission_id) > 0){
            $permissionIds = $request->delete_permission_id;
            MenuPermission::whereIn('id', $permissionIds)->where('role_id', $request->input('role_id'))->delete();
        } 
        
        if(count($request->menu_checkbox) > 0){
            
            foreach($request->menu_checkbox as $menu){
                print_r($menu);
                $sql_check = MenuPermission::where('role_id', '=', $request->input('role_id'))->where('menu_id', $menu)->first();
                if (!$sql_check) {                   

                    MenuPermission::insert([ 
                        'role_id' => $request->input('role_id'), 
                        'menu_id' => $menu,      
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            }
            $signal = 'inserted';
            
        }else{
            $signal = '';
        }

        //return response()->json($signal);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MenuPermission  $menuPermission
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $allModule = Module::all();
        $output = '';

        if($allModule->count() > 0) {
            foreach($allModule as $key => $module) { 

                $menus = Menu::where('module_id', $module->id)->where('status', 1)->get();

                $output .= '<table style="margin-top:15px;">
                                <tr id="'.$module->id.'">
                                    <td>
                                        <h6>
                                            <span>'.($key+1).'.</span>
                                            <span>'.$module->module_name.'</span>
                                        </h6>
                                    </td>
                                </tr>
                            </table>
                            <table style="margin-left:15px;">';
                                if($menus->count() > 0) {
                                    foreach($menus as $menu){

                                        $permission = MenuPermission::where('role_id', $request->role_id)->where('menu_id', $menu->id)->first();
                                        
                                        $output .= '<tr id="'.$module->id.'">
                                                        <td>
                                                            <div class="form-check">
                                                                <input class="form-check-input menu_checkbox" type="checkbox" id="chk-'.$menu->id.'" name="menu_checkbox[]" value="'.$menu->id.'"'; 
                                                                if($permission && $permission->count() > 0) $output .='checked';
                                                                $output .='>
                                                                <label class="form-check-label" for="chk-'.$menu->id.'">'.ucwords($menu->menu_name).'</label>
                                                            </div>
                                                        </td>     
                                                    </tr>';    
                                    }
                                }      
                $output .= '<table>';               
                
            }
        }
        echo json_encode($output);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MenuPermission  $menuPermission
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuPermission $menuPermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuPermission  $menuPermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuPermission $menuPermission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MenuPermission  $menuPermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuPermission $menuPermission)
    {
        //
    }
}
