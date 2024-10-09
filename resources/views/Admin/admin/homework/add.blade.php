@extends('layout.app')
@section('style')
  <style type="text/css">
    .content-wrapper {
      background-color: #d1eaf6; /* Light blue */
      color: #000; /* Black text */
    }
    .card-primary {
      border: 1px solid #357ca5;
      background-color: #f8fafc;
    }
    .btn-primary {
      background-color: #357ca5;
      border-color: #357ca5;
    }
    .btn-primary:hover {
      background-color: #2a5f7d;
      border-color: #2a5f7d;
    }
    .form-group label {
      color: #357ca5;
      font-weight: bold;
    }
    /* Make the form larger */
    .card-body {
      padding: 20px;
    }
  </style>
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add New Project</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('message')
                    <div class="card card-primary">
                        <form method="post" action="" enctype="multipart/form-data">
                           {{ csrf_field() }}
                          <div class="card-body">

                          <div class="form-group">
                            <label>Project Name <span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="class_name" id="getproject" value="{{ $getRecord->project->name ?? '' }}" >
                        </div>

            

                          <div class="form-group">
                            <label>Submission Date <span style="color:red">*</span></label>
                            <input type="date" class="form-control" name="submission_date" required id="submission_date">
                          </div>
                          
                          <!-- Added Submission Time Field -->
                          <div class="form-group">
                            <label>Submission Time <span style="color:red">*</span></label>
                            <input type="time" class="form-control" name="submission_time" required id="submission_time">
                          </div>

                          <div class="form-group">
                            <label>Description <span style="color:red">*</span></label>
                            <textarea name="description" class="form-control" style="height: 300px"></textarea>
                          </div>
                    </div>

                    <div class="card-footer" style="text-align: right;">
                        <button type="submit" class="btn btn-primary"> Submit</button>
                    </div>
                </form>
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
