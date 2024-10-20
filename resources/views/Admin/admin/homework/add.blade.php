@extends('layout.app')

@section('style')
  <style type="text/css">
    .content-wrapper {
      background-color: #d1eaf6; 
      color: #000; 
      min-height: 100vh; 
      padding: 20px; 
    }

    .card-primary {
      border: 1px solid #357ca5;
      background-color: #f8fafc;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
      margin-top: 20px; 
    }

    .btn-primary {
      background-color: #357ca5;
      border-color: #357ca5;
      font-size: 14px;
      padding: 5px 15px;
      height: 36px;
      border-radius: 6px;
      transition: background-color 0.3s, transform 0.2s;
    }

    .btn-primary:hover {
      background-color: #2a5f7d;
      border-color: #2a5f7d;
      transform: scale(1.05); 
    }

    .form-group label {
      color: #357ca5;
      font-weight: bold;
      font-size: 14px; 
    }

    .form-control {
      height: 36px;
      font-size: 14px;
      padding: 5px 10px;
      border-radius: 6px;
    }

    textarea.form-control {
      height: 120px; 
      resize: none; 
    }

    .card-footer {
      text-align: right;
      padding: 10px 15px;
    }

    .sidebar {
      min-height: 100vh; 
    }
  </style>
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 style="font-size: 24px;">Add New Project</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 offset-md-2"> <!-- Centering the form -->
                    @include('message')
                    <div class="card card-primary">
                        <form method="post" action="" enctype="multipart/form-data">
                           {{ csrf_field() }}
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Project Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" name="class_name" id="getproject"
                                           value="{{ $getRecord->project->name ?? '' }}" 
                                           placeholder="Enter project name">
                                </div>

                                <div class="form-group">
                                    <label>Submission Date <span style="color:red">*</span></label>
                                    <input type="date" class="form-control" name="submission_date" required 
                                           id="submission_date">
                                </div>

                                <div class="form-group">
                                    <label>Submission Time <span style="color:red">*</span></label>
                                    <input type="time" class="form-control" name="submission_time" required 
                                           id="submission_time">
                                </div>

                                <div class="form-group">
                                    <label>Description <span style="color:red">*</span></label>
                                    <textarea name="description" class="form-control" 
                                              placeholder="Enter project description"></textarea>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('script')
   <script type="text/javascript">
        $(function () {
            const today = new Date().toISOString().split('T')[0]; 
            $('#submission_date').attr('min', today); 
        });
   </script>
@endsection
