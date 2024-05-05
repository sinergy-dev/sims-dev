@extends('template.main')
@section('head_css')
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
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

    .entry {
        transition: transform .2s;
        margin: 0 auto;
    }

    .entry:hover {
        -ms-transform: scale(1.5); /* IE 9 */
        -webkit-transform: scale(1.5); /* Safari 3-8 */
        transform: scale(1.5); 
    }
    
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

    /* The message box is shown when the user clicks on the password field */
    #message {
      display:none;
      background: #f1f1f1;
      color: #000;
      position: relative;
      padding: 20px;
      margin-top: 10px;
    }

    #message h5 {
      padding: 35px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
      color: green;
    }

    .valid:before {
      position: relative;
      left: -2px;
      content: "✔";
    }

    /* Add a red text color and an "x" when the requirements are wrong */
    .invalid {
      color: red;
    }

    .invalid:before {
      position: relative;
      left: -2px;
      content: "✖";
    }
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    Profile
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-user"></i> User Profile</a></li>
  </ol>
</section>

<section class="content">

  @if (session('alert'))
  <div class="alert alert-danger" id="alert">
    {{ session('alert') }}
  </div>
  @endif
  @if($errors->any())
  <div class="alert alert-danger" id="alert2">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </div>
  @endif
  @if (session('success'))
    <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}</div>
  @endif
  <div class="row">
    <!-- card foto + profile singkat -->
    <div class="col-md-3">
      <div class="box box-primary">
        <div class="box-body box-profile">
          <div style="align-items: center;">
            @if(Auth::User()->avatar != NULL)
              <img id="tes" class="profile-user-img img-responsive img-circle" src="{{Auth::User()->avatar}}" alt="User profile picture">
            @else
              @if(Auth::User()->gambar == NULL || Auth::User()->gambar == "-" || Auth::User()->gambar == "")
                <img id="tes" class="profile-user-img img-responsive img-circle" src="{{ asset('image/place_profile_3.png')}}" alt="User profile picture">
              @else
                <img id="tes" class="profile-user-img img-responsive img-circle" src="{{ asset('image/'.$user_profile->gambar)}}" alt="User profile picture" data-toggle="modal" data-target="#pict_profile" onclick="nik_profile('{{$user_profile->nik}}')">
              @endif
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

          <!-- <div class="row" style="padding-left:25px">
            <button class="btn btn-md btn-primary btn-edit" type="button" style="width: 150px"><i class="fa fa-key"></i> Change Password</button>
            <a href="{{url('show_cuti')}}" style="margin-left: 10px"><button class="btn btn-md btn-success" style="width: 150px"><i class="fa fa-user"></i> Leaving Permite</button></a>
          </div> -->
          <a class="btn btn-primary btn-block btn-edit" type="button"><b>Change Password</b></a>
          <a href="{{url('show_cuti')}}" class="btn btn-success btn-block" type="button"><b>Leaving Permit</b></a>
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
                          <input type="text" class="form-control" id="phone" data-inputmask='"mask": "9999.9999.99999"' data-mask name="phone" placeholder="Type Phone" required>
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
                        <input type="text" class="form-control" rows="3" id="no_npwp" name="no_npwp" data-inputmask='"mask": "99.999.999.9-999.999"' data-mask>
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
              <form action="{{url('update_profile_npwp')}}" enctype="multipart/form-data" method="POST">
                <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden>
                @csrf
                <div class="form-group hidden">
                  <label class="col-sm-2 control-label">Employee Name</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Type Name" value="{{$user_profile->name}}">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">NPWP File</h3>
                        <button type="button" class="btn btn-sm btnRotateNpwp" style="color:black;float: right;background-color: transparent;" onclick="rotateImage('npwp','showgambarnpwp')" name=""><i class="fa  fa-rotate-right"></i></button>
                      </div>
                      <div class="box-body">
                        @if($user_profile->npwp_file == "-" || $user_profile->npwp_file == "" || $user_profile->npwp_file == null)
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarnpwp" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @else
                          <img class="entry" src="{{url('image') . "/" . $user_profile->npwp_file}}" id="showgambarnpwp" style="max-width: 200px;max-height: 200px;border-radius:5px;margin:0px auto;display:block"/>
                        @endif
                      </div>
                      <div class="box-footer">
                          <input type="file" id="inputgambarnpwp" name="npwp_file" value="{{$user_profile->npwp_file}}">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">KTP File</h3>
                        <button type="button" class="btn btn-sm btnRotateKtp" style="color:black;float: right;background-color: transparent;" onclick="rotateImage('npwp','showgambarktp')" name=""><i class="fa  fa-rotate-right"></i></button>
                      </div>
                      <div class="box-body">
                        @if($user_profile->ktp_file == "-" || $user_profile->ktp_file == null || $user_profile->ktp_file == "")
                          <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarktp" style="max-width: 200px;max-height: 200px; align-items: center;"/>
                        @else
                          <img class="entry" src="{{url('image') . "/" . $user_profile->ktp_file}}" id="showgambarktp"  style="max-width: 200px;max-height: 200px;border-radius:5px;margin:0px auto;display:block"/>
                        @endif
                      </div>
                      <div class="box-footer">
                          <input type="file" id="inputgambarktp" name="ktp_file" value="{{$user_profile->ktp_file}}">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">BPJS Ketenagakerjaan File</h3>
                      </div>
                      <div class="box-body">
                        @if($user_profile->bpjs_ket_file == "-" || $user_profile->bpjs_ket_file == "" || $user_profile->bpjs_ket_file == null)
                          <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_ket" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @else
                          <img class="entry" src="{{url('image') . "/" . $user_profile->bpjs_ket_file}}" id="showgambarbpjs_ket" style="max-width: 200px;max-height: 200px;float: left;border-radius:5px"/>
                        @endif
                      </div>
                      <div class="box-footer" >
                          <input type="file" id="inputgambarbpjs_ket" name="bpjs_ket_file" value="{{$user_profile->bpjs_ket}}">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">BPJS Kesehatan File</h3>
                      </div>
                      <div class="box-body">
                        @if($user_profile->bpjs_kes_file == "-" || $user_profile->bpjs_kes_file == "" || $user_profile->bpjs_kes_file == null)
                          <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_kes" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @else
                          <img class="entry" src="{{url('image') . "/" . $user_profile->bpjs_kes_file}}" id="showgambarbpjs_kes" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @endif
                      </div>
                      <div class="box-footer">
                          <input type="file" id="inputgambarbpjs_kes" name="bpjs_kes_file" value="{{$user_profile->bpjs_kes}}">
                      </div>
                    </div>
                  </div>

                  <!-- <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">Sign File</h3>
                      </div>
                      <div class="box-body">
                        @if($user_profile->ttd_digital == null)
                          <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarsign" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @else
                          <img class="entry" src="{{url($user_profile->ttd_digital)}}" id="showgambarsign" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @endif
                      </div>
                      <div class="box-footer">
                          <input type="file" id="inputSign" name="inputSign">
                      </div>
                    </div>
                  </div> -->

                  <div class="col-md-4">
                    <div class="box box-primary" style="background-color: #F7F7F7;">
                      <div class="box-header">
                        <h3 class="box-title">Sign File</h3>
                      </div>
                      <div class="box-body">
                        @if($user_profile->ttd == null)
                          <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarsign" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @else
                          <img class="entry" src="{{url($user_profile->ttd)}}" id="showgambarsign" style="max-width: 200px;max-height: 200px;float: left;"/>
                        @endif
                      </div>
                      <div class="box-footer">
                          <input type="file" id="inputSign" name="inputSign">
                      </div>
                    </div>
                  </div>
                </div>
                

                <!-- <div class="form-group row">
                  <div class="col-md-6">
                    <div>
                      @if($user_profile->ttd == null)
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_ket" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url($user_profile->ttd)}}" id="showgambarbpjs_ket" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                    </div>
                    <div>
                      <div style="position: absolute; bottom: 8px; left: 16px;">
                        <label>Sign File</label>
                      </div>
                      <div style="position: absolute; bottom: 8px; right: 2px;">
                        <input type="file" id="inputSign" name="inputSign" value="{{$user_profile->ttd}}">
                      </div>
                    </div>
                  </div>

                </div> -->





                <div class="box-body">
                  <!-- <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->npwp_file == "-" || $user_profile->npwp_file == null || $user_profile->npwp_file == "")
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarnpwp" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url('image') . "/" . $user_profile->npwp_file}}" id="showgambarnpwp" style="max-width: 400px;max-height: 400px;float: left;"/>
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
                  </div> -->
                  
                  <!-- <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->ktp_file == "-" || $user_profile->ktp_file == null || $user_profile->ktp_file == "")
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarktp" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url('image') . "/" . $user_profile->ktp_file}}" id="showgambarktp" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                    </div>
                  </div>

                  <center>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label style="margin: 12px">KTP</label>
                    </div>
                    <div class="col-md-8">
                      <div class="col-md-4">
                        <input type="file" id="inputgambarktp" name="ktp_file" value="{{$user_profile->ktp_file}}">
                      </div>
                    </div>
                  </div>
                  </center>

                  <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->bpjs_kes == "-" || $user_profile->bpjs_kes == null || $user_profile->bpjs_kes == "")
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_kes" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url('image') . "/" . $user_profile->bpjs_kes}}" id="showgambarbpjs_kes" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                    </div>
                  </div>

                  <center>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label style="margin: 12px">BPJS Kesehatan</label>
                    </div>
                    <div class="col-md-8">
                      <div class="col-md-4">
                        <input type="file" id="inputgambarbpjs_kes" name="bpjs_kes" value="{{$user_profile->bpjs_kes}}">
                      </div>
                    </div>
                  </div>
                  </center>

                  <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->bpjs_ket == "-" || $user_profile->bpjs_ket == null || $user_profile->bpjs_ket == "")
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_ket" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url('image') . "/" . $user_profile->bpjs_ket}}" id="showgambarbpjs_ket" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                    </div>
                  </div>

                  <center>
                  <div class="form-group row">
                    <div class="col-md-2">
                      <label style="margin: 12px">BPJS Ketenagakerjaan</label>
                    </div>
                    <div class="col-md-8">
                      <div class="col-md-4">
                        <input type="file" id="inputgambarbpjs_ket" name="bpjs_ket" value="{{$user_profile->bpjs_ket}}">
                      </div>
                    </div>
                  </div>
                  </center> -->

                  <!-- <div class="form-group row">
                    <div class="col-md-8">
                      @if($user_profile->bpjs_ket == "-" || $user_profile->bpjs_ket == null || $user_profile->bpjs_ket == "")
                        <img class="entry" src="{{url('img/img_nf.png')}}" id="showgambarbpjs_ket" style="max-width: 300px;max-height: 300px;float: left;"/>
                      @else
                        <img class="entry" src="{{url('image') . "/" . $user_profile->bpjs_ket}}" id="showgambarbpjs_ket" style="max-width: 400px;max-height: 400px;float: left;"/>
                      @endif
                    </div>
                  </div>

                  <center>
                    <div class="form-group row">
                      <div class="col-md-2">
                        <label style="margin: 12px">Sign</label>
                      </div>
                      <div class="col-md-8">
                        <div class="col-md-4">
                          <input type="file" id="inputgambarbpjs_ket" name="bpjs_ket" value="{{$user_profile->bpjs_ket}}">
                        </div>
                      </div>
                    </div>
                  </center> -->

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

  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Profile</h4>
        </div>
        <div class="modal-body">
          <!-- <form method="POST" enctype="multipart/form-data" action="{{url('changePassword')}}" id="modalEditProfile" name="modalEditProfile">
            @csrf -->
            <input type="text" name="nik_profile" id="nik_change_password" value="{{$user_profile->nik}}" hidden> 

            <div class="form-group">
              <label>Current Password</label>
              <div class="input-group">
                <input class="form-control" id="current-password" name="current-password" type="Password" required  placeholder="Enter Your Current Password">
                <div class="input-group-addon">
                  <i onclick="eyeEnableOrDisable('current-password','toggle1')" toggle="#password-field" class="fa fa-fw fa-eye field-icon" id="toggle1"></i>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>New Password</label>
              <div class="input-group">
                <input class="form-control" id="new-password" name="password" type="Password" placeholder="Enter New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required onkeypress="psw()">
                <div class="input-group-addon">
                  <i onclick="eyeEnableOrDisable('new-password','toggle2')" toggle="#password-field" class="fa fa-fw fa-eye field-icon" id="toggle2"></i>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Confirm Password</label>
              <div class="input-group">
                <input class="form-control" id="new-password-confirm" name="password_confirmation" required type="Password" placeholder="Enter Confirm Password">
                <div class="input-group-addon">
                  <i onclick="eyeEnableOrDisable('new-password-confirm','toggle3')" toggle="#password-field" class="fa fa-fw fa-eye field-icon" id="toggle3"></i>
                </div>
              </div>
            </div> 

            <div id="message">
              <h4>Password must contain the following:</h4>
              <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
              <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
              <p id="number" class="invalid">A <b>number</b></p>
              <p id="length" class="invalid">Minimum <b>8 characters</b></p>
              <!-- <p id="char" class="invalid">Minimum <b>1 special character</b></p> -->
            </div>

            <div class="modal-footer">
              <button class="btn btn-default" type="button" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary" id="change_password"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        <!-- </form> -->
        </div>
      </div>
    </div>
  </div>
  </div>
