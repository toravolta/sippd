<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class PermissionController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permission.index');
    }

    /**
     * Get permission data.
     *
     * @return \Illuminate\Http\Response
     */
    public function getpermission()
    {
        // $permissions = Permission::all();

        $permissions = DB::table('permissions')
                ->orderBy('name', 'asc')
                ->get();

        $data = [];
        $x = 0;
        foreach ($permissions as $permission => $value) {
            $data[$permission]['no'] = ++$x;
            $data[$permission]['name'] = $value->name;
            $data[$permission]['label'] = $value->label;
            $data[$permission]['group'] = $value->group;
            $data[$permission]['id'] = Crypt::encrypt($value->id);
            $createdAt = Carbon::parse($value->created_at);
            $data[$permission]['created_at'] = $createdAt->format('d-m-Y H:i:s');
            $updatedAt = Carbon::parse($value->updated_at);
            $data[$permission]['updated_at'] = $updatedAt->format('d-m-Y H:i:s');
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
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['guard_name'] = 'web';

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions',
            'label' => 'required',
            'group' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('permission/create')
                ->withErrors($validator)
                ->withInput();
        }

        Permission::create($input);
        Artisan::call('permission:cache-reset');

        $notification = array(
            'message' => 'Permission successfully added',
            'alert-type' => 'success'
        );

        return redirect()->route('permission.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::find(Crypt::decrypt($id));
        return view('permission.edit', compact(['permission', 'id']));
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
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,'.Crypt::decrypt($id),
            'label' => 'required',
            'group' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('permission/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $permission = Permission::find(Crypt::decrypt($id));
        $permission->update($input);
        Artisan::call('permission:cache-reset');

        $notification = array(
            'message' => 'Permission successfully updated',
            'alert-type' => 'success'
        );

        return redirect()->route('permission.index')
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
        Permission::find(Crypt::decrypt($request->id))->delete();
        Artisan::call('permission:cache-reset');

        $notification = array(
            'message' => 'Permission deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('permission.index')
            ->with($notification);
    }

    /**
     * Get group name
     *
     * @param Request $request
     * @return json 
     */
    public function getGroup(Request $request)
    {
        if ($request->has('q')) {
            $find = $request->q;
            $data = DB::table('permissions')->select('group')->where('group', 'LIKE', '%' . $find . '%')->groupBy('group')->get();
            return response()->json($data);
        } else {
            $data = DB::table('permissions')->select('group')->groupBy('group')->get();
            return response()->json($data);
        }
    }

    /**
     * Get group by id
     *
     * @param string id
     * @return json 
     */
    public function getGroupById($id)
    {
        $data = DB::table('permissions')->select('group')->where('id', Crypt::decrypt($id))->first();
        return response()->json($data);
    }
}
