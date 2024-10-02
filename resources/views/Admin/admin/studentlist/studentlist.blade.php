@extends('layout.app')
@section('content')

<div class="content-wrapper" style="background-color: #E6F0FF;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="font-family: 'League Spartan', sans-serif;">User List (Total: {{ $getStudent->total() }} )</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 

                @include('message')
                <div class="card">
                    <div class="card-header" style="background-color: #6991AF;">
                        <h3 class="card-title" style="font-family: 'Arimo', sans-serif;">Student List</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr style="background-color: #C7D7EB;">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($getStudent as $key => $value)
                                <tr style="background-color: {{ $key % 2 == 0 ? '#6991AF' : '#C7D7EB' }}; color: #000;">
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>
                                        <a href="{{ url('admin/student/edit/'.$value->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ url('admin/student/delete/'.$value->id) }}" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div style="padding: 10px; float: right;">
                            {!! $getStudent->appends(request()->except('page'))->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
