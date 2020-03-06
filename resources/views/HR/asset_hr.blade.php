@extends('template.template_admin-lte')
@section('content')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
<style type="text/css">
  .modalIconsubject input[type=text]{
      padding-left:115px;
    }

    .modalIconsubject.inputIconBg input[type=text]:focus + i{
      color:#fff;
      background-color:dodgerBlue;
    }

   .modalIconsubject.inputIconBg i{
      background-color:#aaa;
      color:#fff;
      padding:7px 4px ;
      border-radius:4px 0 0 4px;
    }

  .modalIconsubject{
      position:relative;
    }

   .modalIconsubject i{
      position:absolute;
      left:9px;
      top:0px;
      padding:9px 8px;
      color:#aaa;
      transition:.3s;
    }

</style>
<section class="content">

  @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
  @endif

  @if (session('danger'))
    <div class="alert alert-danger" id="alert">
        {{ session('danger') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success" id="alert">
      {{ session('success') }}
    </div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success" id="alert">
      {{ session('alert') }}
    </div>
  @endif

  <div class="box">
    <div class="box-body">
      <div class="nav-tabs-custom active" id="asset" role="tabpanel">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#list_asset" role="tab" aria-controls="kategori" aria-selected="false">List Asset</a>
          </li>
          @if(Auth::User()->id_division == 'HR')
          <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"> </i>&nbsp Add Asset</button>
          <a href="{{url('exportExcelAsset')}}" class="btn btn-info btn-sm pull-right" style="margin-right: 5px"><i class="fa fa-cloud-download"></i>&nbsp&nbspExport</a>
          @endif
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane active" id="list_asset" role="tabpanel" aria-labelledby="home-tab">
            <br>
            <div class="table-responsive" >
              <table class="table table-bordered table-striped " id="data_table" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Code Asset</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                    @if(Auth::User()->id_division == 'HR')
                    <th>Action</th>
                    @endif
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  @foreach($asset as $data)
                  <tr>
                    <td>{{$data->code_name}}<input type="" name="id_barang_update" hidden></td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->description}}</td>
                    <td>
                      @if($data->serial_number != "-")
                      {{$data->serial_number}}
                      @else
                      -
                      @endif
                    </td>
                    <td>
                      @if($data->status == "PENDING")
                      <span class="label label-info">UnAvailable</span>
                      @else
                      <span class="label label-default">Available</span>
                      @endif
                    </td>
                    @if(Auth::User()->id_division == 'HR')
                    <td>
                      <button class="btn btn-xs btn-warning" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" id="barang_asset_edit" value="{{$data->id_barang}}"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Asset" data-placement="bottom"></i></button>
                      @if($data->status == "PENDING")
                      <button class="btn btn-xs btn-danger btn-pengembalian" value="{{$data->id_barang}}" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" data-toggle="tooltip" title="Pengembalian" data-placement="bottom"><i class="fa fa-hourglass-end"></i></button>
                      @else
                      <button class="btn btn-xs btn-success btn-peminjaman" onclick="pinjam('{{$data->id_barang}}','{{$data->nama_barang}}')" data-toggle="tooltip" style="width:35px;height:30px;border-radius: 25px!important;outline: none;" title="Peminjaman" data-placement="bottom"><i class="fa fa-hourglass-start"></i></button>
                      @endif                      
                      <a href="{{url('/detail_peminjaman_hr', $data->id_barang) }}"><button class="btn btn-xs btn-primary" style="width:35px;height:30px;border-radius: 25px!important;outline: none;"><i class="fa fa-history" aria-hidden="true" data-toggle="tooltip" title="History" data-placement="bottom"></i></button></a>
                      <button class="btn btn-xs" style="width:35px;height:30px;border-radius: 25px!important;outline: none;background-color: black" id="btn_info_asset" value="{{$data->id_barang}}"><i class="fa fa-info" style="color: white" aria-hidden="true"></i></button>
                      
                    </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @if(Auth::User()->id_division == 'HR')
          <div class="tab-pane fade" id="peminjaman_asset" role="tabpanel" aria-labelledby="profile-tab">
            <div class="table-responsive" style="margin-top: 15px">
              <table class="table table-bordered nowrap DataTable" id="datatable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>No Peminjaman</th>
                    <th>Nama Barang</th>
                    <th>Description</th>
                    <th>Nama Peminjam</th>
                    <th>Tgl Peminjaman</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $no = 1 ?>
                  @foreach($assetsd as $data)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$data->no_transac}}</td>
                    <td>{{$data->nama_barang}}</td>
                    <td>{{$data->keterangan}}</td>
                    <td>{{$data->name}}</td>
                    <td>{{$data->tgl_peminjaman}}</td>
                    <td>
                      @if($data->status == 'PENDING')
                        <label class="status-open" style="width: 150px;">SUDAH DI AMBIL</label>
                      @elseif($data->status == 'RETURN')
                       <label class="status-win" style="width: 90px;height: 25px">RETURNED</label>
                      @endif
                    </td>
                    <td>
                      @if($data->status == 'PENDING')
                      <button class="btn btn-md btn-danger" data-target="#kembali_modal" data-toggle="modal" onclick="kembali('{{$data->id_barang}}','{{$data->id_transaction}}')" style="text-align: center;width: 125px">KEMBALI</button>
                      @else
                      <button class="btn btn-md btn-danger" disabled style="text-align: center;width: 125px">KEMBALI</button>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</section>

