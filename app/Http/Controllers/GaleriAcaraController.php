<?php

namespace App\Http\Controllers;

use App\Models\GaleriAcara;
use App\Models\User;
use App\Models\Acara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GaleriAcaraController extends Controller
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
    public function createGaleriAcara(Request $request)
    {
        $cekacara = Acara::find($request->id_acara);

        if (!$cekacara) {
            return response()->json([
                'status' => false,
                'message' => 'acara not found',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'galeri_acara' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $galeriacara = new GaleriAcara;
        $galeriacara->id_acara = $request->id_acara;

        if ($request->hasFile('galeri_acara')) {
            $image = $request->file('galeri_acara');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $galeriacara->galeri_acara = $this->getPublicPath('images/'.$imageName);
        }

        $galeriacara->save();

        return response()->json([
            'success' => true,
            'message' => 'New galeri acara created',
            'data' => [
                'galeri_acara' => $galeriacara
            ]
        ]);
    }

    public function getAllGaleriAcara()
    {
        $galeriacara = GaleriAcara::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all galeri acara grabbed',
            'galeri acara' => $galeriacara,
        ], 200);
    }

    public function getAllGaleriAcaraForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $result = DB::table('galeri_acara')
        ->join('acara', 'galeri_acara.id_acara', '=', 'acara.id_acara')
        ->join('user', 'user.id_user', '=', 'acara.id_user')
        ->select('galeri_acara.*', 'acara.*')
        ->where('user.id_user', $id_pengelola)
        ->get();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'galeri acara not found',
            ], 200);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'all galeri acara grabbed',
            'galeri acara' => $result,
        ], 200);
    }

    public function getGaleriAcaraByIdForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;
        $id_galeri_acara = $request->id_galeri_acara;

        $result = DB::table('galeri_acara')
        ->join('acara', 'galeri_acara.id_acara', '=', 'acara.id_acara')
        ->join('user', 'user.id_user', '=', 'acara.id_user')
        ->select('galeri_acara.*', 'acara.*')
        ->where('id_galeri_acara', $id_galeri_acara)
        ->where('user.id_user', $id_pengelola)
        ->first();

        if (!$result) {
            return response()->json([
                'success' => true,
                'message' => 'galeri acara not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri acara grabbed',
            'data' => [
                'galeri acara' => [
                    'id_galeri_acara' => $result->id_galeri_acara,
                    'galeri_acara' => $result->galeri_acara,
                    'acara' => [
                        'id_acara' => $result->id_acara,
                        'nama_acara' => $result->nama_acara,
                        'deskripsi_acara' => $result->deskripsi_acara,
                        'gambar_acara' => $result->gambar_acara,
                        'alamat_acara' => $result->alamat_acara,
                        'id_user' => $result->id_user,
                    ]
                ]
            ]
        ], 200);
    }

    public function getGaleriAcaraById(Request $request)
    {
        $galeriacara = GaleriAcara::find($request->id_galeri_acara);

        if (!$galeriacara) {
            return response()->json([
                'success' => true,
                'message' => 'galeri acara not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri acara grabbed',
            'data' => [
                'galeri acara' => [
                    'id_galeri_acara' => $galeriacara->id_galeri_acara,
                    'galeri_acara' => $galeriacara->galeri_acara,
                    'acara' => $galeriacara->acaras,
                ]
            ]
        ], 200);
    }

    public function getGaleriAcaraByacara(Request $request)
    {
        $galeriacara = GaleriAcara::all()
        ->where('id_acara', $request->id_acara);

        if (!$galeriacara) {
            return response()->json([
                'success' => false,
                'message' => 'galeri acara not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri acara grabbed',
            'galeri acara' => $galeriacara,
        ],200);
    }

    public function delGaleriAcara(Request $request)
    {
        $galeriacara = GaleriAcara::find($request->id_galeri_acara);

        if (!$galeriacara) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri acara not found'
            ], 404);
        }
    
        $galeriacara->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Galeri acara was deleted'
        ], 200);
    }

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}