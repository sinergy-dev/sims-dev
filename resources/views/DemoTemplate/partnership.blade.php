@extends('template.template')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Partnership Summary</a>
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

  <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> <b>Partnership Summary</b>
          @if(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ADMIN' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_position == 'INTERNAL IT')
          <button type="button" class="btn btn-success-sales pull-right float-right margin-left-custom" data-target="#modalAddPartnership" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspPartnership
          </button>
          <div class="pull-right">
            <button type="button" class="btn btn-warning-eksport dropdown-toggle float-right  margin-left-customt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><b><i class="fa fa-download"></i> Export</b>
            </button>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
              <a class="dropdown-item" href="{{action('PartnershipController@downloadpdf')}}"> PDF </a>
              <a class="dropdown-item" href="{{action('PartnershipController@downloadExcel')}}"> EXCEL </a>
            </div>
          </div>
          @endif
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered display nowrap" id="datastable" style="width: 100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Type</th>
                  <th>Partner</th>
                  <th>Level</th>
                  <th>Renewal Date</th>
                  <th>Annual Fee</th>
                  <th>Sales Target</th>
                  <th>Sales Certification</th>
                  <th>Engineer Certification</th>
                  @if(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ADMIN' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_position == 'INTERNAL IT')
                  <th>Action</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; ?>
                @foreach($datas as $data)
                <tr>
                  <td>{{ $no++}}</td>
                  <td>{{ $data->type }}</td>
                  <td>{{ $data->partner }}</td>
                  <td>{{ $data->level }}</td>
                  <td>{{ $data->renewal_date }}</td>
                  <td>{{ $data->annual_fee }}</td>
                  <td>{{ $data->sales_target }}</td>
                  <td>{{ $data->sales_certification }}</td>
                  <td>{{ $data->engineer_certification }}</td>
                  @if(Auth::User()->id_territory == 'DVG' && Auth::User()->id_position == 'MANAGER' || Auth::User()->id_position == 'ADMIN' && Auth::User()->id_territory == 'DVG' || Auth::User()->id_position == 'INTERNAL IT')
                  <td>

                    <button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEdit" data-toggle="modal" style="width:50px; height:20px;vertical-align: top;" onclick="partnership('{{$data->id_partnership}}', '{{$data->type}}','{{$data->partner}}', '{{$data->level}}','{{$data->renewal_date}}','{{$data->annual_fee}}','{{$data->sales_target}}','{{$data->sales_certification}}','{{$data->engineer_certification}}')">&nbspEdit</button>

                    <a href="{{ url('delete_partnership', $data->id_partnership) }}"><button class="btn btn-sm btn-danger fa fa-trash" style="width:60px; height:20px;vertical-align: top;" onclick="return confirm('Are you sure want to delete this data?')">&nbspDelete
                    </button></a>

                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
    </div>
  </div>

    <!--MODAL-->
    <!--MODAL ADD INCIDENT-->
    <div class="modal fade" id="modalAddPartnership" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Partnership</h4>
        </div>
        <div class="modal-body">
          <form action="/store_partnership" method="POST" id="modalAdd" name="modalAdd">
            @csrf

            <div class="form-group">
                <label>Type</label>
                <select class="form-control" id="type" name="type" required>
                      <option value="Network">Network</option>
                      <option value="Server">Server</option>
                      <option value="Security">Security</option>
                      <option value="Other">Other</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Partner</label>
                <input class="form-control" placeholder="Enter Partner" id="partner" name="partner" required>
            </div>

            <div class="form-group">
                <label>Level</label>
                <input class="form-control" placeholder="Enter Level" id="level" name="level" required>
            </div>

            <div class="form-group">
                <label>Renewal Date</label>
                <input type="date" class="form-control" id="renewal_date" name="renewal_date">
            </div>

            <div class="form-group">
                <label>Annual Fee</label>
                <input class="form-control" placeholder="Enter Annual Fee" id="annual_fee" name="annual_fee">
            </div>

            <div class="form-group">
                <label>Sales Target</label>
                <input class="form-control" placeholder="Enter Sales Target" id="sales_target" name="sales_target">
            </div>

            <div class="form-group">
                <label>Sales Certification</label>
                <input class="form-control" placeholder="Enter Sales Certification" id="sales_certification" name="sales_certification">
            </div>

            <div class="form-group">
                <label>Engineer Certification</label>
                <input class="form-control" placeholder="Enter Engineer Certification" id="engineer_certification" name="engineer_certification">
            </div>         
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
              <!-- <input type="button" name="add_incident" id="add_incident" class="btn btn-sm btn-primary" value="Submit" /> -->
            </div>
        </form>
        </div>
      </div>
    </div>
</div>
  
  <!--MODAL EDIT INCIDENT-->
  <div class="modal fade" id="modalEdit" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Incident</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/update_partnership')}}" id="modalEditPartnership" name="modalEditPartnership">
            @csrf

            <input type="text" name="edit_id" id="edit_id" hidden>

            <div class="form-group">
                <label>Type</label>
                <select class="form-control" id="edit_type" name="edit_type" required>
                      <option value="Network">Network</option>
                      <option value="Server">Server</option>
                      <option value="Security">Security</option>
                      <option value="Other">Other</option>
                  </select>
            </div>

            <div class="form-group">
                <label>Partner</label>
                <input class="form-control" id="edit_partner" name="edit_partner" required>
            </div>

            <div class="form-group">
                <label>Level</label>
                <input class="form-control" id="edit_level" name="edit_level" required>
            </div>

            <div class="form-group">
                <label>Renewal Date</label>
                <input type="date" class="form-control" id="edit_renewal_date" name="edit_renewal_date">
            </div>

            <div class="form-group">
                <label>Annual Fee</label>
                <input class="form-control" id="edit_annual_fee" name="edit_annual_fee">
            </div>

            <div class="form-group">
                <label>Sales Target</label>
                <input class="form-control" id="edit_sales_target" name="edit_sales_target">
            </div>

            <div class="form-group">
                <label>Sales Certification</label>
                <input class="form-control" id="edit_sales_certification" name="edit_sales_certification">
            </div>

            <div class="form-group">
                <label>Engineer Certification</label>
                <input class="form-control" id="edit_engineer_certification" name="edit_engineer_certification">
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

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript">
     $('#datastable').DataTable( {
        "scrollX": true
      });

     $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
      });

     $('.money').mask('000,000,000,000,000.00', {reverse: true});

     function partnership(id_partnership,type,partner,level,renewal_date,annual_fee,sales_target,sales_certification,engineer_certification) {
      $('#edit_id').val(id_partnership);
      $('#edit_type').val(type);
      $('#edit_partner').val(partner);
      $('#edit_level').val(level);
      $('#edit_renewal_date').val(renewal_date);
      $('#edit_annual_fee').val(annual_fee);
      $('#edit_sales_target').val(sales_target);
      $('#edit_sales_certification').val(sales_certification);
      $('#edit_engineer_certification').val(engineer_certification);
    }
  </script>
@endsection