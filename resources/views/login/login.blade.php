@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin - Start Bootstrap Template</title>
  <!-- Bootstrap core CSS-->
  <link href="{{asset('/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{asset('/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="{{asset('/css/sb-admin.css')}}" rel="stylesheet">
</head>

<body class="">
  <link rel="stylesheet" type="text/css" href="style.css">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="panel-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('custom.attempt') }}">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-12 control-label text-center">E-Mail Address</label>
                <div class="col-md-12">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-12 control-label text-center">Password</label>
                <div class="col-md-12">
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-grouSp">
                <div class="col-md-6 col-md-offset-4">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
            </div>
        </form>
      </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{asset('/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
</body>

</html>
@endsection