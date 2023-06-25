<?php

namespace App\Http\Controllers;

use App\Models\GaleriWisata;
use App\Models\User;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GaleriWisataController extends Controller
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
    public function createGaleriWisata(Request $request)
    {
        $cekwisata = Wisata::find($request->id_wisata);

        if (!$cekwisata) {
            return response()->json([
                'status' => false,
                'message' => 'wisata not found',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'galeri_wisata' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $galeriwisata = new GaleriWisata;
        $galeriwisata->id_wisata = $request->id_wisata;

        if ($request->hasFile('galeri_wisata')) {
            $image = $request->file('galeri_wisata');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $galeriwisata->galeri_wisata = $this->getPublicPath('images/'.$imageName);
        }

        $galeriwisata->save();

        return response()->json([
            'success' => true,
            'message' => 'New galeri wisata created',
            'data' => [
                'galeri_wisata' => $galeriwisata
            ]
        ]);
    }

    public function getAllGaleriWisata()
    {
        $galeriwisata = GaleriWisata::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all galeri wisata grabbed',
            'galeri wisata' => $galeriwisata,
        ], 200);
    }

    public function getAllGaleriWisataForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $result = DB::table('galeri_wisata')
        ->join('wisata', 'galeri_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->join('user', 'user.id_user', '=', 'wisata.id_user')
        ->select('galeri_wisata.*', 'wisata.*')
        ->where('user.id_user', $id_pengelola)
        ->get();

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'galeri wisata not found',
            ], 200);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'all galeri wisata grabbed',
            'galeri wisata' => $result,
        ], 200);
    }

    public function getGaleriWisataByIdForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $id_galeri_wisata = $request->id_galeri_wisata;

        $result = DB::table('galeri_wisata')
        ->join('wisata', 'galeri_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->join('user', 'user.id_user', '=', 'wisata.id_user')
        ->select('galeri_wisata.*', 'wisata.*')
        ->where('galeri_wisata.id_galeri_wisata', $id_galeri_wisata)
        ->where('user.id_user', $id_pengelola)
        ->first();

        if (!$result) {
            return response()->json([
                'success' => true,
                'message' => 'galeri wisata not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri wisata grabbed',
            'data' => [
                'galeri wisata' => [
                    'id_galeri_wisata' => $result->id_galeri_wisata,
                    'galeri_wisata' => $result->galeri_wisata,
                    'wisata' => [
                        'id_wisata' => $result->id_wisata,
                        'nama_wisata' => $result->nama_wisata,
                        'deskripsi_wisata' => $result->deskripsi_wisata,
                        'gambar_wisata' => $result->gambar_wisata,
                        'alamat_wisata' => $result->alamat_wisata,
                        'jam_buka_wisata' => $result->jam_buka_wisata,
                        'harga_tiket_wisata' => $result->harga_tiket_wisata,
                        'id_kategori_wisata' => $result->id_kategori_wisata,
                        'id_user' => $result->id_user,
                    ]
                ]
            ]
        ], 200);
    }

    public function getGaleriWisataById(Request $request)
    {
        $galeriwisata = GaleriWisata::find($request->id_galeri_wisata);

        if (!$galeriwisata) {
            return response()->json([
                'success' => true,
                'message' => 'galeri wisata not found',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri wisata grabbed',
            'data' => [
                'galeri wisata' => [
                    'id_galeri_wisata' => $galeriwisata->id_galeri_wisata,
                    'galeri_wisata' => $galeriwisata->galeri_wisata,
                    'wisata' => $galeriwisata->wisatas,
                ]
            ]
        ], 200);
    }

    public function getGaleriWisataByWisata(Request $request)
    {
        $galeriwisata = GaleriWisata::all()
        ->where('id_wisata', $request->id_wisata);

        if (!$galeriwisata) {
            return response()->json([
                'success' => false,
                'message' => 'galeri wisata not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'All galeri wisata grabbed',
            'galeri wisata' => $galeriwisata,
        ],200);
    }

    public function delGaleriWisata(Request $request)
    {
        $galeriwisata = GaleriWisata::find($request->id_galeri_wisata);

        if (!$galeriwisata) {
            return response()->json([
                'success' => false,
                'message' => 'Galeri Wisata not found'
            ]);
        }
    
        $galeriwisata->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Galeri Wisata was deleted'
        ], 200);
    }

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}