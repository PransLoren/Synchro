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
            <h3>Sign In</h3>
            <p>
              Palitan mo met
            </p>
            <a href="{{ url ('/')}}">
              <button class="btn transparent" id="sign-up-btn">
                Sign In
              </button>
            </a>
          </div>
          <img src="#" class="image" alt="" />
        </div>
      </div>
    </div>
  </body>
</html>