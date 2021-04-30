@extends('template.main')
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
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
@endsection
@section('content')
<section class="content-header">
  <h1>
      Detail SHO - {{ $tampilkanb->id_sho }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="/sho">Sales Handover</a></li>
    <li class="active">Detail SHO - {{ $tampilkanb->id_sho }}</li>
  </ol>
</section>

<section class="content">

  <div class="box">
    <div class="box-header">
      <div class="box-header with-border">
        <h4 class="pull-left" style="margin-left: 20px;margin-top: 20px"><b>{{$tampilkan->lead_id}}</b></h4>
        <h4 class="pull-right" style="margin-right: 20px;margin-top: 20px"><b>{{$tampilkan->meeting_date}}</b></h4>
        <h4 class="pull-left" style="clear: left;margin-left: 20px"> <i>Owner : </i>{{$tampilkanb->name}}</h4>
      </div>
    </div>

    <div class="box-body">
      <div class="box-body">
        <h5>{{$tampilkan->sow}}</h5>
        <h5>{{$tampilkan->timeline}}</h5>
        <div style="margin-top: 20px">
          <h5>Term of Payment : {{$tampilkan->top}}</h5>
          <h5>Rp <b class="money">{{$tampilkan->service_budget}}</b></h5>
        </div>
      </div>
      <div class="box-footer">
        @if($tampilkanx == $now)
          <div class="card-footer small text-muted">Post Today</div>
        @elseif($tampilkanx != $now)
          <div class="card-footer small text-muted">Posted {{$tampilkanx}} days ago</div>
        @endif
      </div>
    </div>
  </div>

  @if($tampilkan->meeting_date == date('Y-m-d'))
    @if(!($tampilkanb->nik == Auth::User()->nik))
    <div class="margin-bottom" id="divListEmployee" style="display: none;">  
    @else
    <div class="margin-bottom" id="divListEmployee">
    @endif  
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

  <div class="box">
    <div class="box-header">
      <div class="box-header with-border">
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

      <div class="box-body">
        <div class="box-body">
          <div class="table-responsive">
              <table class="table table-bordered table-striped display nowrap" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Attendee Date</th>
                    <th>Information</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tampilkans as $data)
                  <tr>
                    <td>{{$data->name}}</td>
                    <td>{!!substr($data->tanggal_hadir,0,10)!!}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>
                      @if($data->status == '')
                        @if(!($tampilkanb->nik == Auth::User()->nik))
                        <label class="status-sho">Absen at {!!substr($data->updated_at,11,8)!!}</label>
                        @else
                        <label class="status-sho" id="labelStatusSho">Absen at {!!substr($data->updated_at,11,8)!!}</label>                          
                        @endif
                      @else
                        @if(!($tampilkanb->nik == Auth::User()->nik))
                        <a href="{{ url('delete_detail_sho', $data->id_transaction) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button></a>
                        @else
                        <a href="{{ url('delete_detail_sho', $data->id_transaction) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" id="btnDeleteSho" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button></a>                        
                        @endif
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>



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
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@endsection
@section('script')  
  <script type="text/javascript">
      $(document).ready(function(){
        var accesable = @json($feature_item);
        accesable.forEach(function(item,index){
          $("#" + item).show()          
        })  
      })

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

      $('#dataTable').DataTable({
        
      });

     function attendee(id_transaction) {
      $("#id_sho_transac").val(id_transaction);
      // bod...y
    }

    $('[data-toggle="tooltip"]').tooltip();   
    </script>
@endsection