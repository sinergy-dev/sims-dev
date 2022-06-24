@extends('template.main')
@section('tittle')
Partnership
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <style type="text/css">
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 120px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {background-color: #ddd;}

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {display: block;}

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {background-color: #F0AD4E;}    
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Partnership
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Partnership</li>
    </ol>
  </section>

  <section class="content">
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

    <div class="box">
      <div class="box-header">
          <div class="pull-right">
            <button type="button" class="btn btn-primary pull-right float-right margin-left-custom" id="btnAdd" onclick="showTabAdd(0)" style="display: none;" style="width: 120px;"><i class="fa fa-plus"> </i> &nbspPartnership
              </button>  
            <div class="pull-right dropdown" style="margin-right: 5px;display: none;" id="divExport">
              <button class="btn btn-success"><i class="fa fa-download"> </i>&nbspExport</button>
              <div class="dropdown-content">
                <a href="{{action('PartnershipController@downloadpdf')}}">PDF</a>
                <a href="{{action('PartnershipController@downloadExcel')}}">Excel</a>
              </div>
            </div> 
          </div>
      </div>

      <div class="box-body">
        <div class="table-responsive">
            <table class="table table-bordered nowrap table-striped dataTable" id="datastable" style="width: 100%" cellspacing="0">
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
                  <th>Sert.</th>
                  <th>Sert.</th>
                  <th id="th-action">Action</th>
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
                  <td>
                    <center>
                      <button type="button" data-target="#uploadFile" data-toggle="modal" onclick="upload('{{$data->id_partnership}}', '{{$data->doc}}')" class="btn btn-xs btn-submit" style="vertical-align: top; width: 80px"><i class="fa fa-upload"></i>&nbspUpload</button>
                      @if($data->doc == NULL)
                        <button class="btn btn-xs btn-submit disabled" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button>
                      @else
                        <a href="{{ url('download_partnership', $data->doc) }}" target="_blank" style="color: black"><button class="btn btn-xs btn-submit" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button></a>
                      @endif
                    </center>
                  </td>
                  <td>
                    <center>
                      @if($data->doc == NULL)
                        <button class="btn btn-xs btn-submit disabled" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button>
                      @else
                        <a href="{{ url('download_partnership', $data->doc) }}" target="_blank" style="color: black"><button class="btn btn-xs btn-submit" style="vertical-align: top; width: 80px"><i class="fa fa-download"></i>&nbspDownload</button></a>
                      @endif
                    <center> 
                  </td>
                  <td>
                    <a href="{{url('/partnership_detail', $data->id_partnership)}}"><button class="btn btn-primary btn-xs"><i class="fa fa-search-plus"></i> Detail</button></a>
                 <!--    <button class="btn btn-xs btn-primary" data-target="#modalEdit" data-toggle="modal" style="vertical-align: top; width: 60px" onclick="partnership('{{$data->id_partnership}}', '{{$data->type}}','{{$data->partner}}', '{{$data->level}}','{{$data->renewal_date}}','{{$data->annual_fee}}','{{$data->sales_target}}','{{$data->sales_certification}}','{{$data->engineer_certification}}')"><i class="fa fa-search"></i>&nbspEdit</button> -->

                    <a href="{{ url('delete_partnership', $data->id_partnership) }}"><button class="btn btn-xs btn-danger" style="vertical-align: top;width: 60px" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i>&nbspDelete
                    </button></a>

                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
      </div>
    </div>      

    <!--MODAL-->

    <div class="modal fade" id="uploadFile" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content modal-md">
          <div class="modal-header">
            <h4 class="modal-title">Upload File .pdf</h4>
          </div>

          <div class="modal-body">
            <form action="/upload/proses" method="POST" enctype="multipart/form-data">
              @csrf

              <div class="form-group">
                <input type="text" id="upload_id_partnership" name="upload_id_partnership" hidden>
                <input type="text" id="upload_doc" name="upload_doc" hidden>
              </div>
              <div class="form-group">
                <input type="file" id="file" name="file">
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

    <!--MODAL ADD INCIDENT-->
    <div class="modal fade" id="modalAddPartnership" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content modal-md">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" class="close" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Partnership</h4>
            </div>
            <div class="modal-body">
              <form enctype="multipart/form-data" id="modalAdd" name="modalAdd">
                @csrf

                <div class="tab" style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="Network">Network</option>
                            <option value="Server">Server</option>
                            <option value="Security">Security</option>
                            <option value="Other">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Partner</label>
                        <input class="form-control" placeholder="Enter Partner" id="partner" name="partner" required>
                      </div>  
                    </div>
                  </div>           

                  <div class="form-group">
                      <label>Level</label>
                      <input class="form-control" placeholder="Enter Level (*Beginner, Intermediate, Advanced)" id="levelling" name="levelling" required>
                  </div>

                  <div class="form-group">
                      <label>Current Level</label>
                      <input class="form-control" placeholder="Enter Current Level (Beginner)" id="level" name="level" required>
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

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM</label>
                          <input class="form-control" placeholder="Enter CAM" id="cam" name="cam">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM Phone</label>
                          <input class="form-control" placeholder="Enter CAM Phone" id="cam_phone" name="cam_phone">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>CAM Email</label>
                          <input class="form-control" placeholder="Enter CAM Email" id="cam_email" name="cam_email">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label>Email Support</label>
                          <input class="form-control" placeholder="Enter Email Support" id="email_support" name="email_support">
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                      <label>ID Mitra</label>
                      <input class="form-control" placeholder="Enter Id Mitra" id="id_mitra" name="id_mitra">
                  </div>

                 <!--      <div class="form-group">
                          <label>Sales Certification</label>
                          <input class="form-control" placeholder="Enter Sales Certification" id="sales_certification" name="sales_certification">
                      </div>

                      <div class="form-group">
                          <label>Engineer Certification</label>
                          <input class="form-control" placeholder="Enter Engineer Certification" id="engineer_certification" name="engineer_certification">
                      </div> -->
                </div>

                <div class="tab" style="display: none;">
                  <i class="fa fa-table"></i><label>&nbspCertification list</label> 
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Certificate Level</th>
                        <th>Certificate Name</th>
                        <th>Person</th>
                        <td class="text-center">
                          <button class="btn btn-xs btn-primary" onclick="addListCert()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
                            <i class="fa fa-plus"></i>
                          </button> 
                        </td>
                      </tr>
                    </thead>
                    <tbody id="tbListCert">
                    </tbody>
                  </table>
                  <i class="fa fa-table"></i><label>&nbspSales Target</label> 
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Target</th>
                        <th>Nominal</th>
                        <th>Apaya kemarin</th>
                        <td class="text-center">
                          <button class="btn btn-xs btn-primary" onclick="addSalesTarget()" type="button" style="border-radius:50%;width: 25px;height: 25px;">
                            <i class="fa fa-plus"></i>
                          </button> 
                        </td>
                      </tr>
                    </thead>
                    <tbody id="tbSalesTarget">
                    </tbody>
                  </table>
                </div>         
                 
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" id="prevBtn" onclick="nextPrev()">Back</button>
                  <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextPrev()">Next</button>
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
          <h4 class="modal-title">Edit Partnership</h4>
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

  </section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@endsection
@section('script')  
  <script type="text/javascript">
      $(document).ready(function(){
          var accesable = @json($feature_item);
          accesable.forEach(function(item,index){
            $("#" + item).show()

          })

          if (!(accesable.includes('th-action'))) {
            var column1 = table.column(11);
            column1.visible(!column1.visible());

            var column2 = table.column(9);
            column2.visible(!column2.visible());
          }else{
            var column1 = table.column(11);
            column1.visible(column1.visible());

            var column2 = table.column(10);
            column2.visible(!column2.visible());
          }

          // if (!(accesable)) {
          //   var column1 = table.column(9);
          //   column1.visible( ! column1.visible() );
          // }  
      })
    
     var table = $('#datastable').DataTable({
      "scrollX": 200,
      pageLength:25,
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

    function upload(id_partnership, doc) {
      $('#upload_id_partnership').val(id_partnership);
      $('#upload_doc').val(doc);
    }

    var i = 0;
    function addListCert(){
      i++;
      var append = ""
      append = append + "<tr class='new-list'>"
      append = append + " <td>"
      append = append + " <input data-value='" + i + "' name='cert_type[]' id='cert_type' class='form-control' type='text' placeholder='Enter Certificate Name'>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + "<input data-value='" + i + "' name='cert_name[]' id='cert_name' class='form-control' type='text' placeholder='Enter Certificate Type'>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <select class='form-control select2-person' data-value='" + i + "' id='select2-person' style='width: 100%!important' name='cert_person[]'></select> "
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash-list'>"
      append = append + " <i class='fa fa-trash'></i>"
      append = append + " </button>"
      append = append + " </td>"
      append = append + "</tr>"
      
      $("#tbListCert").append(append)
      $.ajax({
        url: "{{url('/partnership/getUser')}}",
        type: "GET",
        success: function(result) {
          console.log(result)
          $("#select2-person[data-value='" + i + "']").select2({
              dropdownParent: $('#modalAddPartnership'),
              placeholder: "Select Person",
              data: result.data
          })
        }
      }) 
    }

    function addSalesTarget(){
      i++;
      var append = ""
      append = append + "<tr class='new-list'>"
      append = append + " <td>"
      append = append + " <input data-value='" + i + "' name='sales_target[]' id='sales_target' class='form-control' type='text' placeholder='Enter Target'>"
      append = append + " </td>"
      append = append + " <td>"
      append = append + "<input data-value='" + i + "' name='nominal[]' id='nominal' class='form-control' type='text' placeholder='Enter Nominal'>"
      append = append + " </td>"
      append = append + " <td style='white-space: nowrap'>"
      append = append + " <select class='form-control select2-person' data-value='" + i + "' id='select2-person' style='width: 100%!important' name='cert_person[]'></select> "
      append = append + " </td>"
      append = append + " <td class='text-center'>"
      append = append + " <button type='button' style='width: auto !important;' class='btn btn-danger btn-flat btn-trash-list'>"
      append = append + " <i class='fa fa-trash'></i>"
      append = append + " </button>"
      append = append + " </td>"
      append = append + "</tr>"
      
      $("#tbSalesTarget").append(append)
    }

    $(document).on('click', '.btn-trash-list', function() {
      $(this).closest("tr").remove();
    });

    var currentTab = 0

    $('#btnAdd').click(function(n){
      showTabAdd(0)
      $('#modalAddPartnership').modal({
          backdrop: 'static',
          keyboard: false
      });
    });

    function showTabAdd(n){
      console.log(n)
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "inline";
      for (var i = 0; i < x.length; i++){
          x[1].style.display = "none";
      }


      if (n == (x.length - 1)) {
        console.log(n)
        document.getElementById("nextBtn").innerHTML = "Submit";  
        $("#nextBtn").attr('onclick','submitBtnAdd()');
        for (var i = 0; i < x.length; i++){
          x[1].style.display = "inline";
        }       
      } else {
        console.log(n)
        $("#nextBtn").attr('onclick','nextPrev(1)');
        $("#prevBtn").attr('onclick','nextPrev(-1)')
        document.getElementById("nextBtn").innerHTML = "Next";
        $("#nextBtn").prop("disabled",false)
      }
    } 

    function nextPrev(n){
      var x = document.getElementsByClassName("tab");

      x[currentTab].style.display = "none";
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        x[n].style.display = "none";
        currentTab = 0;
      } 
      showTabAdd(currentTab);
    }

    function submitBtnAdd(){
      Swal.fire({
        title: 'Add New Partnership',
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
              url: "{{'/store_partnership'}}",
              type: 'post',
              // dataType: 'application/json',
              data: $("#modalAdd").serialize(), // serializes the form's elements.
            success: function(data)
            {
                Swal.showLoading()
                  Swal.fire(
                    'Successfully!',
                    'success'
                  ).then((result) => {
                    if (result.value) {
                      location.reload()
                    }
                })
            }
          }); 
        }    
      })
    }

  </script>
@endsection