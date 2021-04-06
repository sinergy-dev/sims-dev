@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
    #tes {
    width: 200px;
    height: 200px;
    }
    .form-horizontal .control-label {
      padding-top: 7px;
      margin-bottom: 0;
      text-align: left;
    }
  </style>
<section class="content-header">
  <h1>
    Profile
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-user"></i> User Profile</a></li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <!-- card foto + profile singkat -->
    <div class="col-md-3">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <div style="align-items: center;">
            @if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-" || Auth::User()->gambar == "")
              <img id="tes" class="profile-user-img img-responsive img-circle" src="{{ asset('image/place_profile_3.png')}}" alt="User profile picture">
            @else
              <img id="tes" class="profile-user-img img-responsive img-circle" src="{{ asset('image/'.$user_profile->gambar)}}" alt="User profile picture" data-toggle="modal" data-target="#pict_profile" onclick="nik_profile('{{$user_profile->nik}}')">
            @endif
          </div>
          <h3 class="profile-username text-center" style="padding-left: 5px;font-family: Arial, Helvetica, sans-serif;">{{ucfirst(strtolower($user_profile->name))}}</h3>
          <!-- <p class="text-muted text-center">Software Engineer</p> -->
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <i class="fa fa-address-card">&nbsp</i><b class="">NIK</b> <b class="pull-right">{{$user_profile->nik}}</b>
            </li>
            <li class="list-group-item">
              <i class="fa fa-envelope">&nbsp</i><b class="">Email</b> <b class="pull-right">{{$user_profile->email}}</b>
            </li>
            <li class="list-group-item">
              <i class="fa fa-phone">&nbsp</i><b class="">Phone</b> <b class="pull-right">+62{{$user_profile->phone}}</b>
            </li>
          </ul>
          <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
        </div>
      </div>
    </div>
    <!-- card profile + upload image -->
    <div class="col-md-9">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active">
            <a data-toggle="tab" href="#profile">Profile</a>
          </li>
          <li>
            <a data-toggle="tab" href="#attachfile">Attach File</a>
          </li>
        </ul>
        <div class="tab-content">
          <!-- tab profile -->
          <div class="active tab-pane" id="profile">
            <div class="post">
              <h4 style="padding-left: 10px"><b>User Profile</b></h4>
              <p style="padding-left: 10px">Make sure your data is correct and up to date</p>
              <form class="form-horizontal" action="{{url('update_profile')}}" enctype="multipart/form-data" method="POST">
                <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden>
                @csrf
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Employee Name</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Type Name" value="{{$user_profile->name}}" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Type Email" value="{{$user_profile->email}}" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Date of Birth</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="date_of_birth" name="date_of_birth" value="{{date('d/m/Y',strtotime($user_profile->date_of_birth))}}" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Date of Entry</label>
                    <div class="col-sm-5">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" id="date_of_entry" name="date_of_entry" value="{{date('d/m/Y',strtotime($user_profile->date_of_entry))}}" readonly>
                      </div>
                    </div>
                    <label class="col-sm-5 control-label">
                      Has served for  
                      @if($user_profile->date_of_entrys > 365)
                        {{ floor($user_profile->date_of_entrys / 365) }} Years {{ round($user_profile->date_of_entrys % 365 / 30 )}} Months
                      @elseif($user_profile->date_of_entrys > 31)
                        {{ floor($user_profile->date_of_entrys / 30)}} Months
                      @else
                        {{$user_profile->date_of_entrys}} Days
                      @endif
                    </label>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-phone"></i>
                        </div>
                        {{-- <input type="text" class="form-control" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask> --}}
                        <!-- <input type="text" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask> -->
                        @if($user_profile->phone != null)
                          <input type="text" class="form-control" id="phone" data-inputmask='"mask": "9999.9999.9999"' data-mask name="phone" placeholder="Type Phone" required>
                        @else
                          <input type="text" class="form-control" id="phone" name="phone" placeholder="Type Phone" value="-" required>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Address</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" rows="3" id="address" name="address" placeholder="Type Your Addres" required>
                      </textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Profile Photo</label>
                    <div class="col-sm-10">
                      <input type="file" id="inputgambar" name="gambar">
                      <span class="help-block"><b>The file size must not exceed 2MB</b></span>  
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">NPWP</label>
                    <div class="col-sm-10">
                      @if($user_profile->no_npwp == "-" || $user_profile->no_npwp == null)
                        <input type="text" class="form-control" rows="3" id="no_npwp" name="no_npwp" placeholder="xx.xxx.xxx.x-xxx.xxx" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask>
                      @else
                        <input type="text" class="form-control" rows="3" id="no_npwp" name="no_npwp" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask readonly>
                      @endif
                    </div>
                  </div> 
                </div>
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Update</button>
                </div>
              </form>
            </div>
          </div>
          <!-- tab attach file -->
          <div class="tab-pane" id="attachfile">
            <div class="tab-content">
              <div class="tab-pane fade in active" id="attachfile">
                <h4 style="padding-left: 10px"><b>Attach File</b></h4>
              </div><br>
              <form action="{{url('update_profile')}}" enctype="multipart/form-data" method="POST">
                <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden>
                @csrf
                <div class="box-body">
                  <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->npwp_file == "-" || $user_profile->npwp_file == null || $user_profile->npwp_file == "")
                        <img src="{{url('img/placeholder100x100.png')}}" id="showgambarnpwp" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @else
                        <img src="{{url('image') . "/" . $user_profile->npwp_file}}" id="showgambarnpwp" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                      </div>
                  </div>
                  <div class="form-group row">
                      <div class="col-md-2">
                        <label style="margin: 12px">NPWP File</label>
                      </div>
                      <div class="col-md-8">
                        <div class="col-md-4">
                          <input type="file" id="inputgambarnpwp" name="npwp_file" value="{{$user_profile->npwp_file}}">
                        </div>
                      </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-8">
                      <img src="{{url('img/placeholder100x100.png')}}" id="showgambarktp" style="max-width: 400px;max-height: 400px;float: left;"/>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label style="margin: 12px">KTP</label>
                    </div>
                    <div class="col-md-8">
                      <div class="col-md-4">
                        <input type="file" id="inputgambarktp" name="ktp_file" value="">
                      </div>
                    </div>
                  </div>
                          <!-- <div class="form-group row">
                            <div class="col-md-6">
                              <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-edit"></i>&nbspUpdate</button>
                              <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Update</button>
                            </div>
                          </div> -->
                </div>
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
@section('script')


