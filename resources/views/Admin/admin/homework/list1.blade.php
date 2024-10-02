@extends('layout.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Project</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include ('message')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Project List
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-striped" style="background-color: #edf2fb;">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-file"></i> Project Name</th>
                                        <th><i class="far fa-calendar-alt"></i> Homework Date</th>
                                        <th><i class="far fa-calendar-alt"></i> Submission Date</th>
                                        <th><i class="fas fa-info-circle"></i> Description</th>
                                        <th><i class="fas fa-cogs"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getRecord as $value)
                                    <tr style="background-color: #eff8ff;">
                                        <td>{{ $value->class_name}}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->submission_date)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->created_date)) }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>
                                            <a href="{{ url('admin/project/project/edit/'.$value->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                            <form action="{{ url('admin/project/project/delete/'.$value->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?')"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="padding: 10px; float: right;">
                                {!! $getRecord->appends(request()->except('page'))->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
