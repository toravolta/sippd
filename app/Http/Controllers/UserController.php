<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('user.index');
    }

    public function getUser()
    {
        $users = User::all();

        $data = [];
        $x = 0;
        foreach ($users as $user => $value) {
            $data[$user]['no'] = ++$x;
            $data[$user]['name'] = $value->name;
            $data[$user]['email'] = $value->email;

            $roleNames = [];
            if (!empty($value->getRoleNames())) {
                foreach ($value->getRoleNames() as $roleName) {
                    $roleNames[] = $roleName;
                }
            }

            $data[$user]['roles'] = implode(",", $roleNames);
            $data[$user]['id'] = Crypt::encrypt($value->id);
            $createdAt = Carbon::parse($value->created_at);
            $data[$user]['created_at'] = $createdAt->format('d-m-Y H:i:s');
            $updatedAt = Carbon::parse($value->updated_at);
            $data[$user]['updated_at'] = $updatedAt->format('d-m-Y H:i:s');
        }

        $responseData = ["data" => $data];

        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('users/create')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $notification = array(
            'message' => 'User successfully added',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find(Crypt::decrypt($id));
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(Crypt::decrypt($id));
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->first();
        $id = $id;

        return view('user.edit', compact('user', 'roles', 'userRole', 'id'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Crypt::decrypt($id),
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('users/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find(Crypt::decrypt($id));
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', Crypt::decrypt($id))->delete();

        $user->assignRole($request->input('roles'));

        $notification = array(
            'message' => 'User successfully updated',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')
            ->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        User::find(Crypt::decrypt($request->id))->delete();

        $notification = array(
            'message' => 'User deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')
            ->with($notification);
    }
}
