@extends('template.template')
@section('content')

<style type="text/css">
  .dropbtn {
  background-color: #4CAF50;
  color: white;
  font-size: 12px;
  border: none;
  width: 140px;
  height: 30px;
  border-radius: 5px;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 140px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}


.dropdown-content .year:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}

.transparant-filter{
  background-color: Transparent;
  background-repeat:no-repeat;
  border: none;
  cursor:pointer;
  overflow: hidden;
  outline:none;
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

  <div class="content-wrapper">
    <div class="container-fluid">
      
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Claim Management</a>
        </li>
      </ol>

      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
    <div class="alert alert-primary" id="alert">
        {{ session('success') }}
    </div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif

 <!--      <input type="" name="user" id="user" value="{{Auth::User()->id_position == 'ADMIN'}}"> -->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Claim Management Table
          @if(Auth::User()->id_position == 'ADMIN')
            <button type="button" class="btn btn-success-engineer float-right  margin-left-custom" data-target="#modalAdd" data-toggle="modal"><i class="fa fa-plus"><b> </i> &nbspAdd Claim </b></button>
            <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-custom" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b><i class="fa fa-download"></i> Export</b>
            </button>
            @endif
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item" href="{{url('/downloadPdfESM')}}"> PDF </a>
              <a class="dropdown-item" href="{{url('/downloadExcelESM')}}"> EXCEL </a>
            </div>
          <div class="dropdown btn btn-md pull-right">
            <button class="dropbtn"><i class="fa fa-filter"></i>&nbspFilter Year</button>
            <div class="dropdown-content">
              <div class="year">
                <span class="fa fa-calendar"></span>
                <input type="button" name="answer" value="2018" onclick="show2018()" class="transparant-filter" />
              </div>
              <div class="year">
                  <span class="fa fa-calendar"></span>
                  <input type="button" name="answer" value="2019" onclick="show2019()" class="transparant-filter" />
              </div>
            </div>
          </div>
           
        </div>
        <div class="card-body">
          <div class="table-responsive" id="div_2018" style="display: none">
            <table class="table table-bordered display nowrap" id="datasmu" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                    @if(Auth::User()->id_position == 'ADMIN')
                    <th>Create Date</th>
                    @endif
                  <th>Personnel</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>ID Project</th>
                  <th>Remarks</th>

                    @if(Auth::User()->id_position == 'ADMIN')
                    <th>Action</th>
                    @endif
                    
                    @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                    <th>Action</th>
                    @endif

                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                
                @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')

                  @if(Auth::User()->id_position == 'ADMIN')
                  @foreach($datas_2018 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'ADMIN')
                    <td>
                      <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="esm('{{$data->no}}','{{$data->type}}','{{$data->description}}','{{$data->amount}}','{{$data->id_project}}','{{$data->remarks}}')">&nbsp Edit
                      </button>
                      <a data-id="{{$data->no}}" name="delete_esm" id="delete_esm"><button  class="btn btn-sm btn-danger fa fa-trash fa-lg">&nbsp Delete</button>
                        <!-- <input type="button" name="delete_esm" id="delete_esm" class="btn btn-sm btn-danger fa fa-trash fa-lg" value="Delete" /> -->
                        <!-- <button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button> -->
                      </a>
                    </td>
                    @else
                    <td>
                      <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" style="width: 50px;height: 20px;text-align: center;">&nbsp Edit
                      </button>
                      <a><button class="btn btn-sm btn-danger fa fa-trash fa-lg disabled" style="width: 70px;height: 20px;text-align: center;">&nbsp Delete
                      </button></a>
                    </td>
                    @endif

                    @if($data->status == 'ADMIN')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_hrd" id="assign_to_hrd" onclick="number('{{$data->no}}','{{$data->amount}}')" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'ADMIN')
                      <td>
                        <button name="assign_to_hrd" class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
                  @foreach($datas_2018 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'HRD')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_fnc" value="{{$data->no}}" onclick="number_fnc('{{$data->no}}','{{$data->amount}}')" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'HRD')
                      <td>
                        <button class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-initial">ADMIN</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                  @foreach($datas_2018 as $data)
                  @if($data->status == 'HRD' || $data->status == 'FINANCE' || $data->status == 'TRANSFER')
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'FINANCE')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_adm" onclick="number_adm('{{$data->no}}')" value="{{$data->no}}" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'FINANCE')
                      <td>
                        <button class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endif
                  @endforeach
                  @endif

                @else

                  @foreach($datas_2018 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>
                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-lose">ADMIN</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                @endif

              </tbody>
            </table>
          </div>
          <div class="table-responsive" id="div_2019">
            <table class="table table-bordered display nowrap" id="datas2019" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Date</th>
                    @if(Auth::User()->id_position == 'ADMIN')
                    <th>Create Date</th>
                    @endif
                  <th>Personnel</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>ID Project</th>
                  <th>Remarks</th>

                    @if(Auth::User()->id_position == 'ADMIN')
                    <th>Action</th>
                    @endif
                    
                    @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                    <th>Action</th>
                    @endif

                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="products-list" name="products-list">
                
                @if(Auth::User()->id_position == 'ADMIN' || Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')

                  @if(Auth::User()->id_position == 'ADMIN')
                  @foreach($datas_2019 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{date('d-m-Y', strtotime($data->created_at))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'ADMIN')
                    <td>
                      <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modaledit" data-toggle="modal" style="width: 50px;height: 20px;text-align: center;" onclick="esm('{{$data->no}}','{{$data->type}}','{{$data->description}}','{{$data->amount}}','{{$data->id_project}}','{{$data->remarks}}')">&nbsp Edit
                      </button>
                      <a data-id="{{$data->no}}" name="delete_esm" id="delete_esm"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 70px; height: 20px; text-align: center;">&nbsp Delete</button>
                        <!-- <input type="button" name="delete_esm" id="delete_esm" class="btn btn-sm btn-danger fa fa-trash fa-lg" value="Delete" /> -->
                        <!-- <button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                        </button> -->
                      </a>
                    </td>
                    @else
                    <td>
                      <button class="btn btn-sm btn-primary fa fa-pencil fa-lg disabled" style="width: 50px;height: 20px;text-align: center;">&nbsp Edit
                      </button>
                      <a><button class="btn btn-sm btn-danger fa fa-trash fa-lg disabled" style="width: 70px;height: 20px;text-align: center;">&nbsp Delete
                      </button></a>
                    </td>
                    @endif

                    @if($data->status == 'ADMIN')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_hrd" id="assign_to_hrd" onclick="number('{{$data->no}}','{{$data->amount}}')" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'ADMIN')
                      <td>
                        <button name="assign_to_hrd" class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px; text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'HR' && Auth::User()->id_position == 'HR MANAGER')
                  @foreach($datas_2019 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'HRD')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_fnc" value="{{$data->no}}" onclick="number_fnc('{{$data->no}}','{{$data->amount}}')" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'HRD')
                      <td>
                        <button class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-initial">ADMIN</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endforeach
                  @endif

                  @if(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
                  @foreach($datas_2019 as $data)
                  @if($data->status == 'HRD' || $data->status == 'FINANCE' || $data->status == 'TRANSFER')
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>

                    @if($data->status == 'FINANCE')
                      <td>
                        <button data-target="#keterangan" data-toggle="modal" name="assign_to_adm" onclick="number_adm('{{$data->no}}')" value="{{$data->no}}" class="btn btn-sm btn-warning" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @elseif($data->status != 'FINANCE')
                      <td>
                        <button class="btn btn-sm btn-warning disabled" style="width: 70px; height: 25px;  text-align: center;">Submit</button>
                      </td>
                    @endif

                    <td>
                      @if($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-lose">PENDING</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>

                  </tr>
                  @endif
                  @endforeach
                  @endif

                @else

                  @foreach($datas_2019 as $data)
                  <tr>
                    <td name="no_submit" id="no_submit">
                      <a href="{{ url ('/detail_esm', $data->no) }}">{{ $data->no }}</a>
                    </td>
                    <td>{{date('d-m-Y', strtotime($data->date))}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->type}}</td>
                    <td>{{$data->description}}</td>
                    <td class="money">{{$data->amount}}</td>
                    <td>{{$data->id_project}}</td>
                    <td>{{$data->remarks}}</td>
                    <td>
                      @if($data->status == 'ADMIN')
                      <label class="status-lose">ADMIN</label>
                      @elseif($data->status == 'HRD')
                      <label class="status-initial">HRD</label>
                      @elseif($data->status == 'FINANCE')
                      <label class="status-open">FINANCE</label>
                      @elseif($data->status == 'TRANSFER')
                      <label class="status-sd">TRANSFER</label>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                @endif

              </tbody>
            </table>
          </div>
        </div>
      </div>
  </div>
</div>

<!-- MODAL ADD -->
<div class="modal fade" id="modalAdd" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Add Claim</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{url('/store_esm')}}" id="modal_product" name="modal_product">
              @csrf
            <div class="form-group">
              <label for="">No</label>
              <input type="text" class="form-control" placeholder="Enter No" name="no" id="no" required>
            </div>
            <div class="form-group">
              <label for="">Date</label>
              <input type="date" class="form-control" placeholder="DD/MM/YYYY" name="date" id="date" required>
            </div>
            <div class="form-group">
              <label for="">Personnel</label>
              <select type="text" class="form-control" placeholder="Enter Personnel" name="personnel" id="personnel" required>
                @if(Auth::User()->id_division == 'TECHNICAL')
                  @foreach($owner as $data)
                    @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'PMO')
                  @foreach($owner as $data)
                    @if($data->id_division == 'PMO')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'MSM')
                  @foreach($owner as $data)
                    @if($data->id_division == 'MSM')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @endif
              </select>
            </div> 
            <div class="form-group">
              <label for="">Type</label>
              <select type="text" class="form-control" placeholder="Enter Type" name="type" id="type" required>
                <option>Allowance Staff</option>
                <option>Accomodation</option>
                <option>Entertainment</option>
                <option>Gasoline</option>
                <option>Konsumsi</option>
                <option>Money Request</option>
                <option>Other Claim</option>
                <option>Overtime</option>
                <option>Parking</option>
                <option>Pertanggung Jawaban</option>
                <option>Project Budgeting</option>
                <option>Pulsa</option>
                <option>Toll</option>
                <option>Transportation</option>
              </select>
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <input type="text" class="form-control" placeholder="Enter Description" name="description" id="description" required>
            </div> 
            <div class="form-group modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
            </div> 
            <div class="form-group">
              <label for="">ID Project</label>
              <input type="text" class="form-control" placeholder="Enter ID Project" name="id_project" id="id_project">
            </div>
            <div class="form-group">
              <label for="">Remarks</label>
              <input type="text" class="form-control" placeholder="Enter Remarks" name="remarks" id="remarks" required>
            </div>  
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
              </div>
          </form>
          </div>
        </div>
      </div>
