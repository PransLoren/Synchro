<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Hash;

class UserController extends Controller
{
    public function register (Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|same:password'
        ]);;

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'message' => 'Registration Successfully',
            'data' => $user
        ],200);
    }

    public function login(Request $request){
        $validator = Validator::make($request -> all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::where('email',$request->email)->first();

        if ($user){
            if(Hash::check($request->password,$user->password)){
                $token=$user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login Successful',
                    'token' => $token,
                    'data' => $user
                ],200);
            }
            else{
                return response()->json([
                    'message' => 'Incorrect Credentials',
                ],400);
            }
        }
        else{
            return response()->json([
                'message' => 'Incorrect Credentials'
            ],400);
        }
    }

    public function user (Request $request){
        return response()->json([
            'message' => 'User Successfully Fetched',
            'data' => $request->user()
        ],200);
    }

    public function logout(Request $request){
        $request->user()->currentAccesToken()->delete();
        return response()->json([
            'message' => 'User Successfully Logged Out',
            'data' =>$request->user()
        ],200);
    }
}