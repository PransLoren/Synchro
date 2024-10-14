<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Project;
use App\Models\User;
use App\Models\Task; 
use Auth;
use Str;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function adminProjectList() {
        $data['getRecord'] = Project::getRecord();
        $data['header_title'] = 'Project';
        return view('Admin.admin.homework.listAdmin', $data);
    }

    public function project() {
        $data['getRecord'] = Project::paginate(10);
        $data['header_title'] = 'Project';
        return view('Admin.admin.homework.list1', $data);
    }
    
    public static function add() {
        $data['header_title'] = 'Add New Project';
        return view('Admin.admin.homework.add', $data);
    }

    public function insert(Request $request) {
        $validatedData = $request->validate([
            'class_name' => 'required|string|max:255',
            'submission_date' => 'required|date',
            'submission_time' => 'required|date_format:H:i',
            'description' => 'required|string',
        ]);
    
        $project = new Project;
        $project->class_name = $request->class_name;
        $project->submission_time = $request->submission_time;
        $project->submission_date = $request->submission_date;
        $descriptionWithoutNbsp = str_replace('&nbsp;', '', $request->description);
        $project->description = strip_tags($descriptionWithoutNbsp);
        $project->created_by = Auth::user()->id;
    
        try {
            $project->save();
            $project->users()->attach(Auth::user()->id, ['role' => 'creator']); // Attach the creator with role
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add project: ' . $e->getMessage());
        }
    
        return redirect('student/dashboard')->with('success','Project successfully added');
    }
    

    public function edit($id) {
        $project = Project::findOrFail($id);
        $user = Auth::user();
        $projectUser = $project->users()->where('user_id', $user->id)->first();
    
        if ($projectUser && $projectUser->pivot->role !== 'creator') {
            return redirect()->back()->with('error', 'You do not have permission to edit this project.');
        }
    
        $data['getRecord'] = $project;
        $data['header_title'] = 'Edit Project';
        return view('Admin.admin.homework.edit', $data);
    }
    

    public function update(Request $request, $id) {
        $project = Project::getSingle($id);
        $project->class_name = trim($request->class_name);
        $project->submission_time = trim($request->submission_time);
        $project->submission_date = trim($request->submission_date);
        $project->description = trim($request->description);

        try {
            $project->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update project: ' . $e->getMessage());
        }

        return redirect('student/dashboard')->with('success','Project successfully updated');
    }

    public function delete($id) {
        $project = Project::getSingle($id);
        $project->is_delete = 1;

        try {
            $project->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete project: ' . $e->getMessage());
        }

        return redirect()->back()->with('success','Project successfully deleted');
    }

    public function submit($id) {
        $project = Project::getSingle($id);
        $user = Auth::user();

        $projectUser = $project->users()->where('user_id', $user->id)->first();

        if ($projectUser && $projectUser->pivot->role !== 'creator') {
            return redirect()->back()->with('error', 'You do not have permission to submit this project.');
        }
        try {
            $project->delete(); 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit the project: ' . $e->getMessage());
        }
        return redirect('student/dashboard')->with('success', 'Project successfully submitted');
    }
    

    public function invite(Request $request, $projectId) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }
    
        $invitedUser = User::where('email', $request->email)->firstOrFail();
        $project = Project::findOrFail($projectId);
    
        try {
            $project->users()->attach($invitedUser->id, ['role' => 'member']); // Attach invited user with member role
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send invitation: ' . $e->getMessage()]);
        }
    
        return response()->json(['success' => 'Invitation sent successfully.']);
    }
    public function viewTasks($projectId) {
    // Fetch the project along with all tasks
    $project = Project::with('tasks')->findOrFail($projectId);
    $tasks = $project->tasks;

    // Pass both project and tasks to the view
    return view('Student.viewTask', compact('project', 'tasks'));
    }


    public function startTask($taskId) {
        $task = Task::findOrFail($taskId);
        $user = Auth::user();
        if ($task->assigned_to != $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to start this task.');
        }

        try {
            $task->status = 'inprogress';
            $task->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to start the task: ' . $e->getMessage());
        }
    
        return redirect()->back()->with('success', 'Task has been moved to In Progress.');
    }
    

    public function markTaskAsDone(Request $request, $taskId) {
        $task = Task::findOrFail($taskId);
        $user = Auth::user();
    
        if ($task->assigned_to != $user->id) {
            return redirect()->back()->with('error', 'You do not have permission to mark this task as completed.');
        }
        try {
            $task->status = 'completed';
            $task->save();
        } catch (\Exception $e) {
            return redirect('student/dashboard')->with('error', 'Failed to mark task as completed: ' . $e->getMessage());
        }
        return redirect('student/dashboard')->with('success', 'Task successfully marked as completed.');
    }
    
    

    public function tasksubmit(Request $request, $id) {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'required|string',
            'assigned_to' => 'required|exists:users,id', // Validate the assigned user
        ]);
    
        try {
            $task = Task::create([
                'task_name' => $request->task_name,
                'task_description' => $request->task_description,
                'project_id' => $id,
                'assigned_to' => $request->assigned_to,
                'status' => 'pending',

            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to submit task: ' . $e->getMessage()]);
        }
    
        return response()->json(['success' => 'Task submitted successfully.']);
    }
    

    public function viewTask(Request $request, $taskName) {
        $task = Task::where('task_name', $taskName)->first();
        return response()->json($task);
    }

    public function checkDeadlines() {
        $projects = Project::whereDate('submission_date', '=', Carbon::now()->addDay()->toDateString())
                                ->where('submission_time', '>=', Carbon::now()->format('H:i'))
                                ->get();
        // Handle the upcoming deadline notification
    }
    public function showOverview($id = null)
    {
        // Fetch all projects for the authenticated user
        $projects = auth()->user()->projects;

        // Determine the current project to be displayed
        $currentProject = $id ? Project::with(['users', 'tasks', 'creator'])->find($id) : ($projects->first() ?? null);

        return view('Student.project-overview', compact('projects', 'currentProject'));
    }


}