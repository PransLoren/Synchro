<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{url ('assets/css/syncoauthentication.css')}}" />
    <title>Forgot Password | Sync-o</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="" class="sign-in-form" method="POST">
          {{csrf_field()}}
          @include ('message')
          <img src="uploads/project/logo.png" alt="Your Image" style="height: 19.6vw; width: auto; margin-top: -13rem" />
            <h2 class="title">Forgot Password</h2>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="text" placeholder="Email" name="email" required/>
            </div>
            <input type="submit" value="Send" class="btn solid" />
          </form>
        </div>
      </div>
      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <!-- <h3>Sign In</h3>
             <p>
              Lorem ipsum, dolor sit amet consectetur adipisicing elit. Debitis,
              ex ratione. Aliquid!
            </p> --> 
            <a href="{{ url ('/')}}">
              <button class="btn transparent" id="sign-up-btn">
                Sign In
              </button>
            </a>
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
          </div>
          <img src="#" class="image" alt="" />
        </div>
      </div>
    </div>
  </body>
</html>