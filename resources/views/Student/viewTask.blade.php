@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tasks</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Pending Tasks -->
                <div class="col-md-4">
                    <div class="card" style="border-top: 5px solid #FFC107;">
                        <div class="card-body">
                            <h5>Pending Tasks</h5>
                            @foreach($tasks->where('status', 'pending') as $task)
                                <div class="list-group-item">
                                    <h6>{{ $task->task_name }}</h6>
                                    <p>{{ $task->task_description }}</p>
                                    <form action="{{ route('task.start', ['taskId' => $task->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Start</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- In Progress Tasks -->
                <div class="col-md-4">
                    <div class="card" style="border-top: 5px solid #17A2B8;">
                        <div class="card-body">
                            <h5>In Progress</h5>
                            @foreach($tasks->where('status', 'inprogress') as $task)
                                <div class="list-group-item">
                                    <h6>{{ $task->task_name }}</h6>
                                    <p>{{ $task->task_description }}</p>
                                    <form action="{{ route('task.complete', ['taskId' => $task->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Complete</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="col-md-4">
                    <div class="card" style="border-top: 5px solid #28A745;">
                        <div class="card-body">
                            <h5>Completed Tasks</h5>
                            @foreach($tasks->where('status', 'completed') as $task)
                                <div class="list-group-item">
                                    <h6>{{ $task->task_name }}</h6>
                                    <p>{{ $task->task_description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