<style type="text/css">
  .alert-box {
      color:#555;
      border-radius:10px;
      font-family:Tahoma,Geneva,Arial,sans-serif;font-size:14px;
      padding:10px 36px;
      margin:10px;
  }

  .success {
      background:#e9ffd9 ;
      border:1px solid #a6ca8a;
  }

  .notice {
      background:#e3f7fc;
      border:1px solid #8ed9f6;
  }

  .photos {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    max-width: 300px;
    margin: auto;
    text-align: left;
    font-family: arial; 
    border-radius: 0%;
  }

  .photos-profile{
    position: relative;
    width: 250px;
    height: 250px;
    overflow: hidden;
    border-radius: 50%;
    border: solid white 9px;
  }

  .photos-profile img{
    width: 50%;
    height: auto;

  }


  .margin-top-profile{
    margin-top: 5%;
  }

  .fa-twitter.icon {
  /*  background: #55ACEE;*/
    color: #55ACEE;
  }
  .fa-linkedin.icon {
    /*background: #007bb5;*/
    color: #007bb5;
  }
  .fa-instagram.icon {
    /*background: #e6005c;*/
    color: #e6005c;
  }
  .fa-facebook.icon {
 /*   background: #3333ff;*/
    color: #3333ff;
  }
  .icon {
    padding: 20px;
    font-size: 25px;
    width: 60px;
    height: 60px;
    text-align: center;
    text-decoration: none;
    margin: 5px 2px;
    border-radius: 50%;
  }
  .profile h6{
      position: relative;
      padding: 6px 12px 6px 0px;
      font-size: 16px;
      
  }
  .profile h6:nth-child(odd){
   
  }
  div div ol li a{
    font-size: 14px;
  }

  div div i{
    font-size: 14px;
  }

  .profileInput input[type=number]{
    padding-left:45px;
  }

  input[type=number]:focus{
    border-color:dodgerBlue;
    box-shadow:0 0 8px 0 dodgerBlue;
  }

 .profileInput.inputIconBg input[type=number]:focus + i{
    color:#fff;
    background-color:dodgerBlue;
  }

 .profileInput.inputIconBg i{
    background-color:#aaa;
    color:#fff;
    padding:8px 7px;
    border-radius:4px 0 0 4px;
  }

 .profileInput{
    position:relative;
  }

 .profileInput i{
    position:absolute;
    left:0;
    top:12px;
    padding:8px 12px;
    color:#aaa;
    transition:.3s;
  }
  .shape {
  stroke-dasharray: 30 30;
  stroke-dashoffset: -100;
  stroke-width: 8px;
  fill: transparent;
  stroke: #444 !important;
  border-bottom: 5px solid black;
  transition: stroke-width 1s, stroke-dashoffset 1s, stroke-dasharray 1s;
}

</style>
{{-- <script src="../../plugins/input-mask/jquery.inputmask.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.js" integrity="sha512-SSQo56LrrC0adA0IJk1GONb6LLfKM6+gqBTAGgWNO8DIxHiy0ARRIztRWVK6hGnrlYWOFKEbSLQuONZDtJFK0Q==" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("[data-mask]").inputmask();

    $("#phone").val("{{'0' . $user_profile->phone}}")
    $("#address").val("{{$user_profile->address}}")
    $("#no_npwp").val("{{$user_profile->no_npwp}}")


    if (window.location.hash == '#changePassword') {
      $("#modalEdit").modal("show")
    }

    $('#date_of_birth').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
    })
  })

  $('#inputgambar').on('change', function() { 

      const size = (this.files[0].size / 1024 / 1024).toFixed(2); 

      if (size > 2) { 
          alert("The file size must not exceed 2 MB"); 
      } else { 
          $("#output").html('<b>This file size is: ' + size + ' MB</b>'); 
      } 
  }); 

  $(".btn-edit").click(function(){
  	$("#modalEdit").modal("show");
  	console.log('coba');
  });

  function readURL(input) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
              $('#tes').attr('src', e.target.result);
          }

          reader.readAsDataURL(input.files[0]);
      }
  }

  function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#showgambarnpwp').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

  $("#inputgambar").change(function () {
      readURL(this);
  });


  $("#inputgambarnpwp").change(function () {
      readURL(this);
    });


  function nik_profile(nik){
    $("#nik_profile").val(nik);
    $("#pick_nik").val(nik);
  }

  $("#alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#alert").slideUp(300);
  }); 

</script>
@endsection