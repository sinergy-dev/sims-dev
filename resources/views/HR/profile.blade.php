@extends('template.template_admin-lte')
@section('content')
  <style type="text/css">
    #tes {
    text-align: center;
    background-color: #7c21a3;
    color: white;
    width: 4em;
    height: 4em;
    line-height: 4em;
    border-radius: 50%;
    font-size: 50px;
    font-family: helvetica;
    font-style: bold;
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
    <div class="box">
      <div class="box-body">
        @if (session('success'))
          <div class="alert-box notice" id="alert">
              {{ session('success') }}
          </div>
        @endif
        <div class="row">
          <div class="col-md-12 col-xs-12">
            <div class="pull-left" style="padding-right: 10px">
              @if(Auth::User()->gambar == NULL)
                <div id="tes">
                {!! strtoupper(substr($user_profile->name, 0, 2))!!}
                </div>
              @elseif(Auth::User()->gambar != NULL)
                <img id="tes" class="profile-user img-responsive" src="{{ asset('image/'.$user_profile->gambar)}}" alt="Avatar" style="border:solid white;" data-toggle="modal" data-target="#pict_profile" onclick="nik_profile('{{$user_profile->nik}}')">
              @endif
            </div>
            <div class="pull-left">
              <div class="profile">
                <h1>{{$user_profile->name}}</h1>
                <h6 class="pull-left"><i class="fa fa-address-card"></i><b>&nbsp&nbsp {{$user_profile->nik}} </b></h6>
                <h6 class="pull-left"><i class="fa fa-envelope"></i><b>&nbsp&nbsp {{$user_profile->email}} </b></h6> 
                <h6 class="pull-left"><i class="fa fa-phone"></i><b>&nbsp&nbsp +62{{$user_profile->phone}} </b></h6>
              </div>
              <div class="pull-left">
                <button class="btn btn-md btn-primary btn-edit" type="button" style="width: 150px"><i class="fa fa-key"></i> Change Password</button>
                <a href="{{url('show_cuti')}}"><button class="btn btn-md btn-success" style="width: 150px"><i class="fa fa-user"></i> Leaving Permite</button></a>
                <div class="nav-tabs-custom" style="margin-top:50px">

                <ul class="nav nav-tabs">
                    <li class="active">
                      <a href="#about" data-toggle="tab">About</a>
                    </li>
                    <li>
                      <a href="" data-toggle="tab"></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="about">
                    	<form action="{{url('update_profile')}}" enctype="multipart/form-data" method="POST">
                        <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden>
                          @csrf
                            <div class="row">
                              <div class="col-md-12">
                                <div class="col-md-4">
                                  <label style="margin: 12px">Name</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" style="width: 300px;padding: 12px;margin: 12px;" class="form-control" id="name" name="name" placeholder="Type Name" value="{{$user_profile->name}}" required>
                                </div>  
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                <div class="col-md-4">
                                  <label style="margin: 12px">Email</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" class="form-control" id="email" name="email"  placeholder="Type Email" value="{{$user_profile->email}}" required style="width: 300px;padding: 12px;margin: 12px;">
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Date of Birth</label>
                                  </div>
                                  <div class="col-md-8">
                                    <input type="date" class="form-control"required id="date_of_birth" name="date_of_birth" value="{{$user_profile->date_of_birth}}" style="width: 300px;margin: 12px;">
                                  </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Date of Entry</label>
                                  </div>
                                  <div class="col-md-8">
                                    <input type="date" class="form-control" required id="date_of_entry" name="date_of_entry" value="{{$user_profile->date_of_entry}}" style="width: 300px;margin: 12px;">
                                  </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Lama Bekerja</label>
                                  </div>
                                  <div class="col-md-8" style="margin-top: 12px">
                                  	<span style="margin-left: 12px;">
                                    @if($user_profile->date_of_entrys > 365)
                                    {{ floor($user_profile->date_of_entrys / 365) }} Tahun {{ round($user_profile->date_of_entrys % 365 / 30 )}} Bulan
                                    @elseif($user_profile->date_of_entrys > 31)
                                    {{ floor($user_profile->date_of_entrys / 30)}} Bulan
                                    @else
                                    {{$user_profile->date_of_entrys}} Hari
                                    @endif
                                      <!-- {{ floor($user_profile->date_of_entrys / 365) }} tahun {{ $user_profile->date_of_entrys % 365 }} hari</span> -->
                                  </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Phone</label>
                                  </div>
                                  
                                  <div class="col-md-8">
                                  	@if($user_profile->phone != null)
                                    <input type="number" class="form-control" id="phone" name="phone" value="0{{$user_profile->phone}}" onKeyPress="if(this.value.length==12) return false;" style="width: 300px;margin: 12px;">
                                    @else
                                    <input type="number" class="form-control" id="phone" name="phone" value="" onKeyPress="if(this.value.length==12) return false;" style="width: 300px;margin: 12px;">
                                    @endif
                                  </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Address</label>
                                  </div>
                                  <div class="col-md-8">
                                    <input class="form-control" id="address" name="address" style="white-space: nowrap;margin: 12px;width: 300px" value="{{$user_profile->address}}">
                                  </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-4">
                                    <label style="margin: 12px">Image</label>
                                  </div>
                                  <div class="col-md-8">
                                    
                                    <div class="col-md-4">
                                      <input type="file" id="inputgambar" name="gambar" class="validate" / >
                                      <span class="help-block">*<b>Max 2MB</b></span>  
                                    </div>


                                  </div>
                              </div>
                            </div>


                            <div class="row">
                              <div class="col-md-12">
                                <div class="col-md-4">
                                  <label style="margin: 12px">NPWP</label>
                                </div>
                                <div class="col-md-8">
                                  <input type="text" style="width: 300px;padding: 12px;margin: 12px;" class="form-control" id="no_npwp" name="no_npwp" placeholder="Type NPWP" value="{{$user_profile->no_npwp}}" required>
                                </div>  
                              </div>
                            </div>

                            <button class="btn btn-sm btn-warning pull-right" type="submit"><i class="fa fa-edit"></i>&nbspUpdate</button>
                      </form>
                    </div>
                    <div class="tab-pane" id="">
                    </div>
                </div>
              </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!--modal tic tac toe-->
    <div class="modal fade" id="modal_tic">
  	<div class="modal-dialog modal-md">
  		<div class="modal-content">
  			<div class="modal-header">
  				<h4 class="modal-title">Tic Tac Toe</h4>
  			</div>
  			<div class="modal-body">
  				<div class="row">
  					<button onclick="initialize()" class="btn btn-sm btn-primary">Start Game</button>
  					<div class="col-md-6">
	  					<table id="table_game">
					      <tr><td class="td_game"><div id="cell0" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell1" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell2" onclick="cellClicked(this.id)" class="fixed"></div></td></tr>
					      <tr><td class="td_game"><div id="cell3" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell4" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell5" onclick="cellClicked(this.id)" class="fixed"></div></td></tr>
					      <tr><td class="td_game"><div id="cell6" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell7" onclick="cellClicked(this.id)" class="fixed"></div></td><td class="td_game"><div id="cell8" onclick="cellClicked(this.id)" class="fixed"></div></td></tr>
						</table>
  					</div>
  					<div class="col-md-3">
  						<table>
					      <tr><th class="th_list">Computer</th><th class="th_list" style="padding-right:10px;padding-left:10px">Seri</th><th class="th_list">Player</th></tr>
					      <tr><td class="td_list" id="computer_score">0</td><td class="td_list" style="padding-right:10px;padding-left:10px" id="tie_score">0</td><td class="td_list" id="player_score">0</td></tr>
						</table>
						<button data-dismiss="modal" class="btn btn-sm btn-danger margin-top" style="width: 165px" onclick="selesai()">End Game</button>
  					</div>
  				</div>
  			</div>
  		</div>
  	</div>
  </div>

  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-md">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Profile</h4>
        </div>
        <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" action="{{url('changePassword')}}" id="modalEditProfile" name="modalEditProfile">
            @csrf
            <input type="text" name="nik_profile" id="nik_profile" value="{{$user_profile->nik}}" hidden> 

            <div class="form-group ">
              <label class="margin-top">Current Password</label>
              <input class="form-control" id="current-password" name="current-password" type="Password"  placeholder="Enter Your Current Password">
            </div>


            <div class="form-group">
              <label class="margin-top">New Password</label>
              <input class="form-control" id="new-password" name="new-password" type="Password" placeholder="Enter New Password">
            </div>

            <div class="form-group">
              <label class="margin-top">Confirm Password</label>
            <input class="form-control" id="new-password-confirm" name="new-password-confirm" type="Password" placeholder="Enter Confirm Password">
            </div>

         <!--    <div class="form-group">
              <label class="margin-top">Image</label>
              <div class="col s6">
                 <img src="{{ asset('image/'.$user_profile->gambar) }}" id="tes" style="max-width:100px;max-height:100px;float:left;" />
              </div>
                
              <div class="col-md-4">
                <input type="file" id="inputgambar" name="gambar" class="validate" / >
                <span class="help-block">*<b>Max 2MB</b></span>  
              </div>
            
            </div>  -->     
             
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

  <div id="winAnnounce" class="modal modal_tic_tac">
	  <!-- Modal content -->
	  <div class="modal-content">
	    <span class="close" onclick="closeModal('winAnnounce')">&times;</span>
	    <p id="winText"></p>
	  </div>
  </div>
	<!-- The options dialog -->
	<div id="optionsDlg" class="modal modal_tic_tac">
	  <!-- Modal content -->
	  <div class="modal-content">
	    <h2>How would you like to play?</h2>
	      <h3>Difficulty:</h3>
	      <label><input type="radio" name="difficulty" id="r0" value="0">easy&nbsp;</label>
	      <label><input type="radio" name="difficulty" id="r1" value="1" checked>hard</label><br>
	      <h3>Play as:</h3>
	      <label><input type="radio" name="player" id="rx" value="x" checked>X (go first)&nbsp;</label>
	      <label><input type="radio" name="player" id="ro" value="o">O<br></label>
	      <p><button id="okBtn" onclick="getOptions()">Play</button></p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript">

 $('#inputgambar').on('change', function() { 

      const size =  
         (this.files[0].size / 1024 / 1024).toFixed(2); 

      if (size > 4 || size < 2) { 
          alert("File must be between the size of 2-4 MB"); 
      } else { 
          $("#output").html('<b>' + 
             'This file size is: ' + size + " MB" + '</b>'); 
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

  $("#inputgambar").change(function () {
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