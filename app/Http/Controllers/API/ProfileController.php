<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use File;

class ProfileController extends Controller
{
    public function change_password(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'password' => 'required|min:6|same:password',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ],422);
        }

        $user=$request->user();
        if(Hash::check($request->old_password,$user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'Password successfully updated',
            ],200);
        }
        else{
            return response()->json([
                'message' => 'Old Password does not matched',
                'error' => $validator->errors()                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
            ],400);
        }
    }

    public function update_profile(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|min:2|max:100',
            'profession' => 'nullable|max:100',
            'profile_photo' => 'nullable|image|mimes:jpg,bmp,png',
            'gender' => 'nullable|max:6',
            'location'=> 'nullable|max:200',
            'bio'=>'nullable|max:500',
            'birthdate' =>'nullable|max:10'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation Fails',
                'errors' => $validator->errors()
            ],422);
        }

        $user=$request->user();

        if($request->hasFile('profile_photo')){
            if($user->profile_photo){
                $old_path=public_path().'/uploads/profile_images/'.$user->profile_photo;

                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }

            $image_name='profile-image'.time().'.'.$request->profile_photo->extension();
            $request->profile_photo->move(public_path('/uploads/profile_images'),$image_name);
        }
        else{
            $image_name=$user->profile_photo;
        }

        $user->update([
            'name'=>$request->name ?? $user->name,
            'profession'=>$request->profession ?? $user->profession,
            'gender' => $request->gender ?? $user->gender,
            'location'=> $request->location ?? $user->location,
            'bio'=>$request->bio ?? $user->bio,
            'birthdate' =>$request->birthdate ?? $user->birthdate,
            'profile_photo'=>$image_name
        ]);

        return response()->json([
            'message'=>'Profile Successfully Updated',
        ],200);
    }
}