<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/syncoauthentication.css" />
    <title>Welcome | Sync-o</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
      
        <div class="signin-signup">
        <form action="{{ route('register') }}" method="POST" class="sign-in-form">
          @csrf

          <img src="uploads/project/logo.png" alt="Your Image" style="height: 19.6vw; width: auto; margin-top: -10rem" />

          <h2 class="title2">Register</h2>
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="name" placeholder="Username" value="{{ old('name') }}" />
          </div>
          <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" />
          </div>
          <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="Password" />
          </div>
          <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password_confirmation" placeholder="Confirm Password" />
          </div>
          <input type="submit" value="Register" class="btn solid" />
        </form>

        </div>
      </div>
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <!-- <h3>One of Us?</h3>
            <p>
            Embark on a personalized journey through our website's exclusive content – Log in now to explore and access a diverse range of unique experiences    
          </p> -->
            <a href="{{url('/')}}">
              <button class="btn transparent" id="sign-up-btn">
                Sign In
              </button>
            </a>
          </div>
          <img src="uploads/project/WW.png" alt="Your Image" class="styled-image" /> 
             <button class="btnf1" >
                    Progress at a glance
             </button>
             <button class="btnf2" >
             Keep your project on point
             </button>
             <button class="btnf3" >
             Monitor your group’s progress easily
             </button>
          

          <!-- <img src="{{url('assets/adminassets/dist/img/Syncologo.png')}}" class="image" alt="" /> -->
        </div>
      </div>
    </div>
  </body>
</html>