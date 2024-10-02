<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\ProjectModel;
use App\Models\User;
use App\Models\Task; 
use Auth;
use Str;


class ProjectController extends Controller
{
    public function adminProjectList(){
        $data['getRecord'] = ProjectModel::getRecord();
        $data['header_title'] = 'Project';
        return view('Admin.admin.homework.listAdmin', $data);
    }
    public function project(){
        $data['getRecord'] = ProjectModel::paginate(10);
        $data['header_title'] = 'Project';
        return view('Admin.admin.homework.list1', $data);
    }
    
    public static function add(){
        $data['header_title'] = 'Add New Project';
        return view('Admin.admin.homework.add', $data);
    }
    public function insert(Request $request)
    {
        $project = new ProjectModel;
        $project->class_name = $request->class_name;
        $project->project_date = $request->project_date;
        $project->submission_date = $request->submission_date;
        $descriptionWithoutNbsp = str_replace('&nbsp;', '', $request->description);
        $project->description = strip_tags($descriptionWithoutNbsp);
        $project->created_by = Auth::user()->id;
        $project->save();

        return redirect('student/dashboard')->with('success','Project successfully added');
    }


        public function edit($id)
        {
            $getRecord = ProjectModel::findOrFail($id);
            $data['getRecord'] = $getRecord;
            $data['header_title'] = 'Edit Project';
            return view('Admin.admin.homework.edit', $data);
        }

        public function update(Request $request, $id)
        {
            $project = ProjectModel::getSingle($id);
            $project->class_name = trim($request->class_name);
            $project->project_date = trim($request->project_date);
            $project->submission_date = trim($request->submission_date);
            $project->description = trim($request->description);
    
    
            $project->save();
    
            return redirect('student/dashboard')->with('success','Project successfully updated');
        }

        public function delete($id)
        {
            $project = ProjectModel::getSingle($id);
            $project->is_delete = 1;
            $project->save();

            return redirect()->back()->with('success','Project successfully deleted');

        }

        public function submit($id)
        {
            $project = ProjectModel::getSingle($id);
            $project->delete(); 
        
            return redirect('student/dashboard')->with('success','Project successfully submit')->with('confirmation', 'Project successfully submit');;
        }

        public function invite(Request $request, $projectId)
        {
          
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
     
            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->all());
            }
    
            return response()->json(['success' => 'Invitation sent successfully.']);
        }

        public function viewTasks($projectId)
        {

            $project = ProjectModel::with(['tasks' => function ($query) {
                $query->where('status', '!=', 'completed'); 
            }])->findOrFail($projectId);

            return view('Student.viewTask', compact('project'));
        }
        

        public function markTaskAsDone(Request $request, $projectId, $taskId)
        {
            $task = Task::findOrFail($taskId);
            $task->delete(); 
    
            return redirect('student/dashboard')->with('success','Project successfully submit')->with('confirmation', 'Project successfully submit');
        }
    
    
    public function tasksubmit(Request $request, $projectId)
    {

        $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'required|string',
        ]);
        

        $project = ProjectModel::findOrFail($projectId);
        
        $task = new Task();
        $task->task_name = $request->task_name;
        $task->task_description = $request->task_description;
        $task->project_id = $projectId;
        $task->save();
        return response()->json(['success' => 'Task submitted successfully.']);
    }
    

    public function viewTask(Request $request, $taskName)
    {
        $taskName = $request->task_name;
        $task = Task::where('task_name', $taskName)->first();
        return response()->json($task);
    }

}
