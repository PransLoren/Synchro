@extends('layout.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Report</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <!-- Card for Project Report -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Project List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-hover table-striped" style="background-color: #edf2fb;">
                                <thead style="background-color: #dde2e8;">
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Submission Date</th>
                                        <th>Submission Time</th>
                                        <th>Add Tasks</th>
                                        <th>Action</th>
                                        <th>Invite Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background-color: #eff8ff;">
                                        <td>Project ni Dos</td>
                                        <td>2024-10-15</td>
                                        <td>13:07:00</td>
                                        <td>
                                            <button class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning">Invite User</button>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #eff8ff;">
                                        <td>Uno Project</td>
                                        <td>2024-10-15</td>
                                        <td>10:58:00</td>
                                        <td>
                                            <button class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning">Invite User</button>
                                        </td>
                                    </tr>
                                    <tr style="background-color: #eff8ff;">
                                        <td>DOS</td>
                                        <td>2024-10-15</td>
                                        <td>22:27:00</td>
                                        <td>
                                            <button class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-warning">Invite User</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End of Project Report Card -->
                </div>
            </div>
        </div>
    </section>
</div>

@endsection