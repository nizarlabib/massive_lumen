<?php

namespace App\Http\Controllers;

use App\Models\RoleUser;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function createRoleUser(Request $request)
    {
        $roleuser = RoleUser::create([
            'nama_role' => $request->nama_role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New role user created',
            'data' => [
                'roleuser' => $roleuser
            ]
        ]);
    }

    public function getAllRoleUser()
    {
        $roleuser = RoleUser::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all role users grabbed',
            'role user' => $roleuser,
        ], 200);
    }

    public function getRoleUserById(Request $request)
    {
        $roleuser = RoleUser::find($request->id_role_user);

        return response()->json([
            'success' => true,
            'message' => 'All role user grabbed',
            'data' => [
                'role_user' => [
                    'id_role_user' => $roleuser->id_role_user,
                    'nama_role' => $roleuser->nama_role
                ]
            ]
        ]);
    }
}