<style type="text/css">
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

<!--add asset-->
<div class="modal fade" id="add_asset" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_asset_hr')}}" id="modal_add_asset" name="modalProgress">
            @csrf
          <div class="form-group">
            <label for="sow">Kategori</label>
            <select class="form-control" id="category_asset" name="category_asset" required>
              <option value="">Select Kategori</option>
              <option value="LPT">Laptop</option>
              <option value="PRN">Printer</option>
              <option value="HDD">Harddisk</option>
              <option value="PRY">Proyektor</option>
              <option value="OTH">Other</option>
            </select>
          </div>

          <div class="form-group">
            <label for="sow">Company</label>
            <select class="form-control" id="company_asset" name="company_asset" required">
              <option value="">Select Company</option>
              <option value="SIP">PT. SIP</option>
              <option value="MSP">PT. MSP</option>
            </select>
          </div>

          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang" class="form-control"></input>
          </div>

          <div class="form-group">
            <label for="sow">Nomor SN</label>
            <input name="asset_sn" id="asset_sn" class="form-control"></input>
          </div>
          
          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
          </div>

          <div class="form-group">
            <label for="sow">Asset Date</label>
            <input type="text" name="asset_date" id="asset_date" class="form-control" data-date-end-date="0d"></input>
          </div>

          <div class="form-group">
            <label for="sow">Asset Code</label>
            <input name="kode_asset" id="kode_asset" class="form-control hidden" value="{{$nomor}}" hidden=></input>
            <input name="asset_code" id="asset_code" class="form-control" value="{{$nomor}}" readonly></input>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--edit asset-->

