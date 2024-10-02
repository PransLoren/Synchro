<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AdminController extends Controller
{
    public function list(){
        $data['getRecord'] = User::getAdmin();
        $data['header_title'] = "Admin List";
        return view('Admin.admin.list',$data);
    }

    public function add(){
        $data['header_title'] = "Add New Admin ";
        return view('Admin.admin.addAdmin',$data);
    }

    public function insert(Request $request){


        request()->validate([
            'email' => 'required|email|unique:users',

        ]);

        $user= new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type=1;
        $user->is_delete=0;
        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin successfully created');
    }

}
