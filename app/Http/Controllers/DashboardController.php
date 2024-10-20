<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data['header_title'] = "Dashboard";

        if (Auth::user()->user_type == 1) {
            $data['getStudent'] = User::getStudent();
            $data['getAdmin'] = User::getAdmin();
            $data['getRecord'] = Project::getRecord();

            return view('Admin.admindash', $data);
        } elseif (Auth::user()->user_type == 3) {
            $userId = Auth::id();

            // Overdue projects (submission date is in the past and not completed)
            $data['overdueProjects'] = Project::where('submission_date', '<', Carbon::now())
                ->where('status', '!=', 'completed')
                ->where(function ($query) use ($userId) {
                    $query->where('created_by', $userId)
                        ->orWhereHas('users', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        });
                })
                ->orderBy('submission_date', 'asc')
                ->get();

            // Completed projects (status marked as completed)
            $data['completedProjects'] = Project::where('status', 'completed')
                ->where(function ($query) use ($userId) {
                    $query->where('created_by', $userId)
                        ->orWhereHas('users', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        });
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            // User projects (excluding overdue projects)
            $data['userProjects'] = Project::where('submission_date', '>=', Carbon::now()) // Exclude overdue
                ->where('status', '!=', 'completed') // Not completed
                ->where(function ($query) use ($userId) {
                    $query->where('created_by', $userId)
                        ->orWhereHas('users', function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        });
                })
                ->orderBy('id', 'desc')
                ->paginate(10);

            return view('Student.studentdash', $data);
        }
    }
}
