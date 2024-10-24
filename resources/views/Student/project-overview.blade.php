@extends('layout.app') 

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Left Column: Project List -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="background-color: #1A1A2E; color: #FFFFFF;">
                    <h4>My Projects</h4>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <p>No projects available.</p>
                    @else
                    <ul class="list-group">
                        @foreach($projects as $project)
                            <li class="list-group-item @if($project->id == $currentProject->id) active @endif">
                                <!-- Ensure the 'a' tag spans the entire list item -->
                                <a href="{{ route('project.overview', ['id' => $project->id]) }}" style="text-decoration: none; color: inherit; display: block;">
                                    {{ $project->class_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Project Details -->
<div class="col-md-8">
    @if($currentProject)
    <div class="card">
        <div class="card-header" style="background-color: #1A1A2E; color: #FFFFFF;">
            <h4>{{ $currentProject->class_name }} - Project Details</h4>
        </div>
        <div class="card-body">
            <h5>Project Description:</h5>
            <p>{{ $currentProject->description }}</p>
            <p><strong>Submission Date:</strong> {{ $currentProject->submission_date }} at {{ $currentProject->submission_time }}</p>
            <p><strong>Created By:</strong> {{ $currentProject->creator->name }}</p>

            <h5>Group Members:</h5>
            <ul class="list-group mb-4">
                @foreach($currentProject->users as $member)
                    <li class="list-group-item">
                        {{ $member->name }}
                    </li>
                @endforeach
            </ul>

            <h5>Tasks:</h5>
            <ul class="list-group">
                @foreach($currentProject->tasks as $task)
                    <li class="list-group-item">
                        {{ $task->task_name }}
                        @if($task->status == 'completed')
                            <span class="badge badge-success float-right">Completed</span>
                        @else
                            <span class="badge badge-warning float-right">{{ ucfirst($task->status) }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @else
        <div class="alert alert-info">Please select a project to see its details.</div>
    @endif
</div>

@endsection
