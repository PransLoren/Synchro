@extends('layout.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pending Project Invitations</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    @include('message')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Invitations List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            @if($invitations->count() > 0)
                                <table class="table table-striped" style="background-color: #edf2fb;">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invitations as $invitation)
                                            <tr style="background-color: #eff8ff;">
                                                <td>{{ $invitation->project->class_name }}</td>
                                                <td>
                                                    <form action="{{ route('projects.acceptInvitation', [$invitation->project->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success">Accept</button>
                                                    </form>
                                                    <form action="{{ route('projects.rejectInvitation', [$invitation->project->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">Reject</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            @else
                                <p style="padding: 15px;">No Pending Invitations</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
