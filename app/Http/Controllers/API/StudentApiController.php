<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Auth;

class StudentApiController extends Controller
{
    public function list()
    {
        $students = User::getStudent();
        return response()->json(['data' => $students]);
    }

    public function edit($id)
    {
        $student = User::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json(['data' => $student]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $student = User::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $student->name = trim($request->name);
        $student->last_name = trim($request->last_name);
        $student->gender = trim($request->gender);
        $student->subject_id = trim($request->subject_id);
        $student->date_of_birth = $request->date_of_birth;
        $student->status = trim($request->status);
        $student->email = trim($request->email);
        if (!empty($request->password)) {
            $student->password = Hash::make($request->password);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_pic')) {
            $profilePic = $request->file('profile_pic');
            $profilePicName = time() . '_' . Str::random(10) . '.' . $profilePic->getClientOriginalExtension();
            $profilePic->move(public_path('uploads/profile'), $profilePicName);
            $student->profile_pic = $profilePicName;
        }

        $student->save();

        return response()->json(['message' => 'Student successfully updated']);
    }

    public function delete($id)
    {
        $student = User::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $student->delete();

        return response()->json(['message' => 'Student successfully deleted']);
    }

    public function myStudent()
    {
        $user = Auth::user();

        if ($user->user_type == 2) {
            $getRecord = User::getTeacherStudent($user->id);
            return response()->json(['getRecord' => $getRecord, 'header_title' => 'My Student List']);
        } elseif ($user->user_type == 3) {
            return response()->json(['student' => $user]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}
