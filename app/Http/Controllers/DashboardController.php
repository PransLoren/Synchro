<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\ProjectModel;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = "Dashboard";

        // Check the user type
        if (Auth::user()->user_type == 1) {
            // If the user is an admin
            $data['getStudent'] = User::getStudent();
            $data['getAdmin'] = User::getAdmin();
            $data['getRecord'] = ProjectModel::getRecord();
            return view('Admin.admindash', $data);
        } elseif (Auth::user()->user_type == 3) {
            // If the user is a student, fetch both created and member projects
            $userId = Auth::id();
            $data['userProjects'] = ProjectModel::where('created_by', $userId)
                ->orWhereHas('users', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
            return view('Student.studentdash', $data);
        }
    }

}
