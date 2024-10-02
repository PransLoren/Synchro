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
            // If the user is a student
            $data['getStudent'] = User::getStudent();
            $data['getAdmin'] = User::getAdmin();
            $data['getRecord'] = ProjectModel::getRecord();
            $data['header_title'] = "Dashboard";
            return view('Admin.admindash', $data);
        } elseif (Auth::user()->user_type == 3) {
            // If the user is a student
            // Fetch projects associated with the logged-in user
            $userId = Auth::id();
            $data['userProjects'] = ProjectModel::where('created_by', $userId)
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
            $data['header_title'] = 'Project';
            return view('Student.studentdash', $data);
        }
    }
}
