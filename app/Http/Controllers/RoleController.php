<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('role.index');
    }

    public function getRole()
    {
        $roles = Role::orderBy('id', 'DESC')->get();

        $data = [];
        $x = 0;
        foreach ($roles as $role => $value) {
            $data[$role]['name'] = $value->name;
            $data[$role]['no'] = ++$x;
            $data[$role]['id'] = Crypt::encrypt($value->id);
            $createdAt = Carbon::parse($value->created_at);
            $data[$role]['created_at'] = $createdAt->format('d-m-Y H:i:s');
            $updatedAt = Carbon::parse($value->updated_at);
            $data[$role]['updated_at'] = $updatedAt->format('d-m-Y H:i:s');
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
        $permissions = Permission::get();
        $groups = DB::table('permissions')->select('group')->groupBy('group')->get();
        return view('role.create', compact(['permissions','groups']));
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
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('roles/create')
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        $notification = array(
            'message' => 'Role successfully added',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.index')->with($notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find(Crypt::decrypt($id));
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", Crypt::decrypt($id))
            ->get();

        return view('role.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $decId = Crypt::decrypt($id);
        $role = Role::find($decId);
        $id = $id;
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $decId)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $groups = DB::table('permissions')->select('group')->groupBy('group')->get();

        return view('role.edit', compact('role', 'permission', 'rolePermissions', 'id', 'groups'));
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
        $decId = Crypt::decrypt($id);

        if ($decId == '1') {
            $notification = array(
                'message' => 'Cannot update admin role',
                'alert-type' => 'error'
            );

            return redirect()->route('roles.index')
                ->with($notification);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . Crypt::decrypt($id),
            'permission' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('roles/' . $decId . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::find($decId);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        $notification = array(
            'message' => 'Role successfully updated',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.index')
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
        if (Crypt::decrypt($request->id) == '1') {
            $notification = array(
                'message' => 'Cannot delete admin role',
                'alert-type' => 'error'
            );

            return redirect()->route('roles.index')
                ->with($notification);
        }

        DB::table("roles")->where('id', Crypt::decrypt($request->id))->delete();

        $notification = array(
            'message' => 'Role deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.index')
            ->with($notification);
    }
}
