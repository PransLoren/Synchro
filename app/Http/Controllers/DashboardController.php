<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project; // Update this line to match the new class name
use App\Models\User;

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
            $data['getRecord'] = Project::getRecord(); // Update here as well

            return view('Admin.admindash', $data);
        } elseif (Auth::user()->user_type == 3) {
            // If the user is a student, fetch both created and member projects
            $userId = Auth::id();

            $data['userProjects'] = Project::where('created_by', $userId)
                ->orWhereHas('users', function($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
            

            return view('Student.studentdash', $data);
        }
    }
}
