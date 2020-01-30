 <link rel="icon" type="image/png" href="../img/logopng.png">
 <title>SIP - Sales App</title>
 <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
 <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
 <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">

<body class="bg-pass">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Hello {{Auth::user()->name}}</div>
      <div class="card-body">
           @if (session('error'))
              <div class="alert alert-danger">
                  {{ session('error') }}
              </div>
            @endif
              @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
            @endif
        <form method="POST" action="{{ url('changePassword') }}">
             {{ csrf_field() }}
          <div class="form-group {{ $errors->has('current-password') ? ' has-error' : '' }}">
          	<label>Password</label>
            <input class="form-control" id="current-password" name="current-password" type="Password"  placeholder="Enter Your Current Password" >

             @if ($errors->has('current-password'))
               <span class="help-block">
                 <strong>{{ $errors->first('current-password') }}</strong>
               </span>
             @endif
          </div>
           <div class="form-group {{ $errors->has('new-password') ? ' has-error' : '' }}">
          	<label>New Password</label>
            <input class="form-control" id="new-password" name="new-password" type="Password" placeholder="Enter New Password">
             @if ($errors->has('new-password'))
              <span class="help-block">
                <strong>{{ $errors->first('new-password') }}</strong>
              </span>
             @endif
             </div>

            <div class="form-group">
              <label>Confirm Password</label>
            <input class="form-control" id="new-password-confirm" name="new-password-confirm" type="Password" placeholder="Enter Confirm Password">
          </div>
          </div>
        <div class="text-center">
          <button class="btn btn-sm btn-primary btn-block" type="submit">Change Password</button>
          
        </div>
      </form>
      </div>
    </div>
  </div>

  <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>



<style type="text/css">
  .bg-pass
  {
    background-image: url("img/changepass.jpg");
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>
