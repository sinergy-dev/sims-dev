<!DOCTYPE html>
<html lang="en" class="fullscreen-bg">

<head>
  <title>App Sinergy</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <!-- VENDOR CSS -->
  <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('/vendor2/font-awesome/css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('/vendor2/linearicons/style.css')}}">
  <!-- MAIN CSS -->
  <link rel="stylesheet" href="{{asset('/css/main.css')}}">
  <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
  <link rel="stylesheet" href="{{asset('/css/demo.css')}}">
  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
  <!-- ICONS -->
  <link rel="apple-touch-icon" href="{{asset('/img/logopng.png')}}">
  <link rel="icon" type="image/png" href="{{asset('/img/logopng.png')}}">
</head>

<body class="bg-wall">
  <!-- WRAPPER -->
  <div id="wrapper">
    <div class="vertical-align-wrap">
      <div class="vertical-align-middle">
        <div class="auth-box ">
          <div class="left">
            <div class="content">
              <div class="header">
                <div class="logo text-center"><img src="{{asset('/img/siplogin.png')}}" width="123" height="60" alt="Klorofil Logo"></div>
                <p class="lead">Sinergy Integrated Management System</p>
              </div>
              <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                  {{ csrf_field() }}
                  @if(session()->has('message'))
                      <div class="alert alert-warning notification-bar" id="alert">
                          {{ session()->get('message') }}
                      </div>
                  @endif
                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                      <label for="email" class="control-label sr-only">Email</label>
                      <div class="col-md-12">
                          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>
                  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                      <label for="password" class="control-label sr-only">Password</label>
                      <div class="col-md-12">
                          <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                          @if ($errors->has('password'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>
                  <div class="form-group">
                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary btn-block">
                            Login
                          </button>
                      </div>
                      <div class="col-md-12" style="margin-top: 5px">
                        <a class="pull-left" href="{{ route('password.request') }}" target="_blank">
                          <i class="fa fa-lock"></i>
                            Forgot Password?
                        </a>
                      </div>
                  </div>
              </form>
            </div>
          </div>
          <div class="right">
            <div class="overlay" id="overlay">
              <div class="slideshow">
                 <div class="slide_three slide">
                   <img src="{{asset('/img/futuredatacenter.png')}}" width="100%" height="100%" alt="Klorofil Logo">
                </div>
                <div class="slide_one slide">
                   <img src="{{asset('/img/data-center.jpg')}}" width="100%" height="100%" alt="Klorofil Logo">
                </div>
                <div class="slide_two slide">
                   <img src="{{asset('/img/data-center2.jpg')}}" width="100%" height="100%" alt="Klorofil Logo">
                </div>
                <div class="slide_one slide">
                   <img src="{{asset('/img/data-center.jpg')}}" width="100%" height="100%" alt="Klorofil Logo">
                </div>
              </div>
            </div>
            <div class="content-text text" style="background-color: #17325e ;opacity: 0.8">
              <h1 class="heading">Sinergy Integrated Management System</h1>
              <p style="font-size: 12px;">This website that was made by the PT Sinergy Informasi Pratama (“SIP” hereinafter) is in the property right of SIP. If you are not a member of registered user or having the authority for the website, you must not connect to this web page.</p>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
  </div>
  <!-- END WRAPPER -->
</body>
</html>

<style type="text/css">
	body {
    background-image: url("../img/bg4.jpg");

    height: 100%;

    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }
</style>