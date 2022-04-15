<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Hash;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    protected $folder = 'users';
    public function __construct(Employee $model)
    {
        $this->model = $model;
        $this->folder_path = 'images'.DIRECTORY_SEPARATOR.$this->folder;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->with('profile')->first();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $path =  $this->folder_path.DIRECTORY_SEPARATOR.$request->user_id;
        parent::checkFolderExist($path);
        try {
            if (strlen($request->profile) > 50) {
                $request->merge(['profile' => parent::makeImageWithThumb($request->user_id, $request->profile, $path)]);
            }
            if (strlen($request->signature) > 50) {
                $request->merge(['signature' => parent::makeImageWithThumb('signature_'.$request->user_id, $request->signature, $path)]);
            }
            if (!$request->emp_code) {
                $request['emp_code'] = parent::getRandId();
            }
            try {
                Employee::updateOrCreate(['id' => $request->id], $request->all());
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            $data['error']='false';
            $data['message']='Your Profile Info! Has Been Updated';
        } catch (\Exception $e) {
            $data['error']='true';
            $data['message']=$e->getMessage();
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updatePassword(Request $request)
    {
        $user =  auth('api')->user();
        $this->validate($request, [
            'oldpassword' => 'required|min:6',
            'newpassword' => 'required|min:6||required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'required|min:6'
        ]);

        if (password_verify($request->oldpassword, $user->password)) {
            if (!empty($request->newpassword)) {
                $newpass = Hash::make($request->newpassword);
            } else {
                $response['result'] = 'Error!';
                $response['type'] = 'warning';
                $response['message'] = 'Pleae enter new password';
                return $response;
            }
            $user->update(['password'=> $newpass]);
            $response['result'] = 'success!';
            $response['type'] = 'success';
            $response['message'] = 'Password updated successfully';
            return $response;
        } else {
            $response['result'] = 'Error!';
            $response['type'] = 'warning';
            $response['message'] = 'Old Password doesnt matched';
            return $response;
        }
    }
}
