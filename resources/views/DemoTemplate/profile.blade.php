@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript">

      function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#tes').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#inputgambar").change(function () {
        readURL(this);
    });


    function nik_profile(nik){
      $("#nik_profile").val(nik);
      $("#pick_nik").val(nik);
    }
</script>

@stop

@extends('template.template')
@section('content')
<style type="text/css">
  .photos {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    max-width: 300px;
    margin: auto;
    text-align: left;
    font-family: arial; 
    border-radius: 0%;
  }

  .image{
    text-align: left;
    vertical-align: middle;
    text-decoration-style:center;
    margin-left: 20%;
  }

  .margin-top-profile{
    margin-top: 5%;
  }
  .btn-primary-profile{
      -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857;
    margin-bottom: 0;
    padding: 6px 12px;
    text-align: center;
    touch-action: manipulation;
    vertical-align: middle;
    max-width: 100%;
    width: 260px;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }
  .btn-warning-profile{
    -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857;
    margin-bottom: 0;
    padding: 6px 12px;
    text-align: center;
    touch-action: manipulation;
    vertical-align: middle;
    max-width: 100%;
    background-color: #ffc107;
    border-color: #ffc107;
    width: 260px;
    color: #fff;
  }
  .btn-success-profile{
    -moz-user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.42857;
    margin-bottom: 0;
    padding: 6px 12px;
    text-align: center;
    touch-action: manipulation;
    vertical-align: middle;
    max-width: 100%;
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
    width: 250px;
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
      padding: 12px 8px 12px 40px;
      background: #f9f9f9;
  }
  .profile h6:nth-child(odd){
    background: #f9f9f9;
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
    top:62px;
    padding:9px 8px;
    color:#aaa;
    transition:.3s;
  }
  .shape {
  stroke-dasharray: 30 30;
  stroke-dashoffset: -100;
  stroke-width: 8px;
  fill: transparent;
  stroke: #ffc107!important;
  border-bottom: 5px solid black;
  transition: stroke-width 1s, stroke-dashoffset 1s, stroke-dasharray 1s;
}
.svg-wrapper:hover .shape {
  stroke-width: 2px;
  stroke-dashoffset: 0;
  stroke-dasharray: 760;
}
.logos a:hover{
  color: grey!important;
}
/*.fa-ubah{
  -moz-user-select: none;
  background-image: none;
  display: inline-block;
  text-align: center;
  touch-action: manipulation;
  max-width: 100%;
  color: white;
  font-size: 25px;
  background: #007bb5;
  padding: 15px;
  border-radius: 50%;
  border-style: solid;
  transform: rotate(-22deg) translate(7em) rotate(-340deg);
}*/

img{
  border-radius: 50%;
  max-width: 100%;
}
</style>

<div class="content-wrapper">
    <div class="container-fluid">

    <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">User Profile</a>
        </li>
    </ol>
  
  <div class="row">
    <div class="col-md-3">
        <div class="photos">
            @if(Auth::User()->gambar == NULL)
              <img src="https://www.mycustomer.com/sites/all/modules/custom/sm_pp_user_profile/img/default-user.png" alt="Yuki" style="width: 100%;position: relative;">
            @elseif(Auth::User()->gambar != NULL)
              <img src="{{ asset('image/'.$user_profile->gambar)}}" alt="Avatar" style="width: 100%;" data-toggle="modal" data-target="#pict_profile" onclick="nik_profile('{{$user_profile->nik}}')">
            @endif
        </div><br>
        <div class="photos">
          <table>
            <tr>
              <td><h4><b>{{$user_profile->name}}</b></h4></td>
            </tr>
            @if(Auth::User()->id_division == 'SALES' && Auth::User()->id_territory != ''  || Auth::User()->id_division == 'TECHNICAL' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_territory == 'DPG') 
            <tr>
              <td><b>{{$user_profile->name_division}}</b>&nbsp<b>{{$user_profile->name_territory}}</b></td>
            </tr>
            @elseif(Auth::User()->id_division == 'TECHNICAL')
            <tr><td><b>{{$user_profile->name_division}}</b></td></tr>
            @elseif(Auth::User()->id_division == 'TECHNICAL PRESALES')
            <tr><td><b>{{$user_profile->id_division}}</b></td></tr>
            @elseif(Auth::User()->id_division == 'PMO')
            <tr><td><b>{{$user_profile->name_division}}</b></td></tr>
            @elseif(Auth::User()->id_division == 'FINANCE')
            <tr><td><b>{{$user_profile->name_division}}</b></td></tr>
            @elseif(Auth::User()->id_division == 'SALES' && Auth::User()->id_teritory == '')
            <tr>
              <td><b>{{$user_profile->name_division}}</b>&nbsp<b>MSP</b></td>
            </tr>
            @endif
            <tr><td><b>{{$user_profile->name_position}}</b></td></tr>
          </table>
        </div><br>
        
        <div class="form-group" style="text-align: center;">
          <button class="btn btn-primary-profile" data-target="#modalEdit" data-toggle="modal">Edit My Profile</button><br>
          <a href="{{url('/profile')}}"><button class="btn btn-warning-profile margin-top">Change Password</button></a>
        </div>
      </div>
     

      <div class="col-md-6">
      <div class="form-group">
          <div class="card mb-3">
              <div class="card-header">
                About Me
              </div>
              <div class="card-body profile">
                <h6><i class="fa fa-address-card"></i><b>&nbspNIK : {{$user_profile->nik}} </b></h6>
                <h6><i class="fa fa-user"></i><b>&nbspName : {{$user_profile->name}} </b></h6>
                <h6><i class="fa fa-envelope"></i><b>&nbspEmail : {{$user_profile->email}} </b></h6>
                <h6 id="date_birth"><i class="fa fa-birthday-cake"></i><b>&nbspDate Of Birth : {{date('d F Y', strtotime($user_profile->date_of_birth))}}</b></h6>
                <h6 id="date_entry"><i class="fa fa-calendar"></i><b>&nbspDate Of Entry : {{date('d F Y', strtotime($user_profile->date_of_entry))}}</b></h6>
                <h6><i class="fa fa-phone"></i><b>&nbspPhone : +62{{$user_profile->phone}} </b></h6>
              </div>
              </div>
      </div>

      <div class="form-group margin-top-profile">
          <div class="card mb-3">
              <div class="card-header">
                Company
              </div>
              <div class="card-body">
                @if(Auth::User()->id_company == '1')
                Sinergy Informasi Pratama.
                @else
                Multi Solusindo Perkasa.
                @endif
              </div>
              </div>
      </div>
    </div>

    <div class="col-md-3">  
      <!-- <a href="https://twitter.com" class="fa fa-twitter icon" target="_blank"></a>  -->
