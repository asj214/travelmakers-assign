<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuthResource;
use App\Models\User;

class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }
    //
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        if ($validator->fails()) return $this->respondBadRequest($validator->errors());

        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->respondCreated($user);

    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        if ($validator->fails()) return $this->respondBadRequest($validator->errors());

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->respondBadRequest('email or password is invalid');
        }

        $token_name = $user->id.$user->email;

        return $this->respond([
            'access_token' => $user->createToken($token_name)->plainTextToken,
            'token_type' => 'bearer'
        ]);
    }

    public function me(){
        return $this->respond(new AuthResource(auth()->user()));
    }

    public function logout(Request $request){
        # 해당 유저의 전체 토큰 삭제
        $request->user()->tokens()->delete();
        return $this->respondNoContent(null, 204);
    }
}
