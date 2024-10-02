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
                                        <!-- Removed Homework Date -->
                                        <th>Submission Date</th>
                                        <th>Description</th>
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
                                        <!-- Removed Homework Date -->
                                        <td>{{ date('d-m-Y', strtotime($value->submission_date)) }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal{{ $value->id }}"><i class="fas fa-plus"></i></button>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel{{ $value->id }}">Task Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Task Form -->
                @csrf
                <form id="taskForm{{ $value->id }}">
                    <div class="form-group">
                        <label for="taskName">Task Name:</label>
                        <input type="text" class="form-control" id="taskName" name="task_name" required>
                        <!-- Make sure the name attribute matches the field name in your controller -->
                    </div>
                    <div class="form-group">
                        <label for="taskDesc">Description:</label>
                        <input type="text" class="form-control" id="taskDesc" name="task_description" required>
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

<!-- /.content-wrapper -->

<!-- Your custom JavaScript -->
<script>
    $(document).ready(function() {
        @foreach($userProjects as $value)
        $('#inviteUserForm{{ $value->id }}').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    if (confirm('Invitation sent successfully. Do you want to close the modal?')) {
                        $('#inviteUserModal{{ $value->id }}').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    $('#inviteUserMessage{{ $value->id }}').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
                }
            });
        });
        $('#taskForm{{ $value->id }}').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Get the CSRF token
            var token = "{{ csrf_token() }}";
            
            $.ajax({
                type: 'POST',
                url: '{{ route("task.submit", ["id" => $value->id]) }}', // Use the correct route with the project ID
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': token // Include CSRF token in headers
                },
                success: function(response) {
                    if (confirm('Task submitted successfully. Do you want to close the modal?')) {
                        $('#taskModal{{ $value->id }}').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    $('#taskMessage{{ $value->id }}').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
                }
            });
        });
        @endforeach
    });


    $(document).ready(function() {
        @foreach($userProjects as $value)
        $('#inviteUserForm{{ $value->id }}').submit(function(e) {
            e.preventDefault(); // Prevent default form submission
            
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    if (confirm('Invitation sent successfully. Do you want to close the modal?')) {
                        $('#inviteUserModal{{ $value->id }}').modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    $('#inviteUserMessage{{ $value->id }}').html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
                }
            });
        });
        @endforeach
    });
</script>
@endsection
