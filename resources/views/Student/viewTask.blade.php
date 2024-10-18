@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tasks for Project: {{ $project->class_name }}</h1>
                    <p>Description: {{ $project->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Pending Tasks -->
                <div class="col-md-3">
                    <div class="card" style="border-top: 5px solid #FFC107;">
                        <div class="card-body">
                            <h5>Pending Tasks</h5>
                            <div id="pending-tasks">
                                @foreach($tasks->where('status', 'pending') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <p><strong>Assigned to:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</p>
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
                <div class="col-md-3">
                    <div class="card" style="border-top: 5px solid #17A2B8;">
                        <div class="card-body">
                            <h5>In Progress</h5>
                            <div id="inprogress-tasks">
                                @foreach($tasks->where('status', 'inprogress') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <p><strong>Assigned to:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</p>
                                        <form action="{{ route('task.submit', ['taskId' => $task->id]) }}" method="POST" class="submit-task-form">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Submit for Review</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

               <!-- Submitted Tasks -->
                <div class="col-md-3">
                    <div class="card" style="border-top: 5px solid #007BFF;">
                        <div class="card-body">
                            <h5>Submitted for Review</h5>
                            <div id="submitted-tasks">
                                @foreach($tasks->where('status', 'submitted') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <p><strong>Assigned to:</strong> {{ $task->assignedUser->name }}</p>

                                        @if(Auth::id() == $project->created_by)
                                            <form action="{{ route('task.approve', ['taskId' => $task->id]) }}" method="POST" class="approve-task-form" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>

                                            <form action="{{ route('task.reject', ['taskId' => $task->id]) }}" method="POST" class="reject-task-form" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="col-md-3">
                    <div class="card" style="border-top: 5px solid #28A745;">
                        <div class="card-body">
                            <h5>Completed Tasks</h5>
                            <div id="completed-tasks">
                                @foreach($tasks->where('status', 'completed') as $task)
                                    <div class="list-group-item" id="task-{{ $task->id }}">
                                        <h6>{{ $task->task_name }}</h6>
                                        <p>{{ $task->task_description }}</p>
                                        <p><strong>Assigned to:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.submit-task-form').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function () {
                    var taskId = form.closest('.list-group-item').attr('id');
                    $('#' + taskId).appendTo('#submitted-tasks').find('form').remove();
                    alert('Task submitted for review!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to submit the task.');
                }
            });
        });

        $('.approve-task-form').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function () {
                    var taskId = form.closest('.list-group-item').attr('id');
                    $('#' + taskId).appendTo('#completed-tasks').find('form').remove();
                    alert('Task approved successfully!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to approve the task.');
                }
            });
        });

        $('.reject-task-form').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function () {
                    var taskId = form.closest('.list-group-item').attr('id');
                    $('#' + taskId).appendTo('#inprogress-tasks').find('form').remove();
                    alert('Task rejected successfully!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to reject the task.');
                }
            });
        });
    });
</script>
@endsection
