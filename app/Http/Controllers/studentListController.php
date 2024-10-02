<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\SubjectModel;
use Hash;
use Auth;
use Str;
use Illuminate\Http\Request;


class studentListController extends Controller
{
    public function studentList()
    {
        $data['getStudent'] = User::getStudent();
        $data['header_title'] = "Student List";
        return view('Admin.admin.studentlist.studentlist',$data);
    }

    public function add()
    {
        $data['header_title'] = "Add New Student";
        return view('Admin.admin.studentlist.addStudent',$data);
    }

    public function edit($id)
    {
        $getStudent = User::find($id);

        if ($getStudent) {
            $data['getStudent'] = $getStudent;
            $data['header_title'] = 'Edit Student';
            return view('Admin.admin.studentlist.editStudent', $data);
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:5|max:20|confirmed',
        ]);

        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        return redirect()->route('student.list')->with('success', 'Student information updated successfully!');
    }

    public function delete($id)
    {
        $getStudent = User::find($id);

        if ($getStudent) {
            $getStudent->delete();

            return redirect()->back()->with('success','Student successfully deleted');
        } else {
            abort(404);
        }
    }
}
