@extends('layout.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Left Column: Project List -->
         
        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="background-color: #425673; color: #FFFFFF;">
                    <h4>My Projects</h4>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <p>No projects available.</p>
                    @else
                        <ul class="list-group">
                            @foreach($projects as $project)
                                <li class="list-group-item 
                                    @if($currentProject && $project->id == $currentProject->id) active @endif">
                                    <a href="{{ route('project.overview', ['id' => $project->id]) }}" 
                                       style="text-decoration: none; color: inherit; display: block; ">
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
                    <div class="card-header" style="background-color: #425673; color: #FFFFFF;">
                        <h4>{{ $currentProject->class_name }} - Project Details</h4>
                    </div>
                    <div class="card-body">
                        <h5>Project Description:</h5>
                        <p>{{ $currentProject->description }}</p>
                        <p><strong>Submission Date:</strong> {{ $currentProject->submission_date }} 
                            at {{ $currentProject->submission_time }}</p>
                        <p><strong>Created By:</strong> {{ $currentProject->creator->name }}</p>

                        <h5>Group Members:</h5>
                        <ul class="list-group mb-4">
                            @forelse($currentProject->users as $member)
                                <li class="list-group-item">{{ $member->name }}</li>
                            @empty
                                <li class="list-group-item">No members assigned.</li>
                            @endforelse
                        </ul>

                        <h5>Tasks:</h5>
                        <ul class="list-group">
                            @forelse($currentProject->tasks as $task)
                                <li class="list-group-item">
                                    {{ $task->task_name }}
                                    @if($task->status == 'completed')
                                        <span class="badge badge-success float-right">Completed</span>
                                    @else
                                        <span class="badge badge-warning float-right">{{ ucfirst($task->status) }}</span>
                                    @endif
                                </li>
                            @empty
                                <li class="list-group-item">No tasks available.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-info">Please select a valid project to see its details.</div>
            @endif
        </div>
    </div>
</div>
@endsection
<style>
        body, .container, .card, .card-header, .card-body, .list-group, .list-group-item, h4, h5, p {
        font-family: 'Poppins', sans-serif;
    }
    .list-group{
        background-color: #425673;
        color: grey !important;
    }
    .list-group-item.active {
    background-color: #b8c7dc !important;
    border-color: #b8c7dc !important;
    color: black !important; 
    font-weight: 600 !important;
}

</style>