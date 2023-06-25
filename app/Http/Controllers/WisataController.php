<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\User;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class WisataController extends Controller
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
    public function createWisata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar_wisata' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
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

        $wisata = new Wisata;
        $wisata->nama_wisata = $request->nama_wisata;
        $wisata->deskripsi_wisata = $request->deskripsi_wisata;
        $wisata->alamat_wisata = $request->alamat_wisata;
        $wisata->jam_buka_wisata = $request->jam_buka_wisata;
        $wisata->harga_tiket_wisata = $request->harga_tiket_wisata;
        $wisata->id_kategori_wisata = $request->id_kategori_wisata;
        $wisata->id_user = $id_user;

        if ($request->hasFile('gambar_wisata')) {
            $image = $request->file('gambar_wisata');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $wisata->gambar_wisata = $this->getPublicPath('images/'.$imageName);
        }

        $wisata->save();

        return response()->json([
            'success' => true,
            'message' => 'New wisata created',
            'data' => [
                'wisata' => $wisata
            ]
        ]);
    }

    public function getAllWisataForWisatawan()
    {
        $wisata = Wisata::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all wisata grabbed',
            'wisata' => $wisata,
        ], 200);
    }


    public function getWisataByIdForWisatawan(Request $request)
    {
        $wisata = Wisata::where('id_wisata', $request->id_wisata)->first();

        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All wisata grabbed',
            'data' => [
                'wisata' => [
                    'id_wisata' => $wisata->id_wisata,
                    'nama_wisata' => $wisata->nama_wisata,
                    'deskripsi_wisata' => $wisata->deskripsi_wisata,
                    'gambar_wisata' => $wisata->gambar_wisata,
                    'alamat_wisata' => $wisata->alamat_wisata,
                    'jam_buka_wisata' => $wisata->jam_buka_wisata,
                    'harga_tiket_wisata' => $wisata->harga_tiket_wisata,
                    'galeri_wisata' => $wisata->galeriwisatas,
                    'id_kategori_wisata' => $wisata->id_kategori_wisata,
                    'id_user' => $wisata->id_user,
                    'users' => $wisata->users,
                    'kategoriwisatas' => $wisata->kategoriwisatas,
                ]
            ]
        ]);
    }

    public function getAllWisataForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $wisata = Wisata::all()->where('id_user', $id_user);

        return response()->json([
            'status' => 'Success',
            'message' => 'all wisata grabbed',
            'wisata' => $wisata,
        ], 200);
    }

    public function getAllWisataByIdPengelola(Request $request)
    {
        $wisata = Wisata::all()->where('id_user', $request->id_user);

        return response()->json([
            'status' => 'Success',
            'message' => 'all wisata grabbed',
            'wisata' => $wisata,
        ], 200);
    }

    

    public function getWisataByIdForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $wisata = Wisata::find($request->id_wisata);

        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        if ($wisata->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All wisata grabbed',
            'data' => [
                'wisata' => [
                    'id_wisata' => $wisata->id_wisata,
                    'nama_wisata' => $wisata->nama_wisata,
                    'deskripsi_wisata' => $wisata->deskripsi_wisata,
                    'gambar_wisata' => $wisata->gambar_wisata,
                    'alamat_wisata' => $wisata->alamat_wisata,
                    'jam_buka_wisata' => $wisata->jam_buka_wisata,
                    'harga_tiket_wisata' => $wisata->harga_tiket_wisata,
                    'id_kategori_wisata' => $wisata->id_kategori_wisata,
                    'id_user' => $wisata->id_user,
                    'users' => $wisata->users,
                    'kategoriwisatas' => $wisata->kategoriwisatas
                ]
            ]
        ]);
    }

    public function getAllWisataByKategoriWisataForPengelola(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $wisata = Wisata::all()
        ->where('id_kategori_wisata', $request->id_kategori_wisata);

        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All wisata grabbed',
            'wisata' => $wisata
        ]);
    }

    public function getAllWisataByKategoriWisataForWisatawan(Request $request)
    {
        $wisata = Wisata::all()
        ->where('id_kategori_wisata', $request->id_kategori_wisata);

        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'All wisata grabbed',
            'wisata' => $wisata
        ]);
    }

    public function updateWisata(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $wisata = Wisata::find($request->id_wisata);

        
        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        if ($wisata->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'gambar_wisata' => 'image|mimes:svg,jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $wisata->nama_wisata = $request->nama_wisata;
        $wisata->deskripsi_wisata = $request->deskripsi_wisata;
        $wisata->id_kategori_wisata = $request->id_kategori_wisata;
        $wisata->id_user = $id_user;

        if ($request->hasFile('gambar_wisata')) {
            $image = $request->file('gambar_wisata');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move($this->getPublicPath('images'), $imageName);
            $wisata->gambar_wisata = $this->getPublicPath('images/'.$imageName);
        }

        $wisata->save();

        return response()->json([
            'success' => true,
            'message' => 'Wisata updated',
            'data' => [
                'wisata' => $wisata
            ]
        ]);
    }

    public function delWisata(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $wisata = Wisata::find($request->id_wisata);

        
        if (!$wisata) {
            return response()->json([
                'success' => false,
                'message' => 'Wisata not found'
            ], 404);
        }

        if ($wisata->id_user != $id_user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }
    
        $wisata->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Wisata was deleted'
        ], 200);
    }

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
    
}