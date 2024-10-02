@extends('layout.app')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Panel</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Dropdown for Due Date -->
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- ./col -->
            <div class="col-12">
                <!-- small box -->
                <div class="small-box" style="background-color: #124E7B; padding: 30px;">
                    <div class="inner">
                        <h3 style="font-size: 24px; color: white;">Total: {{ $getStudent->total() }}</h3>
                        <p style="color: white;">User Lists</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars" style="color: white;"></i>
                    </div>
                    <a href="{{ route('student.list') }}" class="small-box-footer" style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-12">
                <!-- small box -->
                <div class="small-box" style="background-color: #6991AF; padding: 30px;">
                    <div class="inner">
                        <h3 style="font-size: 24px; color: white;">Total: {{ $getAdmin->total() }}</h3>
                        <p style="color: white;">Admin Lists</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add" style="color: white;"></i>
                    </div>
                    <a href="{{ url('admin/admin/list') }}" class="small-box-footer" style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-12">
                <!-- small box -->
                <div class="small-box" style="background-color: #84C4F4; padding: 30px;">
                    <div class="inner">
                        <h3 style="font-size: 24px; color: white;">Total: {{ $getRecord->total() }}</h3>
                        <p style="color: white;">Created Projects</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph" style="color: white;"></i>
                    </div>
                    <a href="{{ url('admin/project/list') }}" class="small-box-footer" style="color: white;">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->

        <!-- Rest of your content goes here -->
    </div>
</div>
@endsection
