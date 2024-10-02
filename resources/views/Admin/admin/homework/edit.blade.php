@extends('layout.app')
@section('style')
  <style type="text/css">
  </style>
@endsection
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Project</h1>
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
                        <form method="post" action= "{{ url('student/project/project/edit/'.$getRecord->id) }}" enctype="multipart/form-data">
                           {{ csrf_field() }}
                          <div class="card-body">

                          
                          <div class="form-group">
                            <label>Project Name<span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="class_name" id="getRecord" value="{{ $getRecord->class_name }}" required>
                        </div>

                          <div class="form-group">
                            <label>Project Date <span style="color:red">*</span></label>
                            <input type="date" value="{{ $getRecord->project_date }}" class="form-control" name="project_date" required>
                          </div>

                          <div class="form-group">
                            <label>Submission Date <span style="color:red">*</span></label>
                            <input type="date" value="{{ $getRecord->submission_date }}" class="form-control" name="submission_date" required>
                          </div>

                          <div class="form-group">
                            <label>Description <span style="color:red">*</span></label>
                            <textarea id="compose-textarea" name="description" class="form-control" style="height: 300px">{{ $getRecord->description}}</textarea>
                          </div>
                    
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"> Update</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('script')

   <script src="{{url('public/lugins/summernote/summernote-bs4.min.js') }}"></script>
   <script type="text/javascript">
        
        $(function () {

    
        $('#compose-textarea').summernote({
            height:200
        });
        $('#getClass').change(function(){
            var class_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ url('admin/ajax_get_subject') }}",
                data : {
                    "_token": "{{ csrf_token() }}",
                   class_id : class_id,
                },
                dataType : "json",
                success: function(data) {
                    $('#getSubject').html(data.success);
                    
                }
            })
        });
    
    });

   </script>
   @endsection