<!--edit asset-->
<div class="modal fade" id="modaledit" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Update Asset HR/GA</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('edit_asset')}}" id="modal_update" name="modalProgress">
            @csrf
          <input type="" name="id_barang_asset_edit" id="id_barang_asset_edit" hidden>
          <div class="form-group">
            <label for="sow">Nama barang</label>
            <input class="form-control" type="text" name="nama_barang_asset_edit" id="nama_barang_asset_edit">
          </div>

          <div class="form-group">
            <label for="sow">Keterangan</label>
            <textarea name="keterangan_edit" id="keterangan_edit" class="form-control" required=""></textarea>
          </div>

          <div class="form-group">
            <label for="sow">Nomor SN</label>
            <input class="form-control" type="text" name="asset_sn_edit" id="asset_sn_edit">
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
            <button type="submit" class="btn btn-sm btn-warning"><i class="fa fa-check"></i>&nbsp Update</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="peminjaman" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peminjaman</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('peminjaman_hr')}}" id="modal_peminjaman" name="modalProgress">
            @csrf
          <input type="text" name="id_barang" id="id_barang" hidden>
          <div class="form-group">
            <label>Nama Peminjam</label>
            <select name="users" id="users" class="form-control" style="width: 270px;" required >
              <option>Select Name</option>
              @foreach($users as $user)
                <option value="{{$user->nik}}">{{$user->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="sow">Nama Barang</label>
            <input name="nama_barang" id="nama_barang_pinjam" class="form-control" readonly></input>
          </div>
          <div class="form-group margin-left-right">
              @if ($message = Session::get('warning'))
              <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button> 
                <strong>{{ $message }}</strong>
              </div>
              @endif
            </div>
          <div class="form-group">
            <label>Keperluan</label>
            <textarea class="form-control" name="keperluan"></textarea>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"><i class="fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--modal accept-->
<!-- <div class="modal fade" id="accept_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('accept_pinjam_hr')}}" id="modal_accept" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_update" id="id_barang_update" hidden>
          <input type="text" name="id_transaction_update" id="id_transaction_update" hidden>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>ACCEPT NOW!</b></h3>
          </div>
          <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
            <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman" readonly>
            <i class="" aria-hidden="true">No Peminjaman &nbsp</i>
          </div>
          <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
            <input type="text" class="form-control money" name="nama_peminjam" id="nama_peminjam" readonly>
            <i class="" aria-hidden="true">Nama Peminjam</i>
          </div>
          <legend></legend>
            <table>
              <input type="" name="id_transaksi" id="id_transaksi" hidden>
              <tr class="tr-header">
                <th style="border-left: 10px; width: 270px;">Nama Barang</th>
                <th style="border-left: 10px; width: 270px;">Jumlah Pinjam</th>
              </tr>
              <tbody id="mytable">
                <tr>
                  <td style="margin-bottom: 75px;  width: 270px;">
                  </td>
                  <td style="margin-bottom: 75px;  width: 270px;">
                  </td>
                </tr>
              </tbody>
            </table>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div>

REJECT
<div class="modal fade" id="reject_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('reject_pinjam_hr')}}" id="modal_reject" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_reject" id="id_barang_reject" hidden>
          <input type="text" name="id_transaction_reject" id="id_transaction_reject" hidden>
          <div class="form-group">
          	<h3 style="text-align: center;"><b>REJECT NOW!</b></h3>
          </div>
          <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
            <input type="text" class="form-control money" name="no_peminjaman" id="no_peminjaman2" readonly>
            <i class="" aria-hidden="true">No Peminjaman &nbsp</i>
          </div>
          <div class="form-group modalIconsubject inputIconBg" style="padding-left: 10px">
            <input type="text" class="form-control money" name="nama_peminjam" id="nama_peminjam2" readonly>
            <i class="" aria-hidden="true">Nama Peminjam</i>
          </div>
          <legend></legend>
            <table>
              <input type="" name="id_transaksi" id="id_transaksi2" hidden>
              <tr class="tr-header">
                <th style="border-left: 10px; width: 270px;">Nama Barang</th>
                <th style="border-left: 10px; width: 270px;">Jumlah Pinjam</th>
              </tr>
              <tbody id="mytable2">
                <tr>
                  <td style="margin-bottom: 75px;  width: 270px;">
                  </td>
                  <td style="margin-bottom: 75px;  width: 270px;">
                  </td>
                </tr>
              </tbody>
            </table>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i>&nbsp YES</button>
          </div>
        </form>
        </div>
      </div>
    </div>
</div> -->
  
  <!--kembali-->
<div class="modal fade" id="kembali_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <form method="POST" action="{{url('kembali_pinjam_hr')}}" id="modal_kembali" name="modalProgress">
            @csrf
          <input type="text" name="id_barang_kembali" id="id_barang_kembali" hidden>
          <input type="text" name="id_transaction_kembali" id="id_transaction_kembali" hidden>
          <div class="form-group">
            <h3 style="text-align: center;"><b>RETURN NOW!</b></h3>
            <table class="table table-bordered">
              <tr>
                <th>Nama Barang</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Kembali</th>
              </tr>
              <tr>
                <td>
                  <span id="nama_barang_kembali"></span>
                </td>
                <td>
                  <span id="nama_peminjam_kembali"></span>
                </td>
                <td>
                  <input type="date" name="tanggal_kembali" id="tanggal_kembali" readonly class="form-control">
                </td>
              </tr>
            </table>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbspCANCEL</button>
              <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i>&nbsp Submit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script> 
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/roman.js')}}"></script>
  <script type="text/javascript">

    var hari_libur_nasional = []
    var hari_libur_nasional_tooltip = []
    $.ajax({
      type:"GET",
      url:"https://www.googleapis.com/calendar/v3/calendars/en.indonesian%23holiday%40group.v.calendar.google.com/events?key={{env('GOOGLE_API_YEY')}}",
      success: function(result){
        $.each(result.items,function(key,value){
          hari_libur_nasional.push(moment( value.start.date).format("MM/DD/YYYY"))
          hari_libur_nasional_tooltip.push(value.summary)
        })
      }
    })

    $('#asset_date').datepicker({
      weekStart: 1,
      daysOfWeekDisabled: "0,6",
      daysOfWeekHighlighted: [0,6],
      todayHighlight: true,
      beforeShowDay: function(date){
        var index = hari_libur_nasional.indexOf(moment(date).format("MM/DD/YYYY"))
        if(index > 0){
          return {
            enabled: false,
            tooltip: hari_libur_nasional_tooltip[index],
            classes: 'hari_libur'
          };
        }
      }
    });

    var now = new Date();
 
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);

    var today = now.getFullYear()+"-"+(month)+"-"+(day);

    $('#tanggal_kembali').val(today);

    $(document).on('click',"#barang_asset_edit",function(e) { 
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:"{{url('/getEditAsset')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $('#id_barang_asset_edit').val(result[0].id_barang);
              $('#nama_barang_asset_edit').val(result[0].nama_barang);
              $('#asset_sn_edit').val(result[0].serial_number);
              $('#keterangan_edit').val(result[0].description);
            },
        });

        $('#modaledit').modal('show')
    });

    $(document).on('click',"#btn_info_asset",function(e) { 
        // console.log(this.value);
        $.ajax({
            type:"GET",
            url:"{{url('/getEditAsset')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              var swal_html = '<div class="panel" style="background:aliceblue;font-weight:bold;font-size:12px"> Berikut informasi terkait : </br></br> Umur Asset : '+ result[0].umur_asset +' hari </div>';
              // swal({title: "Asset "+ result[0].nama_barang +" !!", html: swal_html})
              swal({
                title: "Asset "+ result[0].nama_barang +" !!", html: swal_html
              });
            },
        });
    });

    $(document).on('click',".btn-pengembalian",function(e) { 
        console.log(this.value);
        $.ajax({
            type:"GET",
            url:"{{url('/getPengembalian')}}",
            data:{
              id_barang:this.value,
            },
            success: function(result){
              $('#id_transaction_kembali').val(result[0].id_transaction);
              $('#id_barang_kembali').val(result[0].id_barang);
              $('#nama_barang_kembali').text(result[0].nama_barang);
              $('#nama_peminjam_kembali').text(result[0].name);
            },
        });

        $('#kembali_modal').modal('show')
    });

    $(document).on('click',".btn-peminjaman",function(e) { 

        $('#peminjaman').modal('show')
    });

    $('#users').select2();

    $('#data_table').DataTable({
      "order": [[ 0, "asc" ]],
      pageLength: 20,
    });

    $('#datatable').DataTable({
      pageLength: 20,
    });

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    function pinjam(id_barang,nama_barang){
      $('#id_barang').val(id_barang);
      $('#nama_barang_pinjam').val(nama_barang);
    }

    function kembali(id_transaction,id_barang,nama_barang,name){
      $('#id_transaction_kembali').val(id_transaction);
      $('#id_barang_kembali').val(id_barang);
      $('#nama_barang_kembali').text(nama_barang);
      $('#nama_peminjam_kembali').text(name);
    } 

    function edit(id_barang,nama_barang,description,serial_number){
      $('#id_barang_asset_edit').val(id_barang);
      $('#nama_barang_asset_edit').val(nama_barang);
      $('#keterangan_edit').val(description);
      $('#asset_sn_edit').val(serial_number);
    } 

    $(document).on('change',"#category_asset",function(e) { 
        var code = $("#kode_asset").val();
        var company = $("#company_asset").val();
        var asset_date = $("#asset_date").val();

        $(document).on('change',"#company_asset",function(e) { 
          var code = $("#kode_asset").val();
          var category = $("#category_asset").val();
          var asset_date = $("#asset_date").val();

          if (asset_date == "") {
            $('#asset_code').val(code + "/" + category + "/" + this.value + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
          }else{
            $('#asset_code').val(code + "/" + category + "/" + this.value + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));
          }

          $(document).on('change',"#asset_date",function(e) { 

            var code = $("#kode_asset").val();
            var category = $("#category_asset").val();
            var company = $("#company_asset").val();
            var asset_date = $("#asset_date").val();

            $('#asset_code').val(code + "/" + category + "/" + company + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));

          });

        });

        if (company == "" && asset_date == "") {
          $('#asset_code').val(code + "/" + this.value + "/" + "-" + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
        }else if(asset_date != ""){
          $('#asset_code').val(code + "/" + this.value + "/" + company + "/" + roman.toRoman(parseInt(moment(asset_date).format("M"))) + "/" + moment(asset_date).format("YYYY"));
        }else{
          $('#asset_code').val(code + "/" + this.value + "/" + company + "/" + roman.toRoman(parseInt(moment().format("M"))) + "/" + moment().format("YYYY"));
        }
        
    });

    /*$('#myTab a').click(function(e) {
      e.preventDefault();
      $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
      var id = $(e.target).attr("href").substr(1);
      window.location.hash = id;
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab a[href="' + hash + '"]').tab('show');*/
  </script>
@endsection