<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;

class UserController extends Controller
{
    //list user
    public function index(Request $request, $p=10)
    {
        if(!is_numeric($p))
            $p=10;

        $users=User::paginate($p);
        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => $users,
        ], 200);
    }
    //get user
    public function show($id)
    {
        $user=User::find($id);
        if(!empty($user))
            return response()->json([
                'success' => true,
                'messages' => 'Success !',
                'data' => $user,
            ], 200);
        else
            return response()->json([
                'success' => false,
                'messages' => 'User Not Found !',
                'data' => '',
            ], 404);
    }

    //insert user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'Add User Fail !',
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
                'messages' => 'Add User Success !',
                'data' => $register
            ], 201);
        }
        else {
            return response()->json([
                'success' => false,
                'messages' => 'Add User Fail !',
                'data' => ''
            ], 400);
        }
    }
    //update user
    public function update(Request $request, $id)
    {
        $user=User::find($id);
        if(!empty($user)){
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'email|max:255|unique:users',
                'password' => 'min:8',
            ]);
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'messages' => 'Update User Fail !',
                    'data' => $validator->errors(),
                ], 400);
            }

            $name = $request->input('name');
            $email = $request->input('email');
            $password = Hash::make($request->input('password'));

            $user->update([
                'name' => $name
            ]);
            if(!empty($email))
                $user->update([
                    'email' => $email
                ]);
            if(!empty($request->input('password')))
                $user->update([
                    'password' => $password
                ]);

            return response()->json([
                'success' => true,
                'messages' => 'Update User Success !',
                'data' => ''
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'User not found !',
                'data' => ''
            ], 404);
    }
    //delete user
    public function destroy($id)
    {
        $user=User::find($id);
        if(!empty($user)){
            $user->delete();
            return response()->json([
                'success' => true,
                'messages' => 'Delete User Success !',
                'data' => ''
            ], 201);
        }
        else
            return response()->json([
                'success' => false,
                'messages' => 'User Not Found !',
                'data' => ''
            ], 404);
    }
    public function filter(Request $request, $p=10){
        if(!is_numeric($p))
            $p=10;

        $validator = Validator::make($request->all(), [
            'search' => 'required|min:3',
        ]);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'messages' => 'filter News Fail !',
                'data' => $validator->errors(),
            ], 400);
        }
        $search = $request->input('search');

        $users=User::where('name','like','%'.$search.'%')
                    ->orWhere('email','like','%'.$search.'%')
                    ->paginate($p);
        return response()->json([
            'success' => true,
            'messages' => 'Success !',
            'data' => $users,
        ], 200);
    }
}
