<?php

namespace App\Http\Controllers;

use App\Models\KategoriWisata;
use Illuminate\Http\Request;

class KategoriWisataController extends Controller
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
    public function createKategoriWisata(Request $request)
    {
        $kategoriwisata = KategoriWisata::create([
            'nama_kategori_wisata' => $request->nama_kategori_wisata,
            'deskripsi_kategori_wisata' => $request->deskripsi_kategori_wisata,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New kategori wisata created',
            'data' => [
                'kategoriwisata' => $kategoriwisata
            ]
        ]);
    }

    public function getKategoriWisataById(Request $request)
    {
        $kategoriwisata = KategoriWisata::find($request->id_kategori_wisata);

        return response()->json([
            'success' => true,
            'message' => 'All post grabbed',
            'data' => [
                'kategori_wisata' => [
                    'id_kategori_wisata' => $kategoriwisata->id_kategori_wisata,
                    'nama_kategori_wisata' => $kategoriwisata->nama_kategori_wisata,
                    'deskripsi_kategori_wisata' => $kategoriwisata->deskripsi_kategori_wisata
                ]
            ]
        ]);
    }

    public function getAllKategoriWisata()
    {
        $kategoriwisata = KategoriWisata::all();

        return response()->json([
            'status' => 'Success',
            'message' => 'all kategori wisata grabbed',
            'kategoriwisata' => $kategoriwisata
        ], 200);
    }

    public function updateKategoriWisata(Request $request){
        $kategoriWisata = KategoriWisata::find($request->id_kategori_wisata);
        if (!$kategoriWisata) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Wisata not found'
            ], 404);
        }
        $kategoriWisata->id_kategori_wisata = $kategoriWisata->id_kategori_wisata;
        $kategoriWisata->nama_kategori_wisata = $request->nama_kategori_wisata;
        $kategoriWisata->deskripsi_kategori_wisata = $request->deskripsi_kategori_wisata;
        $kategoriWisata->save();
        return response()->json([
            'success' => true,
            'message' => 'Kategori Wisata updated'
        ], 200);
    }

    public function delKategoriWisata(Request $request)
    {
        $kategoriwisata = KategoriWisata::find($request->id_kategori_wisata);
        if (!$kategoriwisata) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori Wisata not found'
            ], 404);
        }
    
        $kategoriwisata->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Kategori Wisata deleted'
        ], 200);
    }

}