@extends('template.template')
@section('content')
<style type="text/css">
  .margin-left-custom2{
  margin-left: 15px;
  }
  .margin-left-custom3{
    margin-left: 17px;
  }
  hr.new4 {
  border: 0.5px solid #007bff!important;
  margin-top: 40px;
}
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</style>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Human Resource</a>
        </li><!-- 
        <li class="breadcrumb-item active">Direktur</li> -->
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

      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Employee Table
        </div>
        <div class="card-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="SIP-tab" data-toggle="tab" href="#SIP" role="tab" aria-controls="sip-tab" aria-selected="true">SIP</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="MSP-tab" data-toggle="tab" href="#MSP" role="tab" aria-controls="msp-tab" aria-selected="false">MSP</a>
            </li>
          </ul>
          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
            <button class="btn btn-primary-lead pull-right" style="margin-top: 12px" id="btnAdd"><i class="fa fa-plus"></i>&nbsp Employee</button>
            @endif<hr class="new4">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active margin-top" id="SIP" role="tabpanel" aria-labelledby="sip-tab">
               
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">ALL</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="tech-tab" data-toggle="tab" href="#tech" role="tab" aria-controls="tech" aria-selected="false">TECHNICAL</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false"> SALES</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="finance-tab" data-toggle="tab" href="#finance" role="tab" aria-controls="finance" aria-selected="false"> FINANCE</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="operation-tab" data-toggle="tab" href="#operation" role="tab" aria-controls="operation" aria-selected="false"> OPERATION</a>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active margin-top" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_all" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>NIK</th>
                              <th>Employees Name</th>
                              <th>Position</th>
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              <th>Action</th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($hr as $data)
                            @if($data->id_company == '1')
                            <tr>
                              <td><?=str_replace('/', '', $data->nik)?></td>
                              <td>{{ $data->name }}</td>
                              @if($data->id_position != '')
                              <td>
                                @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                                  President Director
                                @elseif($data->id_division == 'TECHNICAL')
                                  @if($data->id_territory == 'DPG')
                                    @if($data->id_position == 'ENGINEER MANAGER')
                                      Dept. Implementation Manager
                                    @elseif($data->id_position == 'ENGINEER STAFF')
                                      Staff. Systems Engineer
                                    @endif
                                  @elseif($data->id_territory == 'DVG')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Development Manager
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Dev Ops
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @elseif($data->id_territory == 'SPECIALIST')
                                    @if($data->id_position == 'EXPERT ENGINEER')
                                      Expert Engineer
                                    @endif
                                  @else
                                    @if($data->id_position == 'MANAGER')
                                      Div. Technical Head
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'TECHNICAL PRESALES')
                                  @if($data->id_position == 'MANAGER')
                                    Dept. Presales Manager
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Presales Engineer
                                  @endif
                                @elseif($data->id_division == 'SALES')
                                  @if($data->id_territory == 'TERRITORY 1')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (First)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM1
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 2')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Second)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM2
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 3')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Third)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM3
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 4')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fourth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM4
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 5')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fifth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM5
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 6')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Sixth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM6
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'SPECIALIST')
                                    @if($data->id_position == 'EXPERT SALES')
                                      Expert Sales
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'FINANCE')
                                  @if($data->id_position != 'FINANCE DIRECTOR')
                                    @if($data->id_territory == 'FINANCE')
                                      @if($data->id_position == 'STAFF')
                                        Staff. Finance
                                      @elseif($data->id_position == 'COURIER')
                                        Staff. Courier
                                      @endif
                                    @elseif($data->id_territory == 'ACC')
                                      @if($data->id_position == 'MANAGER')
                                        Div. Accounting
                                      @elseif($data->id_position == 'STAFF')
                                        Staff. Accounting
                                      @endif
                                    @endif
                                  @else
                                    Finance Director
                                  @endif
                                @elseif($data->id_territory == 'OPERATION')
                                  @if($data->id_division == null)
                                    Operation Director
                                  @elseif($data->id_division == 'PMO')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Project Management Office
                                    @elseif($data->id_position == 'PM')
                                      Staff. Project Manager
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. PMO Admin
                                    @endif
                                  @elseif($data->id_division == 'MSM')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Managed Services & Maintenance
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. MSM Admin
                                    @elseif($data->id_position == 'CALL SO')
                                      Staff. Call Center Operator
                                    @elseif($data->id_position == 'HELP DESK')
                                      Staff. Dedicated Help Desk
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                      Dept. Technical Support
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                      Staff. Support Engineer
                                    @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                      Dept. Services Project Manager
                                    @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                      Staff. Services Project Coordinator
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'HR')
                                  @if($data->id_position == 'HR MANAGER')
                                    Div. Human Resource Head
                                  @elseif($data->id_position == 'STAFF GA')
                                    Staff. General Affair
                                  @elseif($data->id_position == 'STAFF HR')
                                    Staff. Human Resource
                                  @endif
                                @else
                                  {{ $data->id_position }}
                                @endif
                              </td>
                              @else
                              <td>&#8212</td>
                              @endif
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              <td>
                                <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                                <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                                </button></a>
                              </td>
                              @endif
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                    </div> 
                </div>
                <div class="tab-pane fade margin-top" id="tech" role="tabpanel" aria-labelledby="tech-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_tech" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>NIK</th>
                              <th>Employees Name</th>
                              <th>Position</th>
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              <th>Action</th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($hr as $data)
                            @if($data->id_division == 'TECHNICAL' || $data->id_division == 'TECHNICAL PRESALES')
                            <tr>
                              <td><?=str_replace('/', '', $data->nik)?></td>
                              <td>{{ $data->name }}</td>
                              @if($data->id_position != '')
                              <td>
                                @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                                  President Director
                                @elseif($data->id_division == 'TECHNICAL')
                                  @if($data->id_territory == 'DPG')
                                    @if($data->id_position == 'ENGINEER MANAGER')
                                      Dept. Implementation Manager
                                    @elseif($data->id_position == 'ENGINEER STAFF')
                                      Staff. Systems Engineer
                                    @endif
                                  @elseif($data->id_territory == 'DVG')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Development Manager
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Dev Ops
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @elseif($data->id_territory == 'SPECIALIST')
                                    @if($data->id_position == 'EXPERT ENGINEER')
                                      Expert Engineer
                                    @endif
                                  @else
                                    @if($data->id_position == 'MANAGER')
                                      Div. Technical Head
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'TECHNICAL PRESALES')
                                  @if($data->id_position == 'MANAGER')
                                    Dept. Presales Manager
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Presales Engineer
                                  @endif
                                @elseif($data->id_division == 'SALES')
                                  @if($data->id_territory == 'TERRITORY 1')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (First)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM1
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 2')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Second)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM2
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 3')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Third)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM3
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 4')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fourth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM4
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 5')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fifth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM5
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 6')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Sixth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM6
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'FINANCE')
                                  @if($data->id_position != 'FINANCE DIRECTOR')
                                    @if($data->id_territory == 'FINANCE')
                                      @if($data->id_position == 'STAFF')
                                        Staff. Finance
                                      @elseif($data->id_position == 'COURIER')
                                        Staff. Courier
                                      @endif
                                    @elseif($data->id_territory == 'ACC')
                                      @if($data->id_position == 'MANAGER')
                                        Div. Accounting
                                      @elseif($data->id_position == 'STAFF')
                                        Staff. Accounting
                                      @endif
                                    @endif
                                  @else
                                    Finance Director
                                  @endif
                                @elseif($data->id_territory == 'OPERATION')
                                  @if($data->id_division == null)
                                    Operation Director
                                  @elseif($data->id_division == 'PMO')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Project Management Office
                                    @elseif($data->id_position == 'PM')
                                      Staff. Project Manager
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. PMO Admin
                                    @endif
                                  @elseif($data->id_division == 'MSM')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Managed Services & Maintenance
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. MSM Admin
                                    @elseif($data->id_position == 'CALL SO')
                                      Staff. Call Center Operator
                                    @elseif($data->id_position == 'HELP DESK')
                                      Staff. Dedicated Help Desk
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                      Dept. Technical Support
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                      Staff. Support Engineer
                                    @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                      Dept. Services Project Manager
                                    @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                      Staff. Services Project Coordinator
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'HR')
                                  @if($data->id_position == 'HR MANAGER')
                                    Div. Human Resource Head
                                  @elseif($data->id_position == 'STAFF GA')
                                    Staff. General Affair
                                  @elseif($data->id_position == 'STAFF HR')
                                    Staff. Human Resource
                                  @endif
                                @else
                                  {{ $data->id_position }}
                                @endif
                              </td>
                              @else
                              <td>&#8212</td>
                              @endif
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <td>
                                <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                                <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                                </button></a>
                              </td>
                              @endif
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade margin-top" id="finance" role="tabpanel" aria-labelledby="finance-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_finance" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>NIK</th>
                              <th>Employees Name</th>
                              <th>Position</th>
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL' || Auth::User()->id_position == 'DIRECTOR')
                              <th>Action</th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($hr as $data)
                            @if($data->id_division == 'FINANCE')
                            <tr>
                              <td><?=str_replace('/', '', $data->nik)?></td>
                              <td>{{ $data->name }}</td>
                              @if($data->id_position != '')
                              <td>
                                @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                                  President Director
                                @elseif($data->id_division == 'TECHNICAL')
                                  @if($data->id_territory == 'DPG')
                                    @if($data->id_position == 'ENGINEER MANAGER')
                                      Dept. Implementation Manager
                                    @elseif($data->id_position == 'ENGINEER STAFF')
                                      Staff. Systems Engineer
                                    @endif
                                  @elseif($data->id_territory == 'DVG')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Development Manager
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Dev Ops
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @else
                                    @if($data->id_position == 'MANAGER')
                                      Div. Technical Head
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'TECHNICAL PRESALES')
                                  @if($data->id_position == 'MANAGER')
                                    Dept. Presales Manager
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Presales Engineer
                                  @endif
                                @elseif($data->id_division == 'SALES')
                                  @if($data->id_territory == 'TERRITORY 1')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (First)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM1
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 2')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Second)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM2
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 3')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Third)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM3
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 4')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fourth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM4
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 5')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fifth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM5
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 6')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Sixth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM6
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'FINANCE')
                                  @if($data->id_position != 'FINANCE DIRECTOR')
                                    @if($data->id_territory == 'FINANCE')
                                      @if($data->id_position == 'STAFF')
                                        Staff. Finance
                                      @elseif($data->id_position == 'COURIER')
                                        Staff. Courier
                                      @endif
                                    @elseif($data->id_territory == 'ACC')
                                      @if($data->id_position == 'MANAGER')
                                        Div. Accounting
                                      @elseif($data->id_position == 'STAFF')
                                        Staff. Accounting
                                      @endif
                                    @endif
                                  @else
                                    Finance Director
                                  @endif
                                @elseif($data->id_territory == 'OPERATION')
                                  @if($data->id_division == null)
                                    Operation Director
                                  @elseif($data->id_division == 'PMO')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Project Management Office
                                    @elseif($data->id_position == 'PM')
                                      Staff. Project Manager
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. PMO Admin
                                    @endif
                                  @elseif($data->id_division == 'MSM')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Managed Services & Maintenance
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. MSM Admin
                                    @elseif($data->id_position == 'CALL SO')
                                      Staff. Call Center Operator
                                    @elseif($data->id_position == 'HELP DESK')
                                      Staff. Dedicated Help Desk
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                      Dept. Technical Support
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                      Staff. Support Engineer
                                    @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                      Dept. Services Project Manager
                                    @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                      Staff. Services Project Coordinator
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'HR')
                                  @if($data->id_position == 'HR MANAGER')
                                    Div. Human Resource Head
                                  @elseif($data->id_position == 'STAFF GA')
                                    Staff. General Affair
                                  @elseif($data->id_position == 'STAFF HR')
                                    Staff. Human Resource
                                  @endif
                                @else
                                  {{ $data->id_position }}
                                @endif
                              </td>
                              @else
                              <td>&#8212</td>
                              @endif
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <td>
                                <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                                <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                                </button></a>
                              </td>
                              @endif
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade margin-top" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_sales" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>NIK</th>
                              <th>Employees Name</th>
                              <th>Position</th>
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <th>Action</th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($hr as $data)
                            @if($data->id_division == 'SALES')
                            <tr>
                              <td><?=str_replace('/', '', $data->nik)?></td>
                              <td>{{ $data->name }}</td>
                              @if($data->id_position != '')
                              <td>
                                @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                                  President Director
                                @elseif($data->id_division == 'TECHNICAL')
                                  @if($data->id_territory == 'DPG')
                                    @if($data->id_position == 'ENGINEER MANAGER')
                                      Dept. Implementation Manager
                                    @elseif($data->id_position == 'ENGINEER STAFF')
                                      Staff. Systems Engineer
                                    @endif
                                  @elseif($data->id_territory == 'DVG')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Development Manager
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Dev Ops
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @else
                                    @if($data->id_position == 'MANAGER')
                                      Div. Technical Head
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'TECHNICAL PRESALES')
                                  @if($data->id_position == 'MANAGER')
                                    Dept. Presales Manager
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Presales Engineer
                                  @endif
                                @elseif($data->id_division == 'SALES')
                                  @if($data->id_territory == 'TERRITORY 1')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (First)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM1
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 2')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Second)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM2
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 3')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Third)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM3
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 4')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fourth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM4
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 5')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fifth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM5
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 6')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Sixth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM6
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. Admin Sales
                                    @endif
                                  @elseif($data->id_territory == 'SPECIALIST')
                                    @if($data->id_position == 'EXPERT SALES')
                                      Expert Sales
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'FINANCE')
                                  @if($data->id_position != 'FINANCE DIRECTOR')
                                    @if($data->id_territory == 'FINANCE')
                                      @if($data->id_position == 'STAFF')
                                        Staff. Finance
                                      @elseif($data->id_position == 'COURIER')
                                        Staff. Courier
                                      @endif
                                    @elseif($data->id_territory == 'ACC')
                                      @if($data->id_position == 'MANAGER')
                                        Div. Accounting
                                      @elseif($data->id_position == 'STAFF')
                                        Staff. Accounting
                                      @endif
                                    @endif
                                  @else
                                    Finance Director
                                  @endif
                                @elseif($data->id_territory == 'OPERATION')
                                  @if($data->id_division == null)
                                    Operation Director
                                  @elseif($data->id_division == 'PMO')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Project Management Office
                                    @elseif($data->id_position == 'PM')
                                      Staff. Project Manager
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. PMO Admin
                                    @endif
                                  @elseif($data->id_division == 'MSM')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Managed Services & Maintenance
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. MSM Admin
                                    @elseif($data->id_position == 'CALL SO')
                                      Staff. Call Center Operator
                                    @elseif($data->id_position == 'HELP DESK')
                                      Staff. Dedicated Help Desk
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                      Dept. Technical Support
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                      Staff. Support Engineer
                                    @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                      Dept. Services Project Manager
                                    @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                      Staff. Services Project Coordinator
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'HR')
                                  @if($data->id_position == 'HR MANAGER')
                                    Div. Human Resource Head
                                  @elseif($data->id_position == 'STAFF GA')
                                    Staff. General Affair
                                  @elseif($data->id_position == 'STAFF HR')
                                    Staff. Human Resource
                                  @endif
                                  @elseif($data->id_territory == 'SPECIALIST')
                                  @if($data->id_position == 'EXPERT ENGINEER')
                                    Expert Engineer
                                  @endif
                                @else
                                  {{ $data->id_position }}
                                @endif
                              </td>
                              @else
                              <td>&#8212</td>
                              @endif
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <td>
                                <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                                <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                                </button></a>
                              </td>
                              @endif
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade margin-top" id="operation" role="tabpanel" aria-labelledby="operation-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="data_operation" width="100%" cellspacing="0">
                          <thead>
                            <tr>
                              <th>NIK</th>
                              <th>Employees Name</th>
                              <th>Position</th>
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <th>Action</th>
                              @endif
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($hr as $data)
                            @if($data->id_territory == 'OPERATION')
                            <tr>
                              <td><?=str_replace('/', '', $data->nik)?></td>
                              <td>{{ $data->name }}</td>
                              @if($data->id_position != '')
                              <td>
                                @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                                  President Director
                                @elseif($data->id_division == 'TECHNICAL')
                                  @if($data->id_territory == 'DPG')
                                    @if($data->id_position == 'ENGINEER MANAGER')
                                      Dept. Implementation Manager
                                    @elseif($data->id_position == 'ENGINEER STAFF')
                                      Staff. Systems Engineer
                                    @endif
                                  @elseif($data->id_territory == 'DVG')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Development Manager
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Dev Ops
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @else
                                    @if($data->id_position == 'MANAGER')
                                      Div. Technical Head
                                    @elseif($data->id_position == 'INTERNAL IT')
                                      Staff. Internal IT Engineer
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. TEC Admin
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'TECHNICAL PRESALES')
                                  @if($data->id_position == 'MANAGER')
                                    Dept. Presales Manager
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Presales Engineer
                                  @endif
                                @elseif($data->id_division == 'SALES')
                                  @if($data->id_territory == 'TERRITORY 1')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (First)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM1
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 2')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Second)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM2
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 3')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Third)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM3
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 4')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fourth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM4
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 5')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Fifth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM5
                                    @endif
                                  @elseif($data->id_territory == 'TERRITORY 6')
                                    @if($data->id_position == 'MANAGER')
                                      Dept. Account Manager (Sixth)
                                    @elseif($data->id_position == 'STAFF')
                                      Staff. Account Executive AM6
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'FINANCE')
                                  @if($data->id_position != 'FINANCE DIRECTOR')
                                    @if($data->id_territory == 'FINANCE')
                                      @if($data->id_position == 'STAFF')
                                        Staff. Finance
                                      @elseif($data->id_position == 'COURIER')
                                        Staff. Courier
                                      @endif
                                    @elseif($data->id_territory == 'ACC')
                                      @if($data->id_position == 'MANAGER')
                                        Div. Accounting
                                      @elseif($data->id_position == 'STAFF')
                                        Staff. Accounting
                                      @endif
                                    @endif
                                  @else
                                    Finance Director
                                  @endif
                                @elseif($data->id_territory == 'OPERATION')
                                  @if($data->id_division == null)
                                    Operation Director
                                  @elseif($data->id_division == 'PMO')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Project Management Office
                                    @elseif($data->id_position == 'PM')
                                      Staff. Project Manager
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. PMO Admin
                                    @endif
                                  @elseif($data->id_division == 'MSM')
                                    @if($data->id_position == 'MANAGER')
                                      Div. Managed Services & Maintenance
                                    @elseif($data->id_position == 'ADMIN')
                                      Staff. MSM Admin
                                    @elseif($data->id_position == 'CALL SO')
                                      Staff. Call Center Operator
                                    @elseif($data->id_position == 'HELP DESK')
                                      Staff. Dedicated Help Desk
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                      Dept. Technical Support
                                    @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                      Staff. Support Engineer
                                    @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                      Dept. Services Project Manager
                                    @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                      Staff. Services Project Coordinator
                                    @endif
                                  @endif
                                @elseif($data->id_division == 'HR')
                                  @if($data->id_position == 'HR MANAGER')
                                    Div. Human Resource Head
                                  @elseif($data->id_position == 'STAFF GA')
                                    Staff. General Affair
                                  @elseif($data->id_position == 'STAFF HR')
                                    Staff. Human Resource
                                  @endif
                                @else
                                  {{ $data->id_position }}
                                @endif
                              </td>
                              @else
                              <td>&#8212</td>
                              @endif
                              @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                              <td>
                                <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                                <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                                </button></a>
                              </td>
                              @endif
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
              </div>

            </div>
            <div class="tab-pane fade margin-top" id="MSP" role="tabpanel" aria-labelledby="msp-tab">
           
              <div class="table-responsive">
                    <table class="table table-bordered" id="data_all_msp" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>NIK</th>
                          <th>Employees Name</th>
                          <th>Position</th>
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($hr as $data)
                        @if($data->id_company == '2')
                        <tr>
                          <td><?=str_replace('/', '', $data->nik)?></td>
                          <td>{{ $data->name }}</td>
                          @if($data->id_position != '')
                          <td>
                            @if($data->id_position == 'DIRECTOR' && $data->id_division == '')
                              President Director
                            @elseif($data->id_division == 'TECHNICAL')
                              @if($data->id_territory == 'DPG')
                                @if($data->id_position == 'ENGINEER MANAGER')
                                  Dept. Implementation Manager
                                @elseif($data->id_position == 'ENGINEER STAFF')
                                  Staff. Systems Engineer
                                @endif
                              @elseif($data->id_territory == 'DVG')
                                @if($data->id_position == 'MANAGER')
                                  Dept. Development Manager
                                @elseif($data->id_position == 'STAFF')
                                  Staff. Dev Ops
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @elseif($data->id_territory == 'SPECIALIST')
                                @if($data->id_position == 'EXPERT ENGINEER')
                                  Expert Engineer
                                @endif
                              @else
                                @if($data->id_position == 'MANAGER')
                                  Div. Technical Head
                                @elseif($data->id_position == 'INTERNAL IT')
                                  Staff. Internal IT Engineer
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. TEC Admin
                                @endif
                              @endif
                            @elseif($data->id_division == 'TECHNICAL PRESALES')
                              @if($data->id_position == 'MANAGER')
                                Dept. Presales Manager
                              @elseif($data->id_position == 'STAFF')
                                Staff. Presales Engineer
                              @endif
                            @elseif($data->id_division == 'SALES')
                              @if($data->id_position == 'MANAGER')
                                Dept. MSP Sales
                              @else
                                Staff. Sales Executive
                              @endif
                            @elseif($data->id_division == 'FINANCE')
                              @if($data->id_position != 'FINANCE DIRECTOR')
                                @if($data->id_territory == 'FINANCE')
                                  @if($data->id_position == 'STAFF')
                                    Staff. Finance
                                  @elseif($data->id_position == 'COURIER')
                                    Staff. Courier
                                  @endif
                                @elseif($data->id_territory == 'ACC')
                                  @if($data->id_position == 'MANAGER')
                                    Div. Accounting
                                  @elseif($data->id_position == 'STAFF')
                                    Staff. Accounting
                                  @endif
                                @endif
                              @else
                                Finance Director
                              @endif
                            @elseif($data->id_territory == 'OPERATION')
                              @if($data->id_division == null)
                                Operation Director
                              @elseif($data->id_division == 'PMO')
                                @if($data->id_position == 'MANAGER')
                                  Div. Project Management Office
                                @elseif($data->id_position == 'PM')
                                  Staff. Project Manager
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. PMO Admin
                                @endif
                              @elseif($data->id_division == 'MSM')
                                @if($data->id_position == 'MANAGER')
                                  Div. Managed Services & Maintenance
                                @elseif($data->id_position == 'ADMIN')
                                  Staff. MSM Admin
                                @elseif($data->id_position == 'CALL SO')
                                  Staff. Call Center Operator
                                @elseif($data->id_position == 'HELP DESK')
                                  Staff. Dedicated Help Desk
                                @elseif($data->id_position == 'SUPPORT ENGINEER(HEAD)')
                                  Dept. Technical Support
                                @elseif($data->id_position == 'SUPPORT ENGINEER(STAFF)')
                                  Staff. Support Engineer
                                @elseif($data->id_position == 'SERVICE PROJECT(HEAD)')
                                  Dept. Services Project Manager
                                @elseif($data->id_position == 'SERVICE PROJECT(STAFF)')
                                  Staff. Services Project Coordinator
                                @endif
                              @endif
                            @elseif($data->id_division == 'HR')
                              @if($data->id_position == 'HR MANAGER')
                                Div. Human Resource Head
                              @elseif($data->id_position == 'STAFF GA')
                                Staff. General Affair
                              @elseif($data->id_position == 'STAFF HR')
                                Staff. Human Resource
                              @endif
                            @elseif($data->id_position == 'ADMIN')
                              Staff. Admin MSP
                            @else
                              {{ $data->id_position }}
                            @endif
                          </td>
                          @else
                          <td>&#8212</td>
                          @endif
                          @if(Auth::User()->id_position == 'HR MANAGER' || Auth::User()->id_division == 'TECHNICAL'|| Auth::User()->id_position == 'DIRECTOR')
                          <td>
                            <button class="btn btn-sm btn-primary fa fa-search-plus fa-lg" data-target="#modal_update" data-toggle="modal" style="width: 40px;height: 40px" onclick="update_HR('{{$data->nik}}','{{$data->name}}','{{$data->email}}','{{$data->date_of_entry}}','{{$data->date_of_birth}}','{{$data->address}}','{{$data->phone}}','{{$data->password}}')"></button>

                            <a href="{{ url('delete_hr', $data->nik) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data? And this data is not used in other table')">
                            </button></a>
                          </td>
                          @endif
                        </tr>
                        @endif
                        @endforeach
                      </tbody>
                    </table>
                </div>

            </div>
          </div>
        </div>

        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>

  </div>

