@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tasks for Project: {{ $project->class_name }}</h1> <!-- Display Project Title -->
                    <p>Description: {{ $project->description }}</p> <!-- Display Project Description -->
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
                            <div id="pending-tasks">
                                @foreach($tasks->where('status', 'pending') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <form action="{{ route('task.start', ['taskId' => $task->id]) }}" method="POST" class="start-task-form">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">Start</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Tasks -->
                <div class="col-md-4">
                    <div class="card" style="border-top: 5px solid #17A2B8;">
                        <div class="card-body">
                            <h5>In Progress</h5>
                            <div id="inprogress-tasks">
                                @foreach($tasks->where('status', 'inprogress') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <form action="{{ route('task.complete', ['taskId' => $task->id]) }}" method="POST" class="complete-task-form">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Complete</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="col-md-4">
                    <div class="card" style="border-top: 5px solid #28A745;">
                        <div class="card-body">
                            <h5>Completed Tasks</h5>
                            <div id="completed-tasks">
                                @foreach($tasks->where('status', 'completed') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> <!-- col-md-4 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </section>
</div> <!-- content-wrapper -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX for "Complete Task" form
        $('.complete-task-form').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            
            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(response) {
                    var taskId = form.closest('.list-group-item').attr('id');
                    $('#' + taskId).appendTo('#completed-tasks'); // Move to Completed Tasks column
                    $('#' + taskId).find('form.complete-task-form').remove(); // Remove the Complete button
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log the error for debugging
                    alert('Failed to mark the task as completed. Please try again.');
                }
            });
        });
    });
</script>
@endsection
