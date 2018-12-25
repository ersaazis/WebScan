<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth', ['only' => ['logout']]);
    }
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Register Fail !',
                'data' => $validator->errors(),
            ], 400);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        

        $register = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
        if($register){
            return response()->json([
                'success' => true,
                'messages' => 'Register Success !',
                'data' => $register
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'messages' => 'Register Fail !',
                'data' => ''
            ], 400);
        }
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Login Fail !',
                'data' => $validator->errors(),
            ], 400);
        }

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if(!empty($user) and Hash::check($password, $user->password)){
            $apiToken=base64_encode(str_random(40));
            $user->update([
                'api_token' => $apiToken
            ]);

            return response()->json([
                'success' => true,
                'messages' => 'Login Success !',
                'data' => [
                    'user' => $user,
                    'api_token' => $apiToken
                ]
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'messages' => 'Login Fail !',
                'data' => ''
            ], 400);
        }
    }
    public function logout(Request $request){
        $api_token= explode(' ', $request->header('Authorization'));
        $user = User::where('api_token', $api_token[1])->first();;
        $user->update([
            'api_token' => ''
        ]);
        return response()->json([
            'success' => true,
            'messages' => 'Logout Success !',
            'data' => ''
        ], 200);
    }
}
