<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Project;
use App\Models\User;
use App\Models\Task; 
use App\Models\Notification; 
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
        $project->description = strip_tags(str_replace('&nbsp;', '', $request->description));
        $project->created_by = Auth::user()->id;
    
        try {
            $project->save();
            $project->users()->attach(Auth::user()->id, ['role' => 'creator']); 
    
            // Notify the creator
            Notification::create([
                'user_id' => Auth::id(),
                'notifiable_type' => Project::class,
                'notifiable_id' => $project->id,
                'type' => 'project_creation', 
                'message' => "You have successfully created the project '{$project->class_name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add project: ' . $e->getMessage());
        }
    
        return redirect('student/dashboard')->with('success', 'Project successfully added');
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

        return redirect('student/dashboard')->with('success', 'Project successfully updated');
    }

    public function delete($id) {
        $project = Project::getSingle($id);
        $project->is_delete = 1;

        try {
            $project->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete project: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Project successfully deleted');
    }

    public function submit($id) {
        $project = Project::findOrFail($id);
        $user = Auth::user();
    
        $projectUser = $project->users()->where('user_id', $user->id)->first();
    
        if ($projectUser && $projectUser->pivot->role !== 'creator') {
            return redirect()->back()->with('error', 'You do not have permission to submit this project.');
        }
    
        try {
            $project->status = 'completed';
            $project->save();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit the project: ' . $e->getMessage());
        }
    
        return redirect('student/dashboard')->with('success', 'Project successfully submitted');
    }
    
    public function viewTasks($projectId) {
        $project = Project::with(['tasks.assignedUser'])->findOrFail($projectId);
        $tasks = $project->tasks;
    
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

            $project = $task->project;
            $creator = $project->users()->wherePivot('role', 'creator')->first();
            if ($creator) {
                Notification::create([
                    'user_id' => $creator->id,
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'type' => 'task_started', 
                    'message' => "The task '{$task->task_name}' has been started by '{$user->name}' in project '{$project->class_name}'.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

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

            $project = $task->project;
            $creator = $project->users()->wherePivot('role', 'creator')->first();
            if ($creator) {
                Notification::create([
                    'user_id' => $creator->id,
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'type' => 'task_completed',
                    'message' => "The task '{$task->task_name}' has been completed by '{$user->name}' in project '{$project->class_name}'.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            return redirect('student/dashboard')->with('error', 'Failed to mark task as completed: ' . $e->getMessage());
        }

        return redirect('student/dashboard')->with('success', 'Task successfully marked as completed.');
    }

    public function tasksubmit(Request $request, $id) {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'required|string',
            'assigned_to' => 'required|exists:users,id', 
        ]);
    
        try {
            $task = Task::create([
                'task_name' => $request->task_name,
                'task_description' => $request->task_description,
                'project_id' => $id,
                'assigned_to' => $request->assigned_to,
                'status' => 'pending',
            ]);

            Notification::create([
                'user_id' => $request->assigned_to,
                'notifiable_type' => Task::class,
                'notifiable_id' => $task->id,
                'type' => 'task_assignment',
                'message' => "You have been assigned a new task: '{$task->task_name}' in project '{$task->project->class_name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Failed to submit task: ' . $e->getMessage()]);
        }
    
        return redirect('student/dashboard')->with('success', 'Task submitted successfully.');
    }

    public function submitTaskForReview(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        if (Auth::id() !== $task->assigned_to) {
            return redirect()->back()->with('error', 'You are not authorized to submit this task.');
        }

        try {
            $task->status = 'submitted';
            $task->save();

            // Notify the creator
            $project = $task->project;
            $creator = $project->users()->wherePivot('role', 'creator')->first();

            if ($creator) {
                Notification::create([
                    'user_id' => $creator->id,
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'type' => 'task_submitted',
                    'message' => "Task '{$task->task_name}' has been submitted for your approval.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            return redirect()->back()->with('success', 'Task submitted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to submit task: ' . $e->getMessage());
        }
    }

    public function approveTask(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $project = $task->project;

        if (Auth::id() !== $project->created_by) {
            return redirect()->back()->with('error', 'Only the project creator can approve this task.');
        }

        try {
            $task->status = 'completed';
            $task->save();

            return redirect()->back()->with('success', 'Task approved successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve task: ' . $e->getMessage());
        }
    }

    public function rejectTask($taskId)
    {
        $task = Task::findOrFail($taskId);
    
        if (Auth::id() !== $task->project->created_by) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->status = 'inprogress';
        $task->save();
    
        Notification::create([
            'user_id' => $task->assigned_to,
            'notifiable_type' => Task::class,
            'notifiable_id' => $task->id,
            'type' => 'task_rejected',
            'message' => "Your task '{$task->task_name}' was rejected. Please make the necessary changes.",
            'is_read' => false,
            'created_at' => now(),
        ]);
    
        return response()->json(['success' => 'Task rejected successfully!']);
    }
    
    public function viewTask(Request $request, $taskName) {
        $task = Task::where('task_name', $taskName)->first();
        return response()->json($task);
    }

    public function checkDeadlines() {
        $tomorrow = Carbon::now()->addDay()->toDateString();
    
        $projects = Project::whereDate('submission_date', '=', $tomorrow)
                            ->whereNull('deleted_at')
                            ->get();
    
        foreach ($projects as $project) {
            foreach ($project->users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'notifiable_type' => Project::class,
                    'notifiable_id' => $project->id,
                    'type' => 'deadline_reminder',
                    'message' => "Reminder: The project '{$project->class_name}' is due in 24 hours.",
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
        }
    
        \Log::info('Project deadlines checked successfully at: ' . now());
    }
    
    public function projectReport()
    {
        $userProjects = Project::where('created_by', Auth::id())
                            ->whereNull('deleted_at')
                            ->paginate(10);
        $overdueProjects = Project::whereNull('deleted_at')
                                ->where(function ($query) {
                                    $query->where('submission_date', '<', now()->toDateString())
                                            ->orWhere(function ($q) {
                                                $q->where('submission_date', '=', now()->toDateString())
                                                ->where('submission_time', '<', now()->format('H:i:s'));
                                            });
                                })->get();

        return view('student.project-report', compact('userProjects', 'overdueProjects'));
    }


    public function markAsDone($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->status = 'completed'; 
            $project->save();
        }
        return redirect()->back()->with('success', 'Project marked as done!');
    }
    
    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
        }
        return redirect()->back()->with('success', 'Project deleted successfully!');
    }
       

    public function showOverview($id = null)
    {
        $projects = auth()->user()->projects()->whereNull('deleted_at')->get();
        $currentProject = $id ? Project::with(['users', 'tasks', 'creator'])
                                ->whereNull('deleted_at')
                                ->find($id) 
                            : ($projects->first() ?? null);

        return view('Student.project-overview', compact('projects', 'currentProject'));
    }

}
