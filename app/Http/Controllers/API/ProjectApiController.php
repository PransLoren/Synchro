<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProjectModel;
use App\Models\Task;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;

class ProjectApiController extends Controller
{

    public function project(){

        $data['getRecord'] = ProjectModel::get();
        $data['header_title'] = 'Add New Project';
        return response()->json($data);
    }
    public function add()
    {
        $data['header_title'] = 'Add New Project';
        return response()->json($data);
    }
    
    public function insert(Request $request)
    {
        $project = new ProjectModel;
        $project->class_name = trim($request->class_name);
        $project->project_date = trim($request->project_date);
        $project->submission_date = trim($request->submission_date);
        $project->description = trim($request->description);
        $project->created_by = Auth::user()->id;
        $project->save();

        // Return a JSON response indicating the success message.
        return response()->json(['message' => 'Project successfully added']);
    }

    public function edit($id)
    {
        $getRecord = ProjectModel::find($id);
        if (!$getRecord) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        return response()->json(['data' => $getRecord]);
    }

    public function update(Request $request, $id)
    {
        $project = ProjectModel::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }
        $project->class_name = trim($request->class_name);
        $project->project_date = trim($request->project_date);
        $project->submission_date = trim($request->submission_date);
        $project->description = trim($request->description);
        $project->created_by = Auth::user()->id;

        $project->save();

        return response()->json(['message' => 'Project successfully updated']);
    }

    public function delete($id)
    {
        $project = ProjectModel::find($id);
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $project->isDelete = 1;
        $project->save();

        return response()->json(['message' => 'Project successfully deleted']);
    }
        public function tasksubmit(Request $request)
        {
        // Validate the incoming request data
        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'required|string',
            'project_id' => 'required|integer',
        ]);

        $task = new Task();
        $task->task_name = $request->task_name;
        $task->project_id = $request->project_id; // Assign project_id from request
        // Assuming 'task_description' is also part of the request
        $descriptionWithoutNbsp = str_replace('&nbsp;', '', $request->task_description);
        $task->task_description = strip_tags($descriptionWithoutNbsp);
        $task->save();

        return response()->json(['success' => 'Task submitted successfully.']);
        }

        public function submit($id)
        {
            $project = ProjectModel::find($id);

            if (!$project) {
                return response()->json(['error' => 'Project not found.'], 404);
            }

            $project->delete();

            return response()->json(['success' => 'Project successfully submitted.']);
        }

        public function markTaskAsDone(Request $request, $projectId, $taskId)
        {
            $task = Task::findOrFail($taskId);
    
            if (!$task) {
                return response()->json(['error' => 'Task not found.'], 404);
            }
    
            $task->delete();
    
            return response()->json(['success' => 'Task marked as done.']);
        }


        public function viewTasks($projectId)
        {
            $project = ProjectModel::with(['tasks' => function ($query) {
                $query->where('status', '!=', 'completed'); 
            }])->findOrFail($projectId);
    
            return response()->json($project);
        }
}