</div> 

<!-- MODAL EDIT -->
 <div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Claim</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/edit_esm')}}" id="modalEdit" name="modalEdit">
            @csrf
            <div class="form-group">
              <label>No</label>
              <input class="form-control" id="edit_no" name="edit_no" readonly>
            </div>
            <div class="form-group">
              <label for="">Personnel</label>
              <select type="text" class="form-control" placeholder="Enter Personnel" name="edit_personnel" id="edit_personnel" required>
                @if(Auth::User()->id_division == 'TECHNICAL')
                  @foreach($owner as $data)
                    @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'PMO')
                  @foreach($owner as $data)
                    @if($data->id_division == 'PMO')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @elseif(Auth::User()->id_division == 'MSM')
                  @foreach($owner as $data)
                    @if($data->id_division == 'MSM')
                      <option value="{{$data->nik}}">{{$data->name}}</option>
                    @endif
                  @endforeach
                @endif
              </select>
            </div> 
            <div class="form-group">
              <label for="">Type</label>
              <input type="text" class="form-control" name="edit_type" id="edit_type" >
            </div>
            <div class="form-group">
              <label for="">Description</label>
              <input type="text" class="form-control" name="edit_description" id="edit_description" >
            </div> 
            <div class="form-group modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control" name="edit_amountclaim" id="edit_amountclaim" >
              <i class="" aria-hidden="true">Rp.</i>
            </div> 
            <div class="form-group">
              <label for="">ID Project</label>
              <input type="text" class="form-control" name="edit_id_project" id="edit_id_project" >
            </div>
            <div class="form-group">
              <label for="">Remarks</label>
              <input type="text" class="form-control" name="edit_remarks" id="edit_remarks" >
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
            </div>

        </form>
        </div>
      </div>
    </div>
  </div>

