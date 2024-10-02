@extends('layout.app')
  @section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1>Edit Student</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <!-- form start -->
              <form action="{{ route('student.edit', ['id' => $getStudent->id]) }}" method="POST">
              {{csrf_field()}}
                <div class="card-body">
                    <div class = "row">
                        
                        <div class="form-group col-md-6">
                        <div class="form-group">
                    <label>Name<span style="color: red;">*</span></label>
                    <input type="name" class="form-control" name="name" value = "{{old('email',  $getStudent->name)}}"  placeholder="Name" required>
                    <div style="color:red">{{$errors->first('name')}}</div>
                  </div>
                        </div>
                    </div>
                    <hr />
                  <div class="form-group">
                    <label>Email address<span style="color: red;">*</span></label>
                    <input type="email" class="form-control" name="email" value = "{{old('email',  $getStudent->email)}}"  placeholder="Email" required>
                    <div style="color:red">{{$errors->first('email')}}</div>
                  </div>
                  <div class="form-group">
                    <label>Password<span style="color: red;"></span></label>
                    <input type="text" class="form-control" name="password" placeholder="Password">
                    <p>Do you want to change your password? Add a password. </p>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>


  @endsection