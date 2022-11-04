@extends('template.main')
@section('tittle')
  Invoice
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.1/css/fixedColumns.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <style type="text/css">
    th {
      text-align: center;
    }

    td>.truncate{
      word-break:break-all;
      white-space: normal;
      width:200px;  
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
    Daftar Buku Admin (Invoice)
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Admin</li>
    <li class="active">Invoice</li>
  </ol>
</section>

<section class="content">

  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success" id="alert">
      {{ session('success') }}
    </div>
  @endif

  <div class="row" id="BoxId">
      <!--box id-->
  </div>

  <div class="box">
    <div class="box-header with-border">
      <div class="pull-left">
        <select style="margin-right: 5px;width: 100px" class="form-control btn-primary fa" id="year_filter">
          <option value="{{$year}}">&#xf073 &nbsp{{$year}}</option>
          @foreach($year_before as $years)
            @if($years->year != $year)
              <option value="{{$years->year}}">&#xf073 &nbsp{{$years->year}}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="pull-right">
          <button type="button" class="btn btn-success margin-bottom pull-right" id="" data-target="#modal_invoice" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Number Invoice</button>
          <button class="btn btn-warning" onclick="downloadExcel('{{action('InvoiceController@downloadExcel')}}')" style="margin-right: 10px;"><i class="fa fa-print"></i>Excel</button>
      </div>
    </div>

    <div class="box-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped dataTable nowrap" id="data_invoice" width="100%" cellspacing="0">
          <thead>
            <tr  style="text-align: center;">
              <th>No Invoice</th>
              <th>No PO</th>
              <th>Date</th>
              <th><div class="truncate">From (External)</div></th>
              <th>Issuance</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="products-list" name="products-list">
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!--MODAL ADD PROJECT-->
  <div class="modal fade" id="modal_invoice" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Invoice</h4>
          </div>
          <div class="modal-body">
            <!-- <form method="POST" action="{{url('/invoice/store')}}" id="modal_pr" name="modal_invoice"> -->
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">No Invoice</label>
                  <input type="text" class="form-control" placeholder="Please input No Invoice" name="no_invoice" id="no_invoice" required>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="">No. Purchase Order</label>
                  <select type="text" class="form-control select2" style="width: 100%;" name="no_po" id="no_po" required>
                    <option value="">Select No Purchase Order</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">Date</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right date" name="date" id="date_invoice" required>
              </div>
            </div>

            <div class="form-group">
              <label for="">From (Customer, Distributor, Pihak External)</label>
              <input type="text" class="form-control" placeholder="ex. PT. Sinergy Informasi Pratama" name="to" id="to" required>
            </div>          
                         
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" onclick="submitInvoice()" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
          <!-- </form> -->
          </div>
        </div>
      </div>
  </div>

  <!--Modal Edit-->
  <div class="modal fade" id="modaledit" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Invoice</h4>
          </div>
          <div class="modal-body">
            <!-- <form method="POST" action="{{url('/update_pr')}}" id="modaledit" name="modaledit"> -->
              @csrf
            <input type="text" name="edit_id_invoice" id="edit_id_invoice" hidden>
            <div class="form-group">
              <label for="">No Invoice</label>
              <input type="text" class="form-control" name="edit_no_invoice" id="edit_no_invoice" >
            </div>
            <div class="form-group">
              <label for="">No. Purchase Order</label>
              <select type="text" class="form-control select2" style="width: 100%;" name="edit_no_po" id="edit_no_po" required>
                <option value="">Select No Purchase Order</option>
              </select>
            </div>
            <input type="text" name="edit_no_po_existing" id="edit_no_po_existing" hidden>
            <div class="form-group">
              <label for="">Date</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right date" name="edit_date" id="edit_date" required>
              </div>
            </div> 
            <div class="form-group">
              <label for="">From</label>
              <textarea type="text" class="form-control" name="edit_from" id="edit_from" > </textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" onclick="editInvoice()" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
          <!-- </form> -->
          </div>
        </div>
      </div>
  </div>
    
</section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <!-- <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script> -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.3.1/js/dataTables.fixedColumns.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@endsection
@section('script')
  <script type="text/javascript">

    $(".date").datepicker({
      todayHighlight: true,
      autoclose:true
    }).attr('readonly','readonly').css('background-color','#fff');

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    var table = $("#data_invoice").DataTable({
      "ajax":{
        "type":"GET",
        "url":"{{url('/invoice/getData')}}",
      },
      "columns": [
        { "data": "no_invoice" },
        { "data": "no_po" },
        { "data": "date"},
        { "data": "from_eksternal" },
        { "data": "issuance" },
        {
          render: function (data, type, row){
            // btnEdit = '<button class="btn btn-xs btn-width-custom btn-warning btn_edit" id="btnEdit" style="display:none;width:50px"><i class="fa fa-edit"></i> Edit</button>'
            return '<button class="btn btn-xs btn-primary btn_edit" id="btnAssign" value="'+ row.id+'" ><i class="fa fa-edit"></i> Edit</button>'              
          }
        },
      ],
      "searching": true,
      "lengthChange": false,
      "info":true,
      "scrollX": true,
      fixedColumns:   {
        leftColumns: 1
      },
      "pageLength": 20,
    })

    $($.fn.dataTable.tables( true ) ).css('width', '100%');
    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    
    $(".dismisbar").click(function(){
      $(".notification-bar").slideUp(300);
    }); 

    $.ajax({
      url: "{{url('/invoice/getNoPo')}}",
      type: "GET",
      success: function(result) {
        $("#no_po").select2({
          data:result.data
        })
      }
    })


    table.on('click', '.btn_edit', function () {  
      value = this.value;
      $('#modaledit').modal('show');
      $.ajax({
        type:"GET",
        url:"{{url('/invoice/getInvoiceEdit')}}",
        data: {
          id_invoice:this.value,
        },
        success:function(result){
          $("#edit_no_po").select2({
            data:result.no_po
          })
          console.log(result.data[0].no)
          $('#edit_id_invoice').val(result.data[0].id);
          $('#edit_no_invoice').val(result.data[0].no_invoice);
          $("#edit_no_po").val(result.data[0].no).trigger("change");
          $('#edit_date').val(result.data[0].date);
          $('#edit_from').val(result.data[0].from_eksternal);
          $('#edit_no_po_existing').val(result.data[0].no);          
        }
      })
    });


    $("#year_filter").change(function(){
      $('#data_invoice').DataTable().ajax.url("{{url('invoice/getFilterYear')}}?data=" + this.value).load();
      dashboardCount(this.value);
    });

    function downloadExcel(url){
      window.location = url + "?year=" + $("#year_filter").val();
    }

    function submitInvoice(){
      Swal.fire({
        title: 'Are you sure?',  
        text: "Submit Invoice",
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
              text: "It's sending..",
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
              type: "POST",
              url: "{{url('/invoice/store')}}",
              data: {
                _token: "{{ csrf_token() }}",
                no_invoice:$("#no_invoice").val(),
                no_po:$("#no_po").val(),
                date:$("#date_invoice").val(),
                from_eksternal:$("#to").val(),
              },
              success: function(result) {
                  Swal.showLoading()
                  Swal.fire(
                      'Successfully!',
                      'Invoice Created.',
                      'success'
                  ).then((result) => {
                      if (result.value) {
                        location.reload()
                        $("#modal_invoice").modal('hide')
                      }
                  })
              }
          })          
        }
      })
    }

    function editInvoice(){
      Swal.fire({
          title: 'Are you sure?',  
          text: "Update this Invoice",
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
              text: "It's sending..",
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
            type: "POST",
            url: "{{url('/invoice/update_invoice')}}",
            data: {
              _token: "{{ csrf_token() }}",
              id_edit:$("#edit_id_invoice").val(),
              edit_no_invoice:$("#edit_no_invoice").val(),
              edit_no_po:$("#edit_no_po").val(),
              edit_date:$("#edit_date").val(),
              edit_from:$("#edit_from").val(),
              edit_no_po_existing:$('#edit_no_po_existing').val(),
            },
            success: function(result) {
              Swal.showLoading()
              Swal.fire(
                  'Successfully!',
                  'Invoice Updated.',
                  'success'
              ).then((result) => {
                  if (result.value) {
                    location.reload()
                    $("#modaledit").modal('hide')
                  }
              })
            }
          })          
        }
      })
    }
  </script>
@endsection