</div>

  <div class="modal fade" id="modalAdd" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Employees</h4>
        </div>
        <div class="modal-body">
        <form method="POST" action="{{url('hu_rec/store')}}">
                        @csrf

                        <div class="form-group row" hidden>
                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK') }}</label>

                            <div class="col-md-8">
                                <input id="nik" type="text" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik" value="{{ old('nik') }}" readonly required autofocus>

                                @if ($errors->has('nik'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nik') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4.5 col-form-label text-md-right margin-left-custom3">{{ __('Employees Name') }}</label>

                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control margin-left-custom2{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} float-left margin-left-custom-psw" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" ></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4.5 col-form-label text-md-right margin-left-custom3">{{ __('Confirm Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control margin-left-custom2" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

                            <div class="col-md-8">
                                <select id="company" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company" value="{{ old('company') }}" onkeyup="copytextbox();" required autofocus>
                                    <option value="">-- Select Company --</option>
                                    <option value="1" data-target="sip" id="1">SIP</option>
                                    <option value="2" data-target="msp" id="2">MSP</option>
                                </select>
                                @if ($errors->has('company'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--SIP-->

                        <div class="form-group row"  style="display:none;"  id="company-sip">
                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Division') }}</label>

                            <div class="col-md-8">
                                <select id="division" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_sip" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select division --</option>
                                    <option value="TECHNICAL" data-target="technical" id="technical">TECHNICAL</option>
                                    <option value="FINANCE" data-target="finance" id="finance">FINANCE and ACCOUNTING</option>
                                    <option value="HR" data-target="hr" id="hr">HUMAN RESOURCE</option>
                                    <option value="SALES" data-target="sales" id="sales">SALES</option>
                                    <option value="OPERATION" data-target="operation" id="operation">OPERATION</option>
                                    <option value="SPECIALIST" data-target="specialist" id="specialist">OTHER</option>
                                    <option value="NULL" data-target="director" id="director">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--DIRECTOR-->
                        <div class="form-group row"  style="display:none;"  id="division-director">
                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-dir" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_dir" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--expert sales-->
                        <div class="form-group row"  style="display:none;"  id="division-specialist" >

                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

                            <div class="col-md-8">
                                <select id="territory-expert-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory_expert" value="{{ old('expert_sales') }}" autofocus>
                                    <option value="">-- Select Territory --</option>
                                </select>
                                @if ($errors->has('territory'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('territory') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-expert-sales" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" name="pos_expert_sales" value="{{ old('expert_sales') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                    <option value="EXPERT SALES">EXPERT SALES</option>
                                    <option value="EXPERT ENGINEER">EXPERT ENGINEER</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Technical -->
                        <div class="form-group row"  style="display:none;"  id="division-technical">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-tech" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="DPG" data-target="dvg" id="dvg">IMPLEMENTATION</option>
                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
                                    <option value="DVG" data-target="dvg" id="dvg">DEVELOPMENT</option>
                                    <option value="NONE" data-target="dpg" id="dpg">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-tech" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Sales -->
                        <div class="form-group row"  style="display:none;"  id="division-sales" >

                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

                            <div class="col-md-8">
                                <select id="territory-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Territory --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-sales" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                    <option value="MANAGER">MANAGER</option>
                                    <option value="STAFF">STAFF</option>
                                    <option value="ADMIN">ADMIN</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Finance -->
                        <div class="form-group row"  style="display:none;"  id="division-finance">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-finance" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_finance" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="FINANCE" data-target="dvg" id="dvg">FINANCE</option>
                                    <option value="ACC" data-target="dpg" id="dpg">ACCOUNTING</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-finance" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_finance" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Operation -->
                        <div class="form-group row"  style="display:none;"  id="division-operation">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-operation" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_operation" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="MSM" data-target="MSM" id="MSM">MSM</option>
                                    <option value="PMO" data-target="PMO" id="PMO">PMO</option>
                                    <option value="DIR" data-target="DIR" id="PMO">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-operation" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_operation" autofocus>
                                  <option value="">-- Select position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- HR -->
                        <div class="form-group row"  style="display:none;"  id="division-hr">
                            <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-hr" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_hr" value="{{ old('division') }}" autofocus>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- MSP -->

                        <div class="form-group row"  style="display:none;"  id="company-msp">
                            <label for="division-msp" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

                            <div class="col-md-8">
                                <select id="division-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Division --</option>
                                    <option value="SALES_MSP" data-target="sales_msp" id="sales_msp">SALES</option>
                                    <option value="TECHNICAL" data-target="TECHNICAL_MSP" id="TECHNICAL_MSP">TECHNICAL</option>
                                    <option value="ADMIN_MSP" data-target="sales_msp">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row"  style="display:none;"  id="division-msp-sales_msp">
                          <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-sales-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_msp" value="{{ old('division') }}" autofocus>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row"  style="display:none;"  id="division-msp-TECHNICAL_MSP">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-tech-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_msp" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
                                    <option value="NONE_MSP" data-target="dpg" id="dpg">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-tech-msp" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_msp" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_of_entry" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Entry') }}</label>

                            <div class="col-md-8">
                                <input id="date_of_entry" type="date" class="form-control{{ $errors->has('date_of_entry') ? ' is-invalid' : '' }}" name="date_of_entry" value="{{ old('date_of_entry') }}" onkeyup="copytextbox();" required autofocus>

                                @if ($errors->has('date_of_entry'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_of_entry') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Birth') }}</label>

                            <div class="col-md-8">
                                <input id="date_of_birth" type="date" class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth" value="{{ old('date_of_birth') }}" onkeyup="copytextbox();" required autofocus>

                                @if ($errors->has('date_of_birth'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-8">
                                <textarea id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" autofocus></textarea>

                                @if ($errors->has('address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-8">
                                <input id="phone_number" type="number" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" autofocus>

                                @if ($errors->has('phone_number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">
                      {{ __('Register') }}
                  </button>
                </div>
          </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal_update" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Detail Employees</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('hu_rec/update')}}">
                        @csrf
                        <div class="form-group row" hidden>
                            <label for="nik" class="col-md-4 col-form-label text-md-right">{{ __('NIK') }}</label>

                            <div class="col-md-8">
                                <input id="nik_update" type="text" class="form-control{{ $errors->has('nik') ? ' is-invalid' : '' }}" name="nik_update" value="{{ old('nik') }}" autofocus>

                                @if ($errors->has('nik'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nik') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-md-4.5 col-form-label text-md-left margin-left-custom3">{{ __('Employees Name') }}</label>

                            <div class="col-md-8">
                                <input id="name_update" type="text" class="form-control margin-left-custom{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-8">
                                <input id="email_update" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company" class="col-md-4 col-form-label text-md-right">{{ __('Company') }}</label>

                            <div class="col-md-8">
                                <select id="company_update" class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}" name="company_update" value="{{ old('company') }}" onkeyup="copytextbox();" required autofocus>
                                    <option value="">-- Select Company --</option>
                                    <option value="1" data-target="sip" id="1">SIP</option>
                                    <option value="2" data-target="msp" id="2">MSP</option>
                                </select>
                                @if ($errors->has('company'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                      <!--SIP-->
                        <div class="form-group row"  style="display:none;"  id="company_update-sip">
                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Division') }}</label>

                            <div class="col-md-8">
                                <select id="division_update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select division --</option>
                                    <option value="TECHNICAL" data-target="technical" id="technical">TECHNICAL</option>
                                    <option value="FINANCE" data-target="finance" id="finance">FINANCE and ACCOUNTING</option>
                                    <option value="HR" data-target="hr" id="hr">HUMAN RESOURCE</option>
                                    <option value="SALES" data-target="sales" id="sales">SALES</option>
                                    <option value="OPERATION" data-target="operation" id="operation">OPERATION</option>
                                    <option value="NULL" data-target="director" id="director">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!--DIRECTOR-->
                        <div class="form-group row"  style="display:none;"  id="division_update-director">
                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-dir-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_dir_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Technical -->
                        <div class="form-group row"  style="display:none;"  id="division_update-technical">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-tech-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="DPG" data-target="dvg" id="dvg">IMPLEMENTATION</option>
                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
                                    <option value="DVG" data-target="dvg" id="dvg">DEVELOPMENT</option>
                                    <option value="NONE" data-target="dpg" id="dpg">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-tech-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Sales -->
                        <div class="form-group row"  style="display:none;"  id="division_update-sales" >

                            <label for="territory" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Territory') }}</label>

                            <div class="col-md-8">
                                <select id="territory-sales-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="territory_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Territory --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-sales-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                    <option value="MANAGER">MANAGER</option>
                                    <option value="STAFF">STAFF</option>
                                    <option value="ADMIN">ADMIN</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Finance -->
                        <div class="form-group row"  style="display:none;"  id="division_update-finance">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-finance-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_finance_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="FINANCE" data-target="dvg" id="dvg">FINANCE</option>
                                    <option value="ACC" data-target="dpg" id="dpg">ACCOUNTING</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-finance-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_finance_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Operation -->
                        <div class="form-group row"  style="display:none;"  id="division_update-operation">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-operation-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_operation_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="MSM" data-target="MSM" id="MSM">MSM</option>
                                    <option value="PMO" data-target="PMO" id="PMO">PMO</option>
                                    <option value="DIR" data-target="DIR" id="PMO">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="division" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-operation-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_operation_update" autofocus>
                                  <option value="">-- Select position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- HR -->
                        <div class="form-group row"  style="display:none;"  id="division_update-hr">
                            <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-hr-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_hr_update" value="{{ old('division') }}" autofocus>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <!-- MSP -->

                       <!--  <div class="form-group row"  style="display:none;"  id="company_update-msp">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

                            <div class="col-md-8">
                                <select id="division-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Division --</option>
                                    <option value="SALES_MSP" data-target="sales_msp" id="sales_msp">SALES</option>
                                    <option value="ADMIN_MSP" >NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-sales-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group row"  style="display:none;"  id="company_update-msp">
                            <label for="division-msp" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Division') }}</label>

                            <div class="col-md-8">
                                <select id="division-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="division_msp_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Division --</option>
                                    <option value="SALES_MSP" data-target="sales_msp_update" id="sales_msp">SALES</option>
                                    <option value="TECHNICAL_MSP" data-target="technical_msp_update" id="TECHNICAL_MSP">TECHNICAL</option>
                                    <option value="ADMIN_MSP" data-target="sales_msp_update">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row"  style="display:none;"  id="division-msp-update-sales_msp_update">
                          <label for="position" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-sales-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_sales" value="{{ old('division') }}" autofocus>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row"  style="display:none;"  id="division-msp-update-technical_msp_update">
                            <label for="division" class="col-md-4 col-form-label text-md-right" style="margin-bottom: 15px;">{{ __('Sub Division') }}</label>

                            <div class="col-md-8">
                                <select id="subdivision-tech-msp_update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="id_sub_division_tech_msp_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Sub Division --</option>
                                    <option value="PRESALES" data-target="dpg" id="dpg">PRESALES</option>
                                    <option value="NONE_MSP" data-target="dpg" id="dpg">NONE</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="position" class="col-md-4 col-form-label text-md-right">{{ __('Position') }}</label>

                            <div class="col-md-8">
                                <select id="position-tech-msp-update" class="form-control{{ $errors->has('division') ? ' is-invalid' : '' }}" name="pos_tech_msp_update" value="{{ old('division') }}" autofocus>
                                    <option value="">-- Select Position --</option>
                                </select>
                                @if ($errors->has('division'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>





                        <div class="form-group row">
                            <label for="date_of_entry" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Entry') }}</label>

                            <div class="col-md-8">
                                <input id="date_of_entry_update" type="date" class="form-control{{ $errors->has('date_of_entry') ? ' is-invalid' : '' }}" name="date_of_entry_update" value="{{ old('date_of_entry') }}" onkeyup="copytextbox();" required autofocus>

                                @if ($errors->has('date_of_entry'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_of_entry') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date_of_birth" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Birth') }}</label>

                            <div class="col-md-8">
                                <input id="date_of_birth_update" type="date" class="form-control{{ $errors->has('date_of_birth') ? ' is-invalid' : '' }}" name="date_of_birth_update" value="{{ old('date_of_birth') }}" onkeyup="copytextbox();" required autofocus>

                                @if ($errors->has('date_of_birth'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-8">
                                <textarea id="address_update" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" value="{{ old('address') }}" autofocus></textarea>

                                @if ($errors->has('address'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-8">
                                <input id="phone_number_update" type="number" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" autofocus>

                                @if ($errors->has('phone_number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">
                      {{ __('Update') }}
                  </button>
                </div>
          </form>
        </div>
      </div>
      
    </div>

  </div>

@endsection

  <!-- <script type="text/javascript">
    function copytextbox(){
      var date_of_entry = document.getElementById('date_of_entry').value;
      var date_of_birth = document.getElementById('date_of_birth').value;

      document.getElementById('nik').value = document.getElementById('company').value + date_of_entry.substr(2, 2) + date_of_entry.substr(5, 2) + date_of_birth.substr(2, 2) + date_of_birth.substr(5, 2);
    }

  </script> -->

  @section('script')
    <script type="">
       // function copytextbox(){
         /* var date_of_entry = document.getElementById('date_of_entry').value;
          var date_of_birth = document.getElementById('date_of_birth').value;*/
/*
          document.getElementById('nik').value = document.getElementById('company').value + date_of_entry.substr(2, 2) + date_of_entry.substr(5, 2) + date_of_birth.substr(2, 2) + date_of_birth.substr(5, 2) */
        //}

       function update_HR(nik,name,email,date_of_entry,date_of_birth,address,phone){
         $("#nik_update").val(nik);
         $("#name_update").val(name);
         $("#email_update").val(email);/*
         $("#company_update").val(id_company);
         $("#division_update").val(id_division);
         $("#position_update").val(id_position);
         $("#territory_update").val(id_territory);*/
         $("#date_of_entry_update").val(date_of_entry);
         $("#date_of_birth_update").val(date_of_birth);
         $("#address_update").val(address);
         $("#phone_number_update").val(phone);
         $("#password_update").val(password);
       } 

       $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        });

         $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
          });

      $(document).ready(function(){
        $('#company').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
            $("#division-director").hide();
            $("#division-specialist").hide();
            $("#division-technical").hide();
            $("#division-sales").hide();
            $("#division-finance").hide();
            $("#division-operation").hide();
            $("#division-hr").hide();
          });
      });

      $(document).ready(function(){
          $('#division').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $(document).ready(function(){
          $('#division-msp').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-dir').html(append)
                var append = "<option > </option>";

                if (result[1] == 'NULL') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-dir').html(append);
              },
          });
      });

      $('#subdivision-tech').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech').html(append)
                var append = "<option> </option>";

                if (result[1] == 'DPG') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PRESALES') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DVG') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-tech').html(append);
              },
          });
      });

      $('#subdivision-finance').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-finance').html(append)
                var append = "<option > </option>";

                if (result[1] == 'FINANCE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ACC') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } 

                $('#position-finance').html(append);
              },
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-expert-sales').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SPECIALIST') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-expert-sales').html(append);
              },
          });
      });

      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-sales').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-sales').html(append);
              },
          });
      });

      $('#subdivision-operation').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-operation').html(append)
                var append = "<option > </option>";

                if (result[1] == 'MSM') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PMO') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DIR') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-operation').html(append);
              },
          });
      });


      $('#division').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-hr').html(append)
                var append = "<option > </option>";

                if (result[1] == 'HR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-hr').html(append);
              },
          });
      });


      $('#division-msp').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-sales-msp').html(append)
                var append = "<option> -- Select Option --</option>";

                if (result[1] == 'SALES_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ADMIN_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-sales-msp').html(append);
              },
          });
      });

      $('#subdivision-tech-msp').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-msp').html(append)
                var append = "<option> -- Select Option --</option>";

                if (result[1] == 'PRESALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE_MSP') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-tech-msp').html(append);
              },
          });
      });

      //update
      $(document).ready(function(){
        $('#company_update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
            $("#"+id+"-"+target).show();
            $("#division_update-director").hide();
            $("#division_update-technical").hide();
            $("#division_update-sales").hide();
            $("#division_update-operation").hide();
            $("#division_update-hr").hide();
          });
      });

      $(document).ready(function(){
          $('#division_update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $(document).ready(function(){
          $('#division-msp-update').on('change', function() {
             var target=$(this).find(":selected").attr("data-target");
             var id=$(this).attr("id");
            $("div[id^='"+id+"']").hide();
           $("#"+id+"-"+target).show();
           $("#"+id+"-"+target).show();
          });
      });

      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-dir-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'NULL') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-dir-update').html(append);
              },
          });
      });

      $('#subdivision-tech-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-update').html(append)
                var append = "<option> </option>";

                if (result[1] == 'DPG') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PRESALES') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DVG') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-tech-update').html(append);
              },
          });
      });

      $('#subdivision-finance-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-finance-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'FINANCE') {
                  $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ACC') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } 

                $('#position-finance-update').html(append);
              },
          });
      });

      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#territory-sales-update').html(append)
                var append = "<option> </option>";

                if (result[1] == 'SALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_territory + "</option>";
                });
                } 

                $('#territory-sales-update').html(append);
              },
          });
      });

      $('#subdivision-operation-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-operation-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'MSM') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'PMO') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'DIR') {
                  $.each(result[0], function(key, value){
                  /*console.log(value);*/
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }


                $('#position-operation-update').html(append);
              },
          });
      });


      $('#division_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-hr-update').html(append)
                var append = "<option > </option>";

                if (result[1] == 'HR') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-hr-update').html(append);
              },
          });
      });


       $('#division-msp-update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-sales-msp-update').html(append)
                var append = "<option>-- Select Option --</option>";

                if (result[1] == 'SALES_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'ADMIN_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-sales-msp-update').html(append);
              },
          });
      });


       $('#subdivision-tech-msp_update').change(function(){
              $.ajax({
              type:"GET",
              url:'/dropdownTech',
              data:{
                id_assign:this.value,
              },
              success: function(result){
                $('#position-tech-msp-update').html(append)
                var append = "<option>-- Select Option --</option>";

                if (result[1] == 'PRESALES') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                } else if (result[1] == 'NONE_MSP') {
                $.each(result[0], function(key, value){
                  append = append + "<option>" + value.name_position + "</option>";
                });
                }

                $('#position-tech-msp-update').html(append);
              },
          });
      });
      

      $('#data_all').DataTable( {
        } );

      $('#data_all_msp').DataTable( {
        } );

      $('#data_tech').DataTable( {
        } );

      $('#data_finance').DataTable( {
        } );

      $('#data_sales').DataTable( {
        } );

      $('#data_operation').DataTable( {
        } );
    </script>
  @endsection