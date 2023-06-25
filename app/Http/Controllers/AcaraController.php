<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AcaraController extends Controller
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
    public function createAcara(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar_acara' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $acara = new Acara;
        $acara->nama_acara = $request->nama_acara;
        $acara->alamat_acara = $request->alamat_acara;
        $acara->deskripsi_acara = $request->deskripsi_acara;
        $acara->id_user = $id_user;

        if ($request->hasFile('gambar_acara')) {
            $image = $request->file('gambar_acara');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $acara->gambar_acara = $this->getPublicPath('images/'.$imageName);
        }

        $acara->save();

        return response()->json([
            'success' => true,
            'message' => 'New acara created',
            'data' => [
                'acara' => $acara
            ]
        ]);
    }

    public function getAllAcaraForWisatawan()
    {
        $acara = Acara::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all acara grabbed',
            'data' => $acara,
        ], 200);
    }

    public function getAcaraByIdForWisatawan(Request $request)
    {
        $acara = Acara::where('id_acara', $request->id_acara)->first();

        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'acara not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All acara grabbed',
            'data' => [
                'acara' => [
                    'id_acara' => $acara->id_acara,
                    'nama_acara' => $acara->nama_acara,
                    'deskripsi_acara' => $acara->deskripsi_acara,
                    'alamat_acara' => $acara->alamat_acara,
                    'gambar_acara' => $acara->gambar_acara,
                    'galeri_acara' => $acara->galeriacaras,
                    'id_user' => $acara->id_user,
                    'users' => $acara->users
                ]
            ]
        ]);
    }

    public function getAllAcaraByIdPengelola(Request $request)
    {
        $acara = Acara::all()->where('id_user', $request->id_user);

        return response()->json([
            'status' => 'Success',
            'message' => 'all acara grabbed',
            'acara' => $acara,
        ], 200);
    }
    

    public function getAllAcaraForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $acara = Acara::all()->where('id_user', $id_user);

        return response()->json([
            'status' => 'Success',
            'message' => 'all acara grabbed',
            'data' => [
                'acara' => $acara
            ]
        ], 200);
    }

    
    public function getAcaraByIdForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $acara = Acara::find($request->id_acara);

        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'acara not found'
            ], 404);
        }

        if ($acara->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All acara grabbed',
            'data' => [
                'acara' => [
                    'id_acara' => $acara->id_acara,
                    'nama_acara' => $acara->nama_acara,
                    'deskripsi_acara' => $acara->deskripsi_acara,
                    'alamat_acara' => $acara->alamat_acara,
                    'gambar_acara' => $acara->gambar_acara,
                    'galeri_acara' => $acara->galeriacaras,
                    'id_user' => $acara->id_user,
                    'users' => $acara->users
                ]
            ]
        ]);
    }

    public function updateAcara(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $acara = Acara::find($request->id_acara);

        
        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'acara not found'
            ], 404);
        }

        if ($acara->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'gambar_acara' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $acara->nama_acara = $request->nama_acara;
        $acara->deskripsi_acara = $request->deskripsi_acara;
        $acara->alamat_acara = $request->alamat_acara;
        $acara->id_user = $id_user;

        if ($request->hasFile('gambar_acara')) {
            $image = $request->file('gambar_acara');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $acara->gambar_acara = $this->getPublicPath('images/'.$imageName);
        }

        $acara->save();

        return response()->json([
            'success' => true,
            'message' => 'acara updated',
            'data' => [
                'acara' => $acara
            ]
        ]);
    }

    public function delAcara(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $acara = acara::find($request->id_acara);

        
        if (!$acara) {
            return response()->json([
                'success' => false,
                'message' => 'acara not found'
            ], 404);
        }

        if ($acara->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }
    
        $acara->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'acara was deleted'
        ], 200);
    }

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
    
}