</section>

@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.js" integrity="sha512-SSQo56LrrC0adA0IJk1GONb6LLfKM6+gqBTAGgWNO8DIxHiy0ARRIztRWVK6hGnrlYWOFKEbSLQuONZDtJFK0Q==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    if ($("#showgambarnpwp").attr("src").split("/")[4] == "") {
      $(".btnRotateNpwp").prop("disabled",true)
    }else{
      $(".btnRotateNpwp").prop("disabled",false)
    }

    if ($("#showgambarktp").attr("src").split("/")[4] == "") {
      $(".btnRotateKtp").prop("disabled",true)
    }else{
      $(".btnRotateKtp").prop("disabled",false)
    }


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

  let degree = 90
  let count = 0
  function rotateImage(checkFile,id) {
    count++
    console.log(count)
    if (count == 0) {
      degree += 0
    }else if (count == 1) {
      degree += 90
    }else if (count == 2) {
      degree += 90
    }else if (count == 3) {
      degree += 90
      count = 0
    }
    $('#'+id).animate({  transform: degree }, {
      step: function(now,fx) {
          $(this).css({
              '-webkit-transform':'rotate('+now+'deg)', 
              '-moz-transform':'rotate('+now+'deg)',
              'transform':'rotate('+now+'deg)'
          });
      }
      });
  }

  $('#inputgambar').on('change', function() { 

      const size = (this.files[0].size / 1024 / 1024).toFixed(2); 

      if (size > 2) { 
          alert("The file size must not exceed 2 MB"); 
      } else { 
          $("#output").html('<b>This file size is: ' + size + ' MB</b>'); 
      } 
  }); 


  function eyeEnableOrDisable(value,id) 
  {
    var x = document.getElementById(value); //getting the password field element
    var y = document.getElementById(id); //getting the eye button element
    if (x.type === "password") 
    {
      x.type = "text";
      y.classList.remove("fa-eye");
      y.classList.add("fa-eye-slash");
    } 
    else 
    {
      x.type = "password";
      y.classList.remove("fa-eye-slash");
      y.classList.add("fa-eye");
    }
  }

  $("#toggle3").click(function() {
      $(this).toggleClass("fa-eye fa-eye-slash");
      var z = document.getElementById("new-password-confirm");

      if (z.type === "password") {
          z.type = "text";
      } else {
          z.type = "password";
      }
  });

  $(".btn-edit").click(function(){
  	$("#modalEdit").modal("show");
  });

  function psw() {
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");
    var myInput = document.getElementById("new-password");
    // var char = document.getElementById("char");

    // myInput.onfocus = function() {
      document.getElementById("message").style.display = "block";
    // }

    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
      // Validate lowercase letters
      var lowerCaseLetters = /[a-z]/g;
      if(myInput.value.match(lowerCaseLetters)) {  
        letter.classList.remove("invalid");
        letter.classList.add("valid");
      } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
      }

      // var charLetters = /[!$#%]/g;
      // if(myInput.value.match(charLetters)) {  
      //   char.classList.remove("invalid");
      //   char.classList.add("valid");
      // } else {
      //   char.classList.remove("valid");
      //   char.classList.add("invalid");
      // }
      
      // Validate capital letters
      var upperCaseLetters = /[A-Z]/g;
      if(myInput.value.match(upperCaseLetters)) {  
        capital.classList.remove("invalid");
        capital.classList.add("valid");
      } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
      }

      // Validate numbers
      var numbers = /[0-9]/g;
      if(myInput.value.match(numbers)) {  
        number.classList.remove("invalid");
        number.classList.add("valid");
      } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
      }
      
      // Validate length
      if(myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
      } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
      }  
    }
  }

  $("#change_password").click(function(){
    if($("#letter").hasClass("invalid") ||
      $("#capital").hasClass("invalid") ||
      $("#number").hasClass("invalid") ||
      $("#length").hasClass("invalid"))
      // ||
      // $("#char").hasClass("invalid"))
    {
      
      Swal.fire(
        'Oops',
        "Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters",
        'error'
      ).then((result2) => {
        if (result2.value) {
          // location.reload()
          $("#new-password").val('');
          $("#current-password").val('');
          $("#new-password-confirm").val('');
          var letter = document.getElementById("letter");
          var capital = document.getElementById("capital");
          var number = document.getElementById("number");
          var length = document.getElementById("length");
          // var char = document.getElementById("char");

          letter.classList.add("invalid");
          capital.classList.add("invalid");
          number.classList.add("invalid");
          length.classList.add("invalid");
          // char.classList.add("invalid");
          
          $("#new-password").parent().addClass('has-error')
          $("#new-password-confirm").parent().addClass('has-error')
        }
      })
    } else {
      var swalAccept = Swal.fire({
        title: 'Change Password',
        text: "Are you sure?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: 'Please Wait..!',
            text: "It's updating..",
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            customClass: {
              popup: 'border-radius-0',
            },
            onOpen: () => {
              Swal.showLoading()
            }
          })
          $.ajax({
            type:"POST",
            url:"{{url('changePassword')}}",
            data:{
              "_token": "{{ csrf_token() }}",
              nik_profile: $("#nik_change_password").val(),
              current_password: $("#current-password").val(),
              password: $("#new-password").val(),
            },
            beforeSend:function() {
              $("#new-password").parent().removeClass('has-error')
              $("#new-password-confirm").parent().removeClass('has-error')
              $("#current-password").parent().removeClass('has-error')
            },
            success: function(result){
              Swal.showLoading()
              Swal.fire(
                'Successfully!',
                'success'
              ).then((result) => {
                if (result.value) {
                  // location.reload()
                  event.preventDefault();
                  document.getElementById('logout-form').submit();
                }
              })
            },
            error: function(result) {
              // console.log(result.responseText)
              Swal.showLoading()
              Swal.fire(
                'Oops',
                result.responseText,
                'error'
              ).then((result2) => {
                if (result2.value) {
                  // location.reload()
                  $("#new-password").val('');
                  $("#current-password").val('');
                  $("#new-password-confirm").val('');
                  var letter = document.getElementById("letter");
                  var capital = document.getElementById("capital");
                  var number = document.getElementById("number");
                  var length = document.getElementById("length");
                  // var char = document.getElementById("char");

                  letter.classList.add("invalid");
                  capital.classList.add("invalid");
                  number.classList.add("invalid");
                  length.classList.add("invalid");
                  // char.classList.add("invalid");

                  if(result.responseText == 'Your current password does not matches with the password you provided. Please try again.'){
                    $("#current-password").parent().addClass('has-error')
                  } else {
                    $("#new-password").parent().addClass('has-error')
                    $("#new-password-confirm").parent().addClass('has-error')
                  }
                }
              })
            }
          }) 
        }        
      })

    }
  })

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
        console.log(e)
        if (input.id == "inputgambarktp") {
          $('#showgambarktp').attr('src', e.target.result);
        }else if (input.id == "inputgambarnpwp") {
          $('#showgambarnpwp').attr('src', e.target.result);
        }else if (input.id == "inputSign") {
          $('#showgambarsign').attr('src', e.target.result);
        }else if (input.id == "inputgambarbpjs_ket") {
          $('#showgambarbpjs_ket').attr('src', e.target.result);
        }else{
          $('#showgambarbpjs_kes').attr('src', e.target.result);
        }
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

  $("#inputgambarktp").change(function () {
      readURL(this);
    });

  $("#inputgambarbpjs_kes").change(function () {
      readURL(this);
    });

  $("#inputgambarbpjs_ket").change(function () {
      readURL(this);
    });


  function nik_profile(nik){
    $("#nik_profile").val(nik);
    $("#pick_nik").val(nik);
  }

  $("#alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#alert").slideUp(300);
  });

  $("#alert2").show(); 

  $(".notification-bar").fadeTo(2000, 500).slideUp(500, function(){
    $(".notification-bar").slideUp(300);
  }); 

</script>
@endsection