@extends('layout.app')

@section('style')
  <style type="text/css">
    .content-wrapper {
        background-color: #FFFFFF; 
        color: #000;
        min-height: 100vh;
        padding: 20px;
    }

    .card-primary {
        border: 1px solid #1a3c5a; 
        background-color: #e1eaf2; 
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    .card-header {
        background-color: #3b5998 !important; 
        color: white !important;
        font-weight: bold;
        font-size: 18px;
        padding: 10px 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .btn-primary {
        background-color: #3b5998 !important;
        border-color: #3b5998 !important;
        font-size: 14px;
        padding: 5px 15px;
        height: 36px;
        border-radius: 6px;
        transition: background-color 0.3s, transform 0.2s;
        color: #fff; 
        font-weight: bold;
    }

    .btn-primary:hover {
        background-color: #2d4373 !important;
        border-color:  #2d4373 !important;
        transform: scale(1.05);
    }

    .form-group label {
        color: #1a3c5a; 
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
                <div class="col-md-8 offset-md-2"> 
                    @include('message')
                    <div class="card card-primary">
                        <!-- Header -->
                        <div class="card-header">
                            Add New Project
                        </div>

                        <!-- Form -->
                        <form method="post" action="" enctype="multipart/form-data">
                           {{ csrf_field() }}
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Project Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" name="class_name" 
                                           placeholder="Enter project name">
                                </div>

                                <div class="form-group">
                                    <label>Submission Date <span style="color:red">*</span></label>
                                    <input type="date" class="form-control" name="submission_date" 
                                           id="submission_date" required>
                                </div>

                                <div class="form-group">
                                    <label>Submission Time <span style="color:red">*</span></label>
                                    <input type="time" class="form-control" name="submission_time" 
                                           id="submission_time" required>
                                </div>

                                <div class="form-group">
                                    <label>Description <span style="color:red">*</span></label>
                                    <textarea name="description" class="form-control" 
                                              placeholder="Enter project description"></textarea>
                                </div>
                            </div>

                            <!-- Footer with Submit Button -->
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
