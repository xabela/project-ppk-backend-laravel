<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::whereRaw('1 = 1');

        if ($request->nama) {
            $user = $user->where('nama', 'LIKE', '%' . $request->nama . '%');
        }
        $user = $user->get();

        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = new User();
        $data->username = $request->username;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->role = $request->role;
        $data->save();
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $username
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        if ($username != request()->loggedin_username && request()->loggedin_role != 1) {
            return abort(403, 'Forbidden');
        }
        $user = User::with(['pendaftaran'])->where('username', $username)->first();

        if ($user) {
            return response()->json($user);
        }
        return response()->json([], 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $username
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $username)
    {
        error_log('masuk update');
        if ($username != request()->loggedin_username) {
            return abort(403, 'Forbidden');
        }
        $user = User::where('username', $username)->first();
        if ($request->nama != null) {
            $user->nama = $request->nama;
        }
        if ($request->password != null) {
            error_log('masuk password');
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
            } else {
                return abort(403, 'Password lama salah');
            }
        }
        $user->save();

        return response()->json($user);
    }

    private function jwtGenerator($username, $role)
    {
        $payload = [
            'iss' => "project-ppk-hybrid", // Issuer of the token
            'username' => $username, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + (60 * 60 * 24 * 365), // Expiration time
            'role' => $role,
        ];
        return JWT::encode($payload, env('SECRET_TOKEN_KEY', 'project-ppk-hybrid'));
    }

    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    "token" => $this->jwtGenerator($request->username, $user->role),
                ]);
            }
            return abort(401, "Password salah");
        }
        return abort(404, "Pengguna tidak ditemukan");
    }
}
