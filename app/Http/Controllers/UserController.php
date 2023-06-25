<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_profil' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = new User;
        $user->nama_user = $request->input('nama_user');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->no_telepon= $request->input('no_telepon');
        $user->id_role_user= $request->input('id_role_user');

        if ($request->hasFile('foto_profil')) {
            $image = $request->file('foto_profil');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $user->foto_profil = $this->getPublicPath('images/'.$imageName);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'New user created',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function getAllUser()
    {
        $user = User::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all users grabbed',
            'user' => $user,
        ], 200);
    }


    public function delUser(Request $request)
    {
        $user = User::find($request->id_user);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    
        $user->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'User deleted'
        ], 200);
    }
    

    public function updateUser(Request $request)
    {
        $user = User::where($request->id_user)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'foto_profil' => 'nullable|image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user->nama_user = $request->input('nama_user');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->no_telepon= $request->input('no_telepon');
        $user->id_role_user= $request->input('id_role_user');

        if ($request->hasFile('foto_profil')) {
            $image = $request->file('foto_profil');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $user->foto_profil = $this->getPublicPath('images/'.$imageName);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated',
            'data' => [
                'user' => $user
            ]
        ]);
    }


    public function addTransaksi(Request $request)
    {
        $user = User::find($request->id_user);
        $user->users()->attach($request->id_transaksi);
        return response()->json([
            'success' => true,
            'message' => 'user berhasil menambahkan transaksi',
        ]);
    }
    
    public function getUserById(Request $request)
    {
        $user = User::where($request->id_user)->first();

        return response()->json([
            'success' => true,
            'message' => 'All user grabbed',
            'data' => [
                'kategori_user' => [
                    'id_user' => $user->id_user,
                    'nama_user' => $user->nama_user,
                    'email' => $user->email,
                    'password' => $user->password,
                    'no_telepon' => $user->no_telepon,
                    'foto_profil' => $user->foto_profil,
                    'id_role_user' => $user->id_role_user,
                    'roleusers' => $user->roleusers
                ]
            ]
        ]);
    }

    public function getAllWisatawanByPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $result = DB::table('user')
        ->join('transaksi', 'user.id_user', '=', 'transaksi.id_user')
        ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
        ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->select('user.*')
        ->where('wisata.id_user', $id_pengelola)
        ->where('user.id_role_user', '3')
        ->get();

        if(!$result){
            return response()->json([
                'status' => false,
                'message' => 'wisatawan not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function getAllPengelola()
    {
        $users = User::where('id_role_user', '2')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function getPengelolaById(Request $request)
    {
        $users = User::where('id_role_user', '2')
        ->where('id_user', $request->id_user)
        ->first();

        if (!$users) {
            return response()->json([
                'status' => false,
                'message' => 'pengelola not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function getWisatawanByIdByPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $id_wisatawan = $request->id_user;

        $result = DB::table('user')
        ->join('transaksi', 'user.id_user', '=', 'transaksi.id_user')
        ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
        ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->select('user.*')
        ->where('user.id_user', $id_wisatawan)
        ->where('wisata.id_user', $id_pengelola)
        ->where('user.id_role_user', '3')
        ->first();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'wisatawan not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function getWisatawanById(Request $request)
    {
        $users = User::where('id_role_user', '3')
        ->where('id_user', $request->id_user)
        ->first();

        if (!$users) {
            return response()->json([
                'status' => false,
                'message' => 'wisatawan not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function getAllWisatawan()
    {
        $users = User::where('id_role_user', '3')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function delPengelola(Request $request)
    {
        $user = User::find($request->id_user);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'pengelola not found'
            ], 404);
        }

        if ($user->id_role_user!='2') {
            return response()->json([
                'success' => false,
                'message' => 'pengelola not found'
            ], 404);
        }
    
        $user->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'User deleted'
        ], 200);
    }

    public function updateWisatawan(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'foto_profil' => 'nullable|image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user->nama_user = $request->nama_user;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->no_telepon= $request->no_telepon;
        $user->id_role_user= $user->id_role_user;

        if ($request->hasFile('foto_profil')) {
            $image = $request->file('foto_profil');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $user->foto_profil = $this->getPublicPath('images/'.$imageName);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated',
            'data' => [
                'user' => $user
            ]
        ]);
    }


    public function getWisatasByUser(Request $request)
    {
        $user = User::find($request->id_user);
        $wisatas = $user->wisatas()->get();
        return response()->json($wisatas);
    }

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}