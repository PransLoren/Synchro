@extends('layout.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-family: 'Poppins', sans-serif; color: #2e5caf;">
                        Tasks for Project: {{ $project->class_name }}
                    </h1>
                    <p style="color: #555;">Description: {{ $project->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Task Columns -->
                @php
                    $taskStatuses = [
                        'pending' => ['color' => '#FFC107', 'title' => 'Pending Tasks', 'formBtn' => 'Start', 'btnClass' => 'btn-primary'],
                        'inprogress' => ['color' => '#17A2B8', 'title' => 'In Progress', 'formBtn' => 'Submit for Review', 'btnClass' => 'btn-warning'],
                        'submitted' => ['color' => '#007BFF', 'title' => 'Submitted for Review', 'approveBtn' => 'Approve', 'rejectBtn' => 'Reject', 'btnApprove' => 'btn-success', 'btnReject' => 'btn-danger'],
                        'completed' => ['color' => '#28A745', 'title' => 'Completed Tasks']
                    ];
                @endphp

                @foreach ($taskStatuses as $status => $details)
                    <div class="col-md-3">
                        <div class="card" style="border-top: 5px solid {{ $details['color'] }}; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-body">
                                <h5 style="font-family: 'Poppins', sans-serif;">{{ $details['title'] }}</h5>
                                <div id="{{ $status }}-tasks">
                                    @foreach ($tasks->where('status', $status) as $task)
                                        <div class="list-group-item mb-2" id="task-{{ $task->id }}" style="border-radius: 8px; background-color: #f7f9fc; padding: 15px;">
                                            <h6>{{ $task->task_name }}</h6>
                                            <p>{{ $task->task_description }}</p>
                                            <p><strong>Assigned to:</strong> {{ $task->assignedUser->name ?? 'Unassigned' }}</p>

                                            @if($status === 'pending' || $status === 'inprogress')
                                                <form action="{{ route('task.' . ($status === 'pending' ? 'start' : 'submit'), ['taskId' => $task->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $details['btnClass'] }} btn-sm">{{ $details['formBtn'] }}</button>
                                                </form>
                                            @elseif($status === 'submitted' && Auth::id() == $project->created_by)
                                                <form action="{{ route('task.approve', ['taskId' => $task->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $details['btnApprove'] }} btn-sm">{{ $details['approveBtn'] }}</button>
                                                </form>
                                                <form action="{{ route('task.reject', ['taskId' => $task->id]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn {{ $details['btnReject'] }} btn-sm">{{ $details['rejectBtn'] }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.submit-task-form, .approve-task-form, .reject-task-form').submit(function (e) {
            e.preventDefault();
            var form = $(this);
            var actionUrl = form.attr('action');
            var taskId = form.closest('.list-group-item').attr('id');
            var targetDiv = form.hasClass('submit-task-form') ? '#submitted-tasks' :
                            form.hasClass('approve-task-form') ? '#completed-tasks' : '#inprogress-tasks';

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function () {
                    $('#' + taskId).appendTo(targetDiv).find('form').remove();
                    alert('Action successful!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to complete the action.');
                }
            });
        });
    });
</script>
@endsection

<!-- Custom CSS -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    .content-wrapper {
        background-color: #f8f9fa;
    }

    .list-group-item {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 5px 10px;
        font-family: 'Poppins', sans-serif;
    }

    h1, h5 {
        font-family: 'Poppins', sans-serif;
    }

    .btn-success:hover, .btn-danger:hover, .btn-primary:hover, .btn-warning:hover {
        opacity: 0.8;
    }

    .card {
        border-radius: 8px;
    }
</style>
