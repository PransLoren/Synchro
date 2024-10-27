@extends('layout.app')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $header_title }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('message')
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list"></i> Project List
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped" style="background-color: #edf2fb;">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-file"></i> Project Name</th>
                                        <th><i class="far fa-calendar-alt"></i> Submission Date</th>
                                        <th><i class="far fa-clock"></i> Submission Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getRecord as $value)
                                    <tr style="background-color: #eff8ff;">
                                        <td>{{ $value->class_name }}</td>
                                        <td>
                                            {{ $value->submission_date ? $value->submission_date->format('Y-m-d') : 'Not Set' }}
                                        </td>
                                        <td>
                                            {{ $value->submission_time ? \Carbon\Carbon::parse($value->submission_time)->format('H:i:s') : 'Not Set' }}
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
