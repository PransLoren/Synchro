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
    <!-- Notification and Profile -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid" style="display: flex; justify-content: flex-end; align-items: center;">
            <ul class="navbar-nav" style="display: flex; gap: 30px; align-items: center; margin-left: auto;">
            <li class="nav-item dropdown" style="position: relative;">
                <a class="nav-link" href="#" id="notificationDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell" style="font-size: 24px; color: #2e5caf;"></i>
                    <span id="notification-count" class="badge badge-notif">7</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown">
                    <h6 class="dropdown-header">Notifications</h6>
                    <div class="notification-list" id="notificationList"></div>
                    <div class="dropdown-footer text-center">
                        <a href="{{ route('notifications.index') }}">View all notifications</a>
                    </div>
                </div>

            </li>

                <li class="nav-item">
                    <a href="{{ url('student/profile') }}" 
                       class="up @if(Request::segment(2) == 'profile') active @endif"
                       style="font-family: 'Poppins', sans-serif; background-color: white; color: #2e5caf; font-size: 18px;">
                        Profile
                    </a>
                </li>
            </ul>
        </div>
    </nav>



<!-- Project Report Title -->
<h1 class="report-title" style="margin-left:4rem; color:#2e5caf; font-size: 28px; font-family: 'Poppins', sans-serif;">
        Project Report
    </h1>

    <!-- Tabs for Project List, Overdue, and Completed Projects -->
    <div class="tabs" style="margin-left: 4rem; margin-bottom: 20px;">
        <a href="#project-list" class="tab active" data-tab="project-list">Project List</a>
        <a href="#overdue-projects" class="tab" data-tab="overdue-projects">Overdue Projects</a>
        <a href="#completed-projects" class="tab" data-tab="completed-projects">Completed Projects</a>
    </div>

    <!-- Project Sections -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('message')

                    <!-- Project List Section -->
                    <div class="card" id="project-list-content">
                        <div class="card-header">
                            <h3 class="card-title">Project List</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-centered">
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
                                        <tr style="background-color: {{ $loop->index % 2 === 0 ? '#edf2fb' : '#dbe7f0' }};">
                                            <td><a href="{{ route('project.view.tasks', ['projectId' => $value->id]) }}">{{ $value->class_name }}</a></td>
                                            <td>{{ $value->submission_date }}</td>
                                            <td>{{ $value->submission_time }}</td>
                                            <td>
                                                @if(Auth::id() == $value->created_by)
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal{{ $value->id }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ url('student/project/project/submit/'.$value->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ url('student/project/project/edit/'.$value->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-invite" data-toggle="modal" data-target="#inviteUserModal{{ $value->id }}">
                                                    Invite User
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Overdue Projects Section -->
                    <div class="card" id="overdue-projects-content" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">Overdue Projects</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Submission Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueProjects as $project)
                                        <tr>
                                            <td>{{ $project->class_name }}</td>
                                            <td>{{ $project->submission_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Completed Projects Section -->
                    <div class="card" id="completed-projects-content" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">Completed Projects</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Completed At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedProjects as $project)
                                        <tr>
                                            <td>{{ $project->class_name }}</td>
                                            <td>{{ $project->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                <form id="taskForm{{ $value->id }}" action="{{ route('task.add', ['id' => $value->id]) }}" method="POST">
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
    $(document).ready(function () {
        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.unread') }}",
                method: 'GET',
                success: function (notifications) {
                    console.log('Fetched Notifications:', notifications); 
                    let notificationList = '';
                    if (Array.isArray(notifications) && notifications.length > 0) {
                        notifications.forEach(notification => {
                            console.log('Notification Object:', notification); 

                            const message = notification.message || 'No message found';
                            const createdAt = formatTimestamp(notification.created_at);

                            notificationList += `
                                <a href="#" class="dropdown-item">
                                    <strong>${message}</strong>
                                    <small class="text-muted d-block">${createdAt}</small>
                                </a>`;
                        });
                    } else {
                        notificationList = '<p class="text-center p-2">No new notifications</p>';
                    }

                    $('#notificationList').html(notificationList);
                    $('#notification-count').text(notifications.length);
                },
                error: function (error) {
                    console.error('Failed to fetch notifications:', error);
                }
            });
        }

        function fetchUnreadNotificationsCount() {
            $.ajax({
                url: "{{ route('notifications.unread.count') }}",
                method: 'GET',
                success: function (response) {
                    $('#notification-count').text(response.unread_count);
                },
                error: function (error) {
                    console.error('Failed to fetch unread notifications count:', error);
                }
            });
        }

        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                hour: 'numeric', minute: 'numeric', second: 'numeric',
                year: 'numeric', month: 'short', day: 'numeric'
            });
        }

        $('#notificationDropdown').on('click', function () {
            fetchNotifications(); 
            fetchUnreadNotificationsCount(); 
        });

        setInterval(fetchUnreadNotificationsCount, 30000);
        fetchUnreadNotificationsCount(); 

    
        $('.tab').click(function () {
            const tabId = $(this).data('tab'); 
            $('.card').hide(); 
            $(`#${tabId}-content`).show(); 
            $('.tab').removeClass('active'); 
            $(this).addClass('active'); 
        });

        // Ensure correct tab content is shown on page load
        const activeTabId = $('.tab.active').data('tab');
        if (activeTabId) {
            $(`#${activeTabId}-content`).show(); 
        } else {
            $('#project-list-content').show(); 
        }
    });
</script>


@endsection
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");
/* .create-btn {
    background-color: #a4a2a4; 
    color: white;
    margin: 50px;
    text-decoration: none;
    display: inline-block;
    list-style: none;
    padding: 30px;
    width: 150px;
    border-radius: 1rem;
    margin-top: -50px;
    margin-left:4rem;
    font-family: "Poppins", sans-serif;
    text-align: center;
    transition: background-color 0.3s ease, color 0.5s ease; 
*/
html, body {
    margin: 0;
    padding: 0;
}
.new-header {
    background-color: white;
    padding: 40px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    margin-top: 0; 
    max-width: 95rem; 
    height: 70px;
    width: 100%;
    margin-left: 5rem;
}

.create-btn:hover {
    background-color: #95b9cd; 
    color: #fff; 
    text-decoration: none;
} */
/* header */
.up{
    border-color: none;
    
    padding: 8px;
    
}
.up:hover{
    border-color: none;
    text-decoration: none;
    font-weight: 700;
    border-radius: 1rem;
}
.info2 {
    margin: 4rem;
    font-family: "Poppins", sans-serif;
    font-size: 28px;
    margin-left:4rem;
    margin-top: -1rem;
    text-decoration: none;
}
.d-block:hover{
    text-decoration: none;
}
.h1{
    font-family: "Poppins", sans-serif;
    text-decoration: none;
}
.report-title:hover{
    text-decoration: none;
}
.content {
    display: flex;
    justify-content: center; 
    padding: 20px; 
    margin-left:4rem;
    margin-top: 1rem;
}

.table-centered {
    width: 100%; 
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
    margin: 0 auto; 
    /* height: 40rem; */
}

div.card {
    width: 100%;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
    overflow: hidden;
}
.row {
    max-width: 96rem; 
    width: 100%;
    
}
div.card-body.p-0 {
    font-family: "Poppins", sans-serif;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
}
/* proj list */
div.card-header{
    background-color: #425673;
    color: fff;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
    
    /* border-radius: 30rem; */
}
tr{
    
    background-color: #dbdfe5;/* title */
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
    
}

div.card-body{
    background-color: #425673;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.20);
    overflow-x: auto;
}

/*--------------------------------------- sodebar menu */
.nav-pills.nav-sidebar.flex-column{
    margin-top: 1rem;
    font-family: "Poppins", sans-serif;
}

ul, ol, li {
    list-style-type: none;
    
    padding: 0;
}
.btn{
    text-align: center;
    font-family: "Poppins", sans-serif;
    height: 36px;
}

.rock a {
    font-weight: 700;
    text-decoration: underline;
    color: #425673;
    text-transform: uppercase;
    
    /* text-decoration: none;  */
}

.rock a:hover {
    color: #ff4500; 
    
}
.btn.btn-success{
    background-color: #D98D36;
    border-color: #D98D36;
    font-size: 15px;
    height: 35px;
}
.btn.btn-warning {
    background-color: #95b9cd;
    border-color: #95b9cd;
    font-size: 15px;
    height: 30px;
    
}
.btn.btn-primary{
    background-color: #2e5caf;
    border-color: #2e5caf;
    font-size: 10px;
    height: 30px;
}

/* Notification Dropdown */
.notification-dropdown {
    width: 320px;
    max-height: 400px;
    overflow-y: auto; /* Make the dropdown scrollable if content exceeds height */
    padding: 10px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.notification-dropdown h6 {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    padding-left: 10px;
    text-align: left;
}

/* Each Notification Item */
.dropdown-item {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 10px;
    font-size: 14px;
    border-bottom: 1px solid #f1f1f1;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item small {
    display: block;
    color: #888;
    margin-top: 4px;
}

/* Footer Link */
.dropdown-footer a {
    display: inline-block;
    margin-top: 8px;
    font-weight: 500;
    color: #2e5caf;
    text-decoration: none;
}

.dropdown-footer a:hover {
    text-decoration: underline;
}

/* Scrollbar Styling */
.notification-dropdown::-webkit-scrollbar {
    width: 6px;
}

.notification-dropdown::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
}

.notification-dropdown::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

tr {
    /* background-color: #f7f7f7; */
}
</style>
