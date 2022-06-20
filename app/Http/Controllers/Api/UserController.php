<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DutyStation;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $folder = 'user';
    protected $panel='User';
    protected $folder_path;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return $this->user->with('roles:name', 'profile')
        // ->whereHas('profile', function ($q) {
        //     $q->where('supervisor', auth()->user()->id);
        // })
        ->latest()->paginate(4);
        // $data['roles'] = Role::select('name', 'id')->get();
        // $data['supervisors'] = User::role('supervisor')->pluck('name', 'id');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['is_verified'] = 1;
        $user = User::create($request->all());
        $user->assignRole($request['roles']);

        return response()->json($user);
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
        try {
            if (auth()->user()->hasPermissionTo('update_users')) {
                $user  = User::findOrFail($id);
            } elseif (auth()->user()->hasPermissionTo('update_supervisee')) {
                try {
                    $user = User::where('id', $id)->whereHas('profile', function ($query) {
                        $query->where('supervisor', auth()->user()->id);
                    })->firstOrFail();
                } catch (\Exception $e) {
                    return response()->json(['error' => 'You are not allowed to update this user'], 500);
                }
            }
            /*If user change the password*/
            if (!empty($request->password)) {
                $request->merge(['password' => Hash::make($request['password'])]);
            }
            
            $user->update($request->all());
            $roles = [];
            foreach ($request->roles as $role) {
                array_push($roles, $role['name']);
            }
            $user->syncRoles($roles);

            $data['error']='false';
            $data['message']='User Info! Has Been Updated';
        } catch (\Exception $exception) {
            $data['error']='true';
            $data['message']=$exception->getMessage();
        }

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return ['message' =>'User Deleted'];
    }
}