<!--       <a href="https://www.linkedin.com/" class="fa fa-linkedin icon" target="_blank"></a> -->      
      <!-- <a href="https://www.instagram.com/" class="fa fa-instagram icon" target="_blank"></a>
      <a href="https://www.facebook.com/" class="fa fa-facebook icon" target="_blank"></a> -->
      <div class="svg-wrapper pull-left">
        <svg height="60" width="60" xmlns="http://www.w3.org/2000/svg">
          <rect class="shape" height="60" width="60" />
          <div style="margin-top: -70px" class="logos">
            <a href="https://twitter.com" class="fa fa-twitter icon" target="_blank" style="text-decoration: none"></a> 
          </div>
        </svg>
      </div>
      <div class="svg-wrapper pull-left">
        <svg height="60" width="60" xmlns="http://www.w3.org/2000/svg">
          <rect class="shape" height="60" width="60" />
          <div style="margin-top: -70px" class="logos">
            <a href="https://www.linkedin.com/" class="fa fa-linkedin icon" style="text-decoration: none" target="_blank"></a> 
          </div>
        </svg>
      </div>
      <div class="svg-wrapper pull-left">
        <svg height="60" width="60" xmlns="http://www.w3.org/2000/svg">
          <rect class="shape" height="60" width="60" />
          <div style="margin-top: -70px" class="logos">
             <a href="https://www.instagram.com/" class="fa fa-instagram icon" target="_blank" style="text-decoration: none;"></a>
          </div>
        </svg>
      </div>
      <div class="svg-wrapper pull-left">
        <svg height="60" width="60" xmlns="http://www.w3.org/2000/svg">
          <rect class="shape" height="60" width="60" />
          <div style="margin-top: -70px" class="logos">
            <a href="https://www.facebook.com/" class="fa fa-facebook icon" target="_blank" style="text-decoration: none;"></a>
          </div>
        </svg>
      </div>
      @if(Auth::User()->id_position == 'EXPERT SALES' || Auth::User()->id_position == 'EXPERT ENGINEER')
      @else
      <a href="{{url('show_cuti')}}"><button class="btn btn-warning-profile margin-top"><i class="fa fa-sticky-note-o"></i>&nbspLeaving Permit</button></a>
      @endif
    </div>

  </div>
  </div>
</div>

<div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Profile</h4>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" action="{{url('update_profile')}}" id="modalEditProfile" name="modalEditProfile">
            @csrf
            <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden>

            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Type Name" value="{{$user_profile->name}}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="email" name="email"  placeholder="Type Email" value="{{$user_profile->email}}" required>
            </div> 


            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" class="form-control float-right"required id="date_of_birth" name="date_of_birth" value="{{$user_profile->date_of_birth}}">
            </div> 

            <div class="form-group">
                <label class="margin-top">Date of Entry</label>
                <input type="date" class="form-control float-right "required id="date_of_entry" name="date_of_entry" value="{{$user_profile->date_of_entry}}">
            </div>             

            <div class="form-group profileInput inputIconBg">
                <label class="margin-top">Phone</label>
                <input type="number" class="form-control float-right" id="phone" name="phone" value="{{$user_profile->phone}}" onKeyPress="if(this.value.length==11) return false;" >
                <i class="" aria-hidden="true" >+62</i>
            </div>  

            <div class="form-group">
              <label class="margin-top">Image</label>
              <div class="col s6">
                 <img src="{{ asset('image/'.$user_profile->gambar) }}" id="tes" style="max-width:200px;max-height:200px;float:left;" />
              </div>
                
              <div class="col-md-4">
                <input type="file" id="inputgambar" name="gambar" class="validate" / >
                <span class="help-block">*<b>Max 2MB</b></span>  
              </div>
            
            </div>      
             
            <div class="modal-footer">
              <button class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

 <div class="modal fade" id="delete_pict" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form action="{{url('profile/delete_pict')}}" method="POST" hidden>
            {!! csrf_field() !!}
            <input type="" name="pick_nik" id="pick_nik" value="{{$user_profile->nik}}">
            <div style="text-align: center;">
              <h3>Are you sure?</h3><br><h3>DELETE PICTURE!</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success-raise" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="pict_profile" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <form action="{{url('profile/delete_pict')}}" method="POST">
            {!! csrf_field() !!}
            <input type="" name="pick_nik" id="pick_nik" value="{{$user_profile->nik}}" hidden>
            <div style="text-align: center;">
              <h3>Are you sure?</h3><br><h3>DELETE PICTURE!</h3>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
            <button class="btn btn-sm btn-success-raise" type="submit"><b>Yes</b></button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection