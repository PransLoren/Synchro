@extends('layout.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="color: #2e5caf; font-family: 'Poppins', sans-serif;">
                        Pending Project Invitations
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="card-header" style="background-color: #425673; color: white;">
                    <h3 class="card-title" style="font-family: 'Poppins', sans-serif;">Invitations</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if($invitations->count() > 0)
                        <table class="table table-hover table-centered">
                            <thead style="background-color: #dde2e8;">
                                <tr>
                                    <th>Project Name</th>
                                    <th style="width: 30%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invitations as $invitation)
                                    <tr style="background-color: {{ $loop->index % 2 === 0 ? '#edf2fb' : '#dbe7f0' }};">
                                        <td>{{ $invitation->project->class_name }}</td>
                                        <td>
                                            <!-- Accept Invitation Form -->
                                            <form action="{{ route('projects.acceptInvitation', ['project' => $invitation->project->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-action">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <!-- Reject Invitation Form -->
                                            <form action="{{ route('projects.rejectInvitation', ['project' => $invitation->project->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-action">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center" style="font-family: 'Poppins', sans-serif; font-size: 18px; margin: 20px 0;">
                            No Pending Invitations
                        </p>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
</div>
@endsection

<!-- Custom CSS to match the clean design -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    .content-wrapper {
        background-color: #f8f9fa;
    }

    .table-centered {
        width: 100%;
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .table-hover tbody tr:hover {
        background-color: #e8f1ff;
    }

    .btn-action {
        margin: 0 5px;
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.3s ease;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    h1, h3 {
        font-family: 'Poppins', sans-serif;
    }

    .card-header {
        font-weight: 600;
    }

    tbody tr:nth-child(even) {
        background-color: #dbe7f0;
    }

    tbody tr:nth-child(odd) {
        background-color: #edf2fb;
    }
</style>
