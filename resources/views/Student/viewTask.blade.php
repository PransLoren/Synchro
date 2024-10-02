@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $project->class_name }}</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <h3>Tasks</h3>

            @if($project->tasks->isEmpty())
                <p>No tasks found for this project.</p>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="list-group">
                                    @foreach($project->tasks->where('status', '!=', 'done') as $task)
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $task->task_name }}</h5>
                                                <form action="{{ route('done.task', ['projectId' => $project->id, 'taskId' => $task->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">Done</button>
                                                </form>
                                            </div>
                                            <p class="mb-1">{{ $task->task_description }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
