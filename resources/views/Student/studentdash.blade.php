@extends('layout.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"></h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <!-- Notification Button -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <ul class="navbar-nav ml-auto">
            <!-- Notification button -->
            <li class="nav-item">
            <a href="{{ route('notifications.index') }}" class="btn btn-primary">
                Notifications <span id="notification-count" class="badge badge-warning">0</span>
            </a>
            </li>
        </ul>
    </nav>
    <!-- End of Notification Button -->

    <h1>Project Report</h1>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('message')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Project List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-striped" style="background-color: #edf2fb;">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Submission Date</th>
                                        <th>Submission Time</th>
                                        <th>Add Tasks</th>
                                        <th>Action</th>
                                        <th>Edit Project</th>
                                        <th>Invite Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userProjects as $value)
                                        <tr style="background-color: #eff8ff;">
                                            <td>
                                                <a href="{{ route('project.view.tasks', ['projectId' => $value->id]) }}">
                                                    {{ $value->class_name }}
                                                </a>
                                            </td>
                                            <td>{{ $value->submission_date }}</td>
                                            <td>{{ $value->submission_time }}</td>
                                            <td>
                                                @if(Auth::id() == $value->created_by) <!-- Only project creator can add tasks -->
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal{{ $value->id }}"><i class="fas fa-plus"></i></button>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ url('student/project/project/submit/'.$value->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to end this task?')"><i class="fas fa-check"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ url('student/project/project/edit/'.$value->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                            </td>
                                            <td> 
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#inviteUserModal{{ $value->id }}">Invite User</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="padding: 10px; float: right;">
                                {!! $userProjects->appends(request()->except('page'))->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@foreach($userProjects as $value)
<div class="modal fade" id="inviteUserModal{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="inviteUserModalLabel{{ $value->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteUserModalLabel{{ $value->id }}">Invite User to {{ $value->class_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Invite User Form -->
                <form id="inviteUserForm{{ $value->id }}" action="{{ route('projects.invite', ['project' => $value->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Invite</button>
                </form>
                <!-- Display success or error message -->
                <div id="inviteUserMessage{{ $value->id }}"></div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Task Modal -->
@foreach($userProjects as $value)
<div class="modal fade" id="taskModal{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel{{ $value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #46637F; color: #FFFFFF;">
                <h5 class="modal-title" id="taskModalLabel{{ $value->id }}" style="color: #101621;">
                    Task Form for Project: {{ $value->class_name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #FFFFFF;">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #E1E3E4; color: #101621;">
                <!-- Task Form -->
                <form id="taskForm{{ $value->id }}" action="{{ route('task.submit', ['id' => $value->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="taskName">Task Name:</label>
                        <input type="text" class="form-control" id="taskName" name="task_name" required>
                    </div>
                    <div class="form-group">
                        <label for="taskDesc">Description:</label>
                        <input type="text" class="form-control" id="taskDesc" name="task_description" required>
                    </div>
                    <div class="form-group">
                        <label for="assignedTo">Assign To:</label>
                        <select class="form-control" id="assignedTo" name="assigned_to" required>
                            <option value="" disabled selected>Select User</option>
                            @foreach($value->users as $user)
                                @if($user->id !== $value->created_by) <!-- Exclude creator of the project -->
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Submit</button>
                </form>
                <!-- Display success or error message -->
                <div id="taskMessage{{ $value->id }}"></div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to fetch unread notifications count
        function fetchUnreadNotificationsCount() {
            $.ajax({
                url: "{{ route('notifications.unread.count') }}", // Fetch unread count
                method: 'GET',
                success: function(response) {
                    // Update the notification count badge
                    $('#notification-count').text(response.unread_count);
                },
                error: function(error) {
                    console.error('Failed to fetch unread notifications count', error);
                }
            });
        }

        // Fetch unread count every 30 seconds
        setInterval(fetchUnreadNotificationsCount, 30000);

        // Initial fetch on page load
        fetchUnreadNotificationsCount();
    });
</script>

@endsection
