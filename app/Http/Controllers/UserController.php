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
    public function index()
    {
        //
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
        if ($username != request()->loggedin_username) {
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
        if ($username != request()->loggedin_username) {
            return abort(403, 'Forbidden');
        }
        $user = User::where('username', $username)->first();
        if ($request->name != null) {
            $user->name = $request->name;
        }
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
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
            return abort(401, "Wrong password");
        }
        return abort(404, "User not found");
    }
}
