<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) //
  {
    //

    $this->request = $request;
  }

  //
  protected function jwt(User $user)
  {
    $payload = [
      'iss' => 'lumen-jwt', //issuer of the token
      'sub' => $user->email, //subject of the token
      'iat' => time(), //time when JWT was issued.
      'exp' => time() + 60 * 60 //time when JWT will expire
    ];

    return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
  }

  public function login(Request $request)
  {
    $email = $request->email;
    $password = $request->password;

    $user = User::where('email', $email)->first();

    if (!$user) {
      return response()->json([
        'status' => 'Error',
        'message' => 'user not exist',
      ], 404);
    }

    if (!Hash::check($password, $user->password)) {
      return response()->json([
        'status' => 'Error',
        'message' => 'wrong password',
      ], 400);
    }

    $user->token = $this->jwt($user);

    $user->save();

    return response()->json([
      'status' => 'Success',
      'message' => 'successfully login',
      'token' => $user->token
    ], 200);
  }
    //
    public function register(Request $request)
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
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->no_telepon= $request->no_telepon;
        $user->id_role_user= $request->id_role_user;

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

    public function registerPengelola(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_profil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation fail',
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = new User;
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->no_telepon= $request->no_telepon;
        $user->id_role_user= '2';

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

    private function getPublicPath($path = '')
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

   
   
}