@if(Auth::User()->id_position == 'ADMIN')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_hrd')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_hrd_edit" name="assign_to_hrd_edit" value="" hidden>
          <input type="" id="amount_edit" name="amount_edit" value="" hidden>
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
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
@elseif(Auth::User()->id_position == 'HR MANAGER')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_fnc')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_fnc_edit" name="assign_to_fnc_edit" value="" hidden>
          <div class="form-group  modalIcon inputIconBg">
            <label for="">Amount</label>
            <input type="text" class="form-control money" name="amount_edit" id="amount_edit" readonly>
            <i class="" aria-hidden="true">Rp.</i>
          </div>
          <div class="form-group modalIcon inputIconBg">
              <label for="">Revised Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
          </div> 
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
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
@elseif(Auth::User()->id_division == 'FINANCE' && Auth::User()->id_position == 'STAFF')
  <div class="modal fade" id="keterangan" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Keterangan</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/assign_to_adm')}}" id="modalProgress" name="modalProgress">
            @csrf
          <input type="" id="assign_to_adm_edit" name="assign_to_adm_edit" value="" hidden>
          <div class="form-group modalIcon inputIconBg">
              <label for="">Amount</label>
              <input type="text" class="form-control money" placeholder="Enter Amount" name="amount" id="amount" required>
              <i class="" aria-hidden="true">Rp.</i>
          </div> 
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
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
@endif

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">

  @if(Auth::User()->id_position == 'ADMIN') {
    $('#datasmu').DataTable( {
        "scrollX": true,
        "order": [[ 11, "desc" ]],
        });
  }@else{
    $('#datasmu').DataTable( {
        "scrollX": true,
        } );
  }
  @endif

  @if(Auth::User()->id_position == 'ADMIN') {
    $('#datas2019').DataTable( {
        "scrollX": true,
        "order": [[ 11, "desc" ]],
        fixedColumns:   {
            leftColumns: 4
        },
    });
  }@else{
    $('#datas2019').DataTable( {
        "scrollX": true,
        fixedColumns:   {
            leftColumns: 4
        },
    });
  }
  @endif
     

    function esm(no, type, description, amount, id_project, remarks, personnel) {
      $('#edit_no').val(no);
      $('#edit_type').val(type);
      $('#edit_description').val(description);
      $('#edit_amountclaim').val(amount);
      $('#edit_id_project').val(id_project);
      $('#edit_remarks').val(remarks);
      $('#edit_personnel').val(personnel);
    }

    function number(no, amount) {
      $('#assign_to_hrd_edit').val(no);
      $('#amount_edit').val(amount);
    }

    function number_fnc(no, amount) {
      $('#amount_edit').val(amount);
      $('#assign_to_fnc_edit').val(no);
    }

    function number_adm(no){
      $('#assign_to_adm_edit').val(no);
    }

    /*$('.money').mask('000,000,000,000,000', {reverse: true});
      $(document).ready(function() {
          $('#contact').select2();
    });*/

    $('.money').mask('000,000,000,000,000', {reverse: true});

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    function show2019() {
         document.getElementById('div_2018').style.display = "none";
         document.getElementById('div_2019').style.display = "inherit";
    }

    function show2018() {
         document.getElementById('div_2018').style.display = "inherit";
         document.getElementById('div_2019').style.display = "none";
    }
  </script>
@endsection