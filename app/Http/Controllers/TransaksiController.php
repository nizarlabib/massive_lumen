<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Wisata;
use App\Models\TransaksiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
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
   
    public function createTransaksi(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;
        $id_role_user = $user->id_role_user;

        if ($id_role_user != '3') {
            return response()->json([
                'success' => false,
                'message' => 'Login sebagai wisatawan jika ingin melakukan transaksi'
            ], 404);
        }

        $transaksi = Transaksi::create([
            'total_transaksi' => $request->total_transaksi,
            'status_transaksi' => $request->status_transaksi,
            'id_user' => $id_user
        ]);

        $id_transaksi = $transaksi->id_transaksi;
        $id_wisata = $request->id_wisata;

        $this->addTransaksiWisata($id_transaksi,$id_wisata);

        return response()->json([
            'success' => true,
            'message' => 'New transaksi created',
            'data' => [
                'transaksi' => $transaksi
            ]
        ]);
    }

    public function updateTransaksi(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $cek = Transaksi::find($request->id_transaksi);
        
        if (!$cek) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        $result = DB::table('transaksi')
            ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
            ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
            ->select('transaksi.*', DB::raw('wisata.id_user AS id_pengelola'))
            ->where('wisata.id_user', $id_pengelola)
            ->where('transaksi.id_transaksi', $request->id_transaksi)
            ->first();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if ($result->id_pengelola != $id_pengelola) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }

        $transaksi = Transaksi::find($request->id_transaksi);

        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->total_transaksi = $result->total_transaksi;
        $transaksi->id_user = $result->id_user;

        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi updated',
            'data' => [
                'transaksi' => $transaksi
            ]
        ]);
    }

    public function addTransaksiWisata($id_transaksi, $id_wisata)
    {
        $transaksiWisata = TransaksiWisata::create([
            'id_transaksi' => $id_transaksi,
            'id_wisata' => $id_wisata

        ]);

        return response()->json([
            'success' => true,
            'message' => 'wisata added to transaksi',
            'data' => [
                'transaksiWisata' => $transaksiWisata
            ]
        ]);
    }
    

    public function getTransaksiById(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;
        
        $result = DB::table('transaksi')
        ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
        ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->select('transaksi.*', DB::raw('wisata.id_user AS id_pengelola'))
        ->where('wisata.id_user', $id_pengelola)
        ->where('transaksi.id_transaksi', $request->id_transaksi)
        ->first();
        
        $cek = Transaksi::where('id_transaksi', $request->id_transaksi)->first();
        
        if (!$cek) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if ($result->id_pengelola != $id_pengelola) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi by id grabbed',
            'transaksi' => $result
        ]);
    }
    public function getTransaksiByIdForWisatawan(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $transaksi = Transaksi::where('id_user', $id_user)
        ->where('id_transaksi', $request->id_transaksi)
        ->first();
        
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi by id grabbed',
            'transaksi' => $transaksi
        ]);
    }

    public function getAllTransaksi(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;

        $result = DB::table('transaksi')
            ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
            ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
            ->select('transaksi.*')
            ->where('wisata.id_user', $id_pengelola)
            ->get();

        return response()->json([
            'status' => 'Success',
            'message' => 'all transaksi grabbed',
            'transaksi' => $result,
        ], 200);

    }

    public function getAllTransaksiForWisatawan(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_user = $user->id_user;

        $transaksi = DB::table('transaksi')
        ->where('id_user', $id_user)
        ->get();
        
        if(!$transaksi){
            return response()->json([
                'status' => false,
                'message' => 'tidak ada transaksi'
            ], 400);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'all transaksi grabbed',
            'transaksi' => $transaksi,
        ], 200);

    }

    public function delTransaksiWisata($id_transaksi, $id_wisata){
        $transaksiWisata = TransaksiWisata::where('id_transaksi', $id_transaksi)
        ->where('id_wisata', $id_wisata)
        ->first();
        $transaksiWisata->delete();
    }

    public function delTransaksi(Request $request)
    {
        $token = $request->header('token') ?? $request->query('token');
        $user = User::where('token', $token)->first();
        $id_pengelola = $user->id_user;
        
        $result = DB::table('transaksi')
        ->join('transaksi_wisata', 'transaksi.id_transaksi', '=', 'transaksi_wisata.id_transaksi')
        ->join('wisata', 'transaksi_wisata.id_wisata', '=', 'wisata.id_wisata')
        ->select('transaksi.*', DB::raw('wisata.id_user AS id_pengelola'), 'wisata.id_wisata')
        ->where('wisata.id_user', $id_pengelola)
        ->where('transaksi.id_transaksi', $request->id_transaksi)
        ->first();

        $transaksi = Transaksi::find($request->id_transaksi);
        
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi not found'
            ], 404);
        }

        if ($result->id_pengelola != $id_pengelola) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan pengelolanya'
            ], 404);
        }
    
        $this->delTransaksiWisata($result->id_transaksi, $result->id_wisata);

        $transaksi->delete();

    
        return response()->json([
            'success' => true,
            'message' => 'Transaksi deleted'
        ], 200);
    }
}