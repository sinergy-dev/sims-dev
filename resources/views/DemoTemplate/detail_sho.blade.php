@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Detail Sales Hand Over</a>
        </li><!-- 
        <li class="breadcrumb-item active">Direktur</li> -->
      </ol>

          <div class="card mb-3">
          <div class="card-title mb-1">
            <h6 class="pull-left" style="margin-left: 20px;margin-top: 20px">{{$tampilkan->lead_id}}</h6>
            <h6 class="pull-right" style="margin-right: 20px;margin-top: 20px"><b>{{$tampilkan->meeting_date}}</b></h6>
            <h6 class="pull-left" style="clear: left;margin-left: 20px"> <i>Owner : </i>{{$tampilkanb->name}}</h6>            
          </div>
          <hr class="">
          <div class="card-body small bg-faded">
            <div class="media">
              <div class="media-body">
                <h6>{{$tampilkan->sow}}</h6>
                <h6>{{$tampilkan->timeline}}</h6>
        <div style="margin-top: 20px">
          <h6>Term of Payment : {{$tampilkan->top}}</h6>
          <h6>Rp <b class="money">{{$tampilkan->service_budget}}</b></h6>
        </div>
              </div>
            </div>
          </div>
          @if($tampilkanx == $now)
          <div class="card-footer small text-muted">Post Today</div>
          @elseif($tampilkanx != $now)
          <div class="card-footer small text-muted">Posted {{$tampilkanx}} days ago</div>
          @endif
        </div>

    @if($tampilkan->meeting_date == date('Y-m-d'))
    @if($tampilkanb->nik == Auth::User()->nik || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
    <div class="margin-bottom">
        <div style="background-color:#3a66ad;height:35px;color: white;padding-top:5px;padding-left:15px;">
          <h6>List Employees</h6>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="presales-tab" data-toggle="tab" href="#presales" role="tab" aria-controls="presales" aria-selected="true">Presales</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Sales</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" data-toggle="tab" href="#ter1">Territory 1</a>
              <a class="dropdown-item" data-toggle="tab" href="#ter2">Territory 2</a>
              <a class="dropdown-item" data-toggle="tab" href="#ter3">Territory 3</a>
              <a class="dropdown-item" data-toggle="tab" href="#ter4">Territory 4</a>
              <a class="dropdown-item" data-toggle="tab" href="#ter5">Territory 5</a>
              <a class="dropdown-item" data-toggle="tab" href="#ter6">Territory 6</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#engineer" role="tab" aria-controls="profile" aria-selected="false">Engineer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#PMO" role="tab" aria-controls="contact" aria-selected="false">PMO</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active cek" id="presales" role="tabpanel" aria-labelledby="presales-tab">
           <ul id="myUL" class="listed" >
             @foreach($presales as $data)
          <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
                <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
                <!-- <button class="transparant pull-right"><i class="fa fa-times pull-right" style="color: red"></i></button> -->
               <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
          </form>
          <div class="tab-pane fade cek" id="ter1" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter1 as $data)
            <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
            </form>
           <div class="tab-pane fade cek" id="ter2" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter2 as $data)
              <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
            </form>
           <div class="tab-pane fade cek" id="ter3" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter3 as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
            </form>
           <div class="tab-pane fade cek" id="ter4" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter4 as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
            </form>
           <div class="tab-pane fade cek" id="ter5" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter5 as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
            </form>
           <div class="tab-pane fade cek" id="ter6" role="tabpanel" aria-labelledby="profile-tab">
            <ul id="myUL" class="listed" >
             @foreach($ter6 as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
              </form>
          <div class="tab-pane fade cek" id="engineer" role="tabpanel" aria-labelledby="engineer-tab">
            <ul id="myUL" class="listed" >
             @foreach($engineer as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
              </form>
          <div class="tab-pane fade cek" id="PMO" role="tabpanel" aria-labelledby="engineer-tab">
             <ul id="myUL" class="listed" >
             @foreach($pmo as $data)
               <form method="POST" action="{{url('/store_sho_transac')}}">
             @csrf
              <li><i class="fa fa-user-circle-o"></i>&nbsp{{$data->name}}
              <input type="" name="id_sho" id="id_sho" value="{{$tampilkanb->id_sho}}" hidden>
              <button class="transparant pull-right" type="submit" name="nik_transaction" value="{{$data->nik}}"> <i class="fa fa-check pull-right" style="color: green"></i></button>
              </li>
             @endforeach
            </ul>
          </div>
              </form>
        </div>
    </div>
    @endif
    @endif

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> <b>List Attendee</b>
          @foreach($tampilkans as $data)
          @if($data->meeting_date == date('Y-m-d'))
            @if($data->nik == Auth::User()->nik)
              @if($data->status == NULL)
              <button class="btn btn-warning pull-right disabled" style="width: 125px;color: white" title="You have been absent!!"><i class="fa fa-hand-paper-o"></i>&nbsp Attendee </button>
              @else
              <button class="btn btn-warning pull-right" style="width: 125px" data-target="#keterangan" data-toggle="modal" onclick="attendee('{{$data->id_transaction}}')"><i class="fa fa-hand-paper-o"></i>&nbsp Attendee </button>
              @endif
            @endif
          @endif
          @endforeach
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Attendee Date</th>
                  <th>Information</th>
                  @if($tampilkanb->nik == Auth::User()->nik || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach($tampilkans as $data)
                <tr>
                  <td>{{$data->name}}</td>
                  <td>{!!substr($data->tanggal_hadir,0,10)!!}</td>
                  <td>{{$data->keterangan}}</td>
                  @if($tampilkanb->nik == Auth::User()->nik || Auth::User()->id_position == 'MANAGER' && Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                  <td>
                    @if($data->status == '')
                    <label class="status-sho">Absen at {!!substr($data->updated_at,11,8)!!}</label>
                      @else
                    <a href="{{ url('delete_detail_sho', $data->id_transaction) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                    </button></a>
                    @endif
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
        </div>
      </div>

<style type="text/css">
  /* Style the list items */
    .cek ul li {
      cursor: pointer;
      position: relative;
      padding: 12px 8px 12px 40px;
      list-style-type: none;
      background: #eee;
      font-size: 18px;
      transition: 0.2s;
      
      /* make the list items unselectable */
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .cek ul{ 
      margin: 0;
      padding: 0;
    }

    /* Set all odd list items to a different color (zebra-stripes) */
    .cek ul li:nth-child(odd) {
      background: #f9f9f9;
    }

    /* Darker background-color on hover */
    .cek ul li:hover {
      background: #ddd;
    }

    /* When clicked on, add a background color and strike out text */
    .cek ul li.checked {
      background: #888;
      color: #fff;
    }


    /* Add a "checked" mark when clicked on */
    .cek ul li.checked::before {
      content: '';
      position: absolute;
      border-color: #fff;
      border-style: solid;
      border-width: 0 2px 2px 0;
      top: 10px;
      left: 16px;
      transform: rotate(45deg);
      height: 15px;
      width: 7px;
    }

        /* Style the close button */
    .close {
      position: absolute;
      right: 0;
      top: 0;
      padding: 12px 16px 12px 16px;
    }

    .close:hover {
      background-color: #f44336;
      color: white;
    }

    .del{
      color: red
    }

    .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }


  </style>

  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Attendee</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_sho_transac')}}" id="modalCustomer" name="modalCustomer">
            @csrf
          <input type="" name="id_sho_transac" id="id_sho_transac" hidden>
          <div class="form-group">
            <label for="sow">Information</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
          </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success-absen"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript">
      $('.money').mask('000,000,000,000,000.00', {reverse: true});


      // Click on a close button to hide the current list item
      var close = document.getElementsByClassName("close");
      var i;
      for (i = 0; i < close.length; i++) {
        close[i].onclick = function() {
          var div = this.parentElement;
          div.style.display = "none";
        }
      }

      var close = document.getElementsByClassName("del");
      var i;
      for (i = 0; i < close.length; i++) {
        close[i].onclick = function() {
          var div = this.parentElement;
          div.style.display = "none";
        }
      }


     function attendee(id_transaction) {
      $("#id_sho_transac").val(id_transaction);
      // bod...y
    }

    $('[data-toggle="tooltip"]').tooltip();   
    </script>
@endsection