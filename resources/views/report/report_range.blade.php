@extends('template.main')
@section('tittle')
Report Range
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="{{asset('template2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
    .btn-warning-export{
      background-color: #ffc107;
      border-color: #ffc107;
    }
    .dataTables_paging {
     display: none;
    }

    .dataTables_wrapper .dt-buttons {
      float:none;  
      text-align:center;
      /*margin-bottom: 10px;*/
    }

    .dataTable thead tr:first-child th,
    .dataTable thead tr#status td{
      border: none;
    }

    #data_all .row:first-child {
      display: none
    }

    .dataTables_filter {
      display: none;
    }

</style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Report Range
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li class="active">Report Range</li>
    </ol>
  </section>
  <section class="content">
    <div class="row mb-3">
      <div class="col-lg-2 col-xs-6">

        <div class="small-box bg-purple">
            <div class="inner">
              <div id="lead_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_lead}}</h3></center></div>

              <center><p>Lead Register</p></center>

            </div>
        </div>

      </div>
      <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
              <div id="open_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_open}}</h3></center></div>

              <center><p>Open</p></center>

            </div>
        </div>
      </div>
      
      <div class="col-lg-2 col-xs-6">
        <!-- small box -->

        <div class="small-box bg-aqua">
            <div class="inner">
              <div id="sd_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_sd}}</h3></center></div>

              <center><p>Solution Design</p></center>

            </div>
        </div>
      </div>

      <div class="col-lg-2 col-xs-6">
        <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <div id="tp_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_tp}}</h3></center></div>

              <center><p>Tender Process</p></center>

            </div>
        </div>

      </div>
      <div class="col-lg-2 col-xs-6">
        <!-- small box -->

      <div class="small-box bg-green">
            <div class="inner">
              <div id="win_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_win}}</h3></center></div>

              <center><p>Win</p></center>

            </div>
      </div>

      </div>
      <div class="col-lg-2 col-xs-6">
        <!-- small box -->

        <div class="small-box bg-red">
            <div class="inner">
              <div id="lose_2019" class="txt_serif stats_item_number"><center><h3 class="counter">{{$total_lose}}</h3></center></div>

              <center><p>Lose</p></center>

            </div>
        </div>

      </div>
    </div>
    <div class="box">
      <div class="box-header">
        <div class="box-body">
            {{-- <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" class="form-control pull-right" id="datepicker">
            </div> --}}
            <div class="row">
              <div class="col-md-12">
                <div style="border:solid #d4d1cb;width: fit-content;max-width: fit-content;background-color: #d4d1cb;margin-bottom: 10px;border-radius: 5px;float: left;">
                  <b style="font-size: 20px;padding:15px;color: white;">Total Deal Price by Year : Rp <span id="total_deal_prices" class="money"> </span>,-</b>
                </div>
                <div id="coba" style="float:right;">                    
                </div>
              </div>              
            </div>
           
            <div class="row" style="margin-bottom:10px">
              <div class="col-md-1 col-xs-12">
                <button class="btn btn-md btn-info" style="margin-top:30px" id="btnRefresh"><i class="fa fa-refresh"></i> Refresh</button>
              </div>
              <div class="col-md-2 col-xs-12">
                <label style="margin-top: 5px">Filter Year</label>
                <select style="margin-right: 5px;" class="form-control fa" id="year_dif">
                  @foreach($years as $data)
                  <option value="{{$data->year}}">&#xf073 &nbsp{{$data->year}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 col-xs-12">
                <label style="margin-top: 5px;">Date</label>
                <div class="input-group" style="float: left">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control dates" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                </div>
              <!--   <label style="margin-top: 5px;">Start Date</label>
                <input type="text" id="startdate" class="form-control pull-right" placeholder="DD/MM/YYYY"> -->
              </div>
           <!--    <div class="col-md-2 col-xs-12">
                <label style="margin-top: 5px;">End Date</label>
                <input type="text" id="enddate" class="form-control pull-right" placeholder="DD/MM/YYYY">  
              </div> -->
              <div class="col-md-4 col-xs-12">
                <label style="margin-top: 5px">Search Anything</label>
                <div class="input-group pull-right">
                  <input id="searchBar" type="text" class="form-control" onkeyup="searchCustom('data_all','searchBar')" placeholder="Search Anything...">
                  
                  <div class="input-group-btn">
                    <button type="button" id="btnShowEntryRoleUser" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                      Show 10 entries
                    </button>
                    <ul class="dropdown-menu">
                      <li><a href="#" onclick="$('#data_all').DataTable().page.len(10).draw();$('#btnShowEntryRoleUser').html('Show 10 entries')">10</a></li>
                      <li><a href="#" onclick="$('#data_all').DataTable().page.len(25).draw();$('#btnShowEntryRoleUser').html('Show 25 entries')">25</a></li>
                      <li><a href="#" onclick="$('#data_all').DataTable().page.len(50).draw();$('#btnShowEntryRoleUser').html('Show 50 entries')">50</a></li>
                      <li><a href="#" onclick="$('#data_all').DataTable().page.len(100).draw();$('#btnShowEntryRoleUser').html('Show 100 entries')">100</a></li>
                    </ul>
                  </div>
                  <span class="input-group-btn">
                    <button onclick="searchCustom('data_all','searchBar')" type="button" class="btn btn-default btn-flat">
                      <i class="fa fa-fw fa-search"></i>
                    </button>
                  </span>
                </div>
              </div>                               
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-striped dataTable" id="data_all" width="100%" cellspacing="0">
                  <thead>
                    <tr class="no-border">
                      <th class="first">Territory</th>
                      <th>Sales</th>  
                      <th>Presales</th>
                      <th>Priority</th>
                      <th>Win Probability</th>
                      <th width="10%">Status</th>
                      <th colspan="2"></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>                  
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>                                                                                                                                 
                    </tr>
                    <tr class="no-border" id="status">
                      <td class="first"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td colspan="2"></td>
                      <td hidden></td>
                      <td hidden></td>
                      <td hidden></td>
                      <td hidden></td>
                      <td hidden></td>
                      <td hidden></td>                  
                      <td hidden></td> 
                      <th hidden></th>
                      <th hidden></th>                                                                                                      
                    </tr>
                    <tr>
                      <th class="first" hidden></th>  
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>                                                                        
                      <th>Lead ID</th>
                      <th>Owner</th>  
                      <th width="20%">Opty Name</th>
                      <th>Customer</th>
                      <th>Create Date</th>
                      <th>Closing Date</th>
                      <th>Amount IDR</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody id="report" name="report">
                    @foreach($leads as $data)
                      <tr>
                          <td class="first" hidden>{{$data->name_territory}}</td>
                          <td hidden>{{$data->name}}</td>
                          <td hidden>{{$data->name_presales}}</td>
                          <td hidden>{{$data->priority}}</td>
                          <td hidden>{{$data->win_prob}}</td>
                          <td hidden>{{$data->result_modif}}</td>
                          <td hidden>{{$data->year}}</td>
                          <td hidden>{{$data->nik}}</td>
                          <td hidden>{!!substr($data->closing_date,0,4)!!}</td>
                          <td class="lead_id">{{ $data->lead_id }}</td>
                          <td>{{ $data->name }}</td>
                          <td>{{ $data->opp_name }}</td>
                          <td>{{ $data->brand_name }}</td> 
                          <td>{!!substr($data->created_at,0,10)!!}</td>
                          <td>{{ $data->closing_date }}</td>
                          <td align="right">
                            <i class="money">{{ $data->deal_price }}</i>
                          </td>
                          <td style="align-content: center;">
                            @if($data->result_modif == 'INITIAL')
                              <label class="bg-purple" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">INITIAL</label>
                            @elseif($data->result_modif == 'OPEN')
                              <label class="bg-orange" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">OPEN</label>
                            @elseif($data->result_modif == 'SD')
                              <label class="bg-aqua" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">SD</label>
                            @elseif($data->result_modif == 'TP')
                              <label class="bg-yellow" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">TP</label>
                            @elseif($data->result_modif == 'WIN')
                              <label class="bg-green" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">WIN</label>
                            @elseif($data->result_modif == 'LOSE')
                              <label class="bg-red" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">LOSE</label>
                            @elseif($data->result_modif == 'CANCEL')
                            <label class="bg-navy" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">CANCEL</label>
                            @elseif($data->result_modif == 'HOLD')
                              <label class="bg-navy" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">HOLD</label>
                            @elseif($data->result_modif == 'SPECIAL')
                              <label class="bg-navy" style="padding:5px;width: 60px;text-align: center;border-radius: 5px;">SPECIAL</label>
                            @endif
                          </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="first" hidden></th>                  
                      <th hidden></th>                  
                      <th hidden></th>                  
                      <th hidden></th>                  
                      <th hidden></th>                  
                      <th hidden></th>                  
                      <th hidden></th>
                      <th hidden></th>
                      <th hidden></th>
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                      <th></th>                  
                    </tr>
                  </tfoot>
              </table>
            </div>             
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/button.download.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="{{asset('template2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="{{asset('js/sum().js')}}"></script>
@endsection
@section('script')
<script type = "text/javascript" >
  var d = new Date();
  var n = d.getFullYear();
  $.ajax({
    type:"GET",
    url:'/total_deal_price?year='+n,
    success: function(result){
      $('#total_deal_prices').text(result[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g,","));
    }
  });

  $('.select2').select2();

  $('#enddate').datepicker({
      autoclose: true
  })

  $('#startdate').datepicker({
      autoclose: true
  })

  function searchCustom(id_table,id_seach_bar){
    $("#" + id_table).DataTable().search($('#' + id_seach_bar).val()).draw();
  }


  // $('#daterange-btn').daterangepicker(
  //   {
  //     ranges   : {
  //       'This Month'   : [moment().startOf('month'), moment().endOf('month')],
  //       'Last 3 Month' : [moment().startOf('month').subtract(3, 'months'), moment().endOf('month')],
  //       'Last 6 Month' : [moment().startOf('month').subtract(6, 'months'), moment().endOf('month')],
  //       'Last Year'    : [moment().startOf('year').subtract(1, 'year'),moment().endOf('year').subtract(1, 'year')],
  //       'This Year'    : [moment().startOf('year'),moment().endOf('year')],
  //     },
  //     locale: {
  //       format: 'DD/MM/YYYY'
  //     }
  //   },
  //   function (start, end) {
  //     $('#reportrange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))
  //     start_date  = start.format("YYYY-MM-DD");
  //     end_date    = end.format("YYYY-MM-DD");   

  //     // console.log(start_date)

  //     $.fn.dataTable.ext.search.push(
  //     function(settings, data, dataIndex) {
  //           var min = start_date;
  //           var max = end_date;
  //           var startDate = new Date(data[[11],[12]]);
  //           if (min == null && max == null) {
  //               return true;
  //           }
  //           if (min == null && startDate <= max) {
  //               return true;
  //           }
  //           if (max == null && startDate >= min) {
  //               return true;
  //           }
  //           if (startDate <= max && startDate >= min) {
  //               return true;
  //           }
  //           return false;
  //           console.l(min)
  //       }
  //     );

  //     table.draw()
  //   }
  // )

  //fungsi untuk filtering data berdasarkan tanggal 
   var start_date;
   var end_date;
   var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue(start_date);
        var dateEnd = parseDateValue(end_date);
        //Kolom tanggal yang akan kita gunakan berada dalam urutan 2, karena dihitung mulai dari 0
        //nama depan = 0
        //nama belakang = 1
        //tanggal terdaftar =2
        var evalDate= parseDateValue(aData[13]);
          if ( ( isNaN( dateStart ) && isNaN( dateEnd ) ) ||
               ( isNaN( dateStart ) && evalDate <= dateEnd ) ||
               ( dateStart <= evalDate && isNaN( dateEnd ) ) ||
               ( dateStart <= evalDate && evalDate <= dateEnd ) )
          {
              return true;
          }
          return false;
    });

    // fungsi untuk converting format tanggal dd/mm/yyyy menjadi format tanggal javascript menggunakan zona aktubrowser
    function parseDateValue(rawDate) {
        var dateArray= rawDate.split("-");
        var parsedDate= new Date(dateArray[0], parseInt(dateArray[1])-1, dateArray[2]);  // -1 because months are from 0 to 11   
        return parsedDate;
    }    

    $( document ).ready(function() {
     //konfigurasi daterangepicker pada input dengan id datesearch
     $('#reportrange').daterangepicker({
        autoUpdateInput: false
      });

     //menangani proses saat apply date range
      $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
         $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
         start_date=picker.startDate.format('YYYY-MM-DD');
         end_date=picker.endDate.format('YYYY-MM-DD');
         $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
         console.log(start_date)
         table.draw();
      });

      $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        start_date='';
        end_date='';
        $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
        table.draw();
      });
  });

  $("#dropdown2").on('change', function() {
    if ($(this).find('option:selected').text() == "Select Option")
        $("#btnSubmit").attr('disabled', true)
    else
        $("#btnSubmit").attr('disabled', false)
  });

  var enableDisableSubmitBtn = function() {
        var startVal = $('#startdate').val().trim();
        var endVal = $('#enddate').val().trim();
        var disableBtn = startVal.length == 0 || endVal.length == 0;
        $('#dropdown').attr('disabled', disableBtn);
  }

  function exportPdf() {
    type = encodeURI($("#dropdown").val())
    date_start = encodeURI(moment($("#startdate").datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"))
    date_end = encodeURI(moment($("#enddate").datepicker("getDate")).format("YYYY-MM-DD HH:mm:ss"))
    dropdown2 = encodeURI($("#dropdown2").val())
    myUrl = url + "/getCustomerbyDate2?type=" + type + "&customer=" + dropdown2 + "&start=" + date_start + "&end=" + date_end
    location.assign(myUrl)
  }

  $('#year_dif').on('change', function() {
    console.log($('#year_dif').val());

    $.ajax({
      type:"GET",
      url:'/total_deal_price',
      data:{
        year:this.value,
      },
      success: function(result){
        $('#total_deal_prices').text(result[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g,","));
      }
    });

    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
          var years = parseInt($('#year_dif').val());
          var tahunbanding = parseInt(data[6]);
      if ( ( isNaN( years ) ) ||
           ( years  ==  tahunbanding ))
      {
      return true;
      }
      return false;  
      }
    );

    table.draw()
  })

  // $("#startdate").datepicker({
  //     onSelect: function() {
  //         table.draw();
  //     },
  //     changeMonth: true,
  //     changeYear: true,
  //     autoclose: true
  // });

  // $("#enddate").datepicker({
  //     onSelect: function() {
  //         table.draw();
  //     },
  //     changeMonth: true,
  //     changeYear: true,
  //     autoclose: true
  // });

  // $('#startdate, #enddate').change(function() {
  //   table.draw();
  // });

  if ('{{Auth::User()->id_division}}' == 'SALES') {
    $('.first').hide()
    // column1.visible(!column1.visible() );
    arr = [[1],[2],[3],[4],[5]] 
  }else{
    arr = [[0],[1],[2],[3],[4],[5]]
  }

  function initRefresh(val){
    if (val == true) {
      $("#year_dif").prop('disabled',false)
      $("#reportrange").prop('disabled',false)
      $("#searchBar").prop('disabled',false)
      $(".kat_drop").prop('disabled',false)
      $("#reportrange").val("").daterangepicker("update")
      $("#searchBar").val("")
      $("#year_dif").val(n)
      location.reload()  
      location.replace('{{url("/report_range")}}')
    }else{
      $("#reportrange").val("").daterangepicker("update")
      $("#searchBar").val("")
      $("#year_dif").val(n)
    }
  }

  $(document).ready(function(){
    if (window.location.href.split('/')[4] == undefined) {
      $("#btnRefresh").click(function(){
        initRefresh(false)
      })
      table.draw()
    }else{
      $("#btnRefresh").click(function(){
        initRefresh(true)
      })
      table.column(8).search(n).column(7).search(window.location.href.split('/')[4]).column(5).search('WIN').draw();
      $("#year_dif").prop('disabled',true)
      $("#reportrange").prop('disabled',true)
      $("#searchBar").prop('disabled',true)
      $(".kat_drop").prop('disabled',true)
    }

  })

 

  var table = $('#data_all').DataTable({
    fixedHeader: true,
    pageLength: 50,
    "bLengthChange": false,
    initComplete: function() { 
        this.api().columns(arr).every(function() {
            if (window.location.href.split('/')[4] != undefined) {
              $.ajax({
                type:"get",
                url:"{{url('/filter_sales_report')}}",
                data:{
                  nik:window.location.href.split('/')[4]
                },
                success:function(result){
                  initSales(result)
                }
              })
            }
            
            var column = this;
            var title = $(this).text();
            var select = $('<select class="form-control select2 kat_drop" style="width:100%" name="kat_drop" ><option value="" selected>Filter</option></select>')
                .appendTo($("#status").find("td").eq(column.index()))
                .on('change', function() {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val());

                    column.search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });

            column.data().unique().sort().each(function(d, j) {
                // console.log(d + ' ' + j)
                select.append('<option>' + d + '</option>')
            });

            function initSales(parameter){
              if (column.index() == 1) {
                column.data().unique().sort().each(function(d, j) {
                    // console.log(d + ' ' + j)
                    if (d == parameter) {
                      select.append('<option selected>' + d + '</option>')
                    }
                });                 
              }
              
            }
        });

        $('.kat_drop').select2();
    },
    "footerCallback": function( row, data, start, end, display ) {
        
          var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'Rp' ).display;

          var api = this.api(),data;
            // Remove the formatting to get integer data for summation
          var intVal = function ( i ) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '')*1 :
          typeof i === 'number' ?
            i : 0;
          };

          var filtered = api.column( 15, {"filter": "applied"} ).data().sum();

          $( api.column(14).footer() ).html("Total Amount");
         
          $( api.column(15).footer() ).html(numFormat(filtered));

    },
    drawCallback: function() {
    }
  });

  // var table = initTable()  

  var buttons = new $.fn.dataTable.Buttons(table, {
     buttons: [{
        text: '<i class="fa fa-cloud-download""></i> <b>PDF</b>',
        filename: function(){
          var today = new Date();
          // var n = d.getTime();
          var dd = today.getDate();
          var mm = today.getMonth() + 1; //January is 0!

          var yyyy = today.getFullYear();
          if (dd < 10) {
            dd = '0' + dd;
          } 
          if (mm < 10) {
            mm = '0' + mm;
          } 
          var today = dd + '-' + mm + '-' + yyyy;
          return 'Report PDF' + ' ' + '(' + today + ')';
        },
        extend: 'pdfHtml5',
        footer:true,
        className: 'btn btn-warning',
        title: 'Report Lead Register' + ' ' + '(' + moment(new Date()).format('YYYY-MM-DD h:mm:ss a') + ')',
        customize: function(doc) {
          //pageMargins [left, top, right, bottom] 
          doc.pageMargins = [ 30, 30, 30, 30 ];
          doc.styles['td:nth-child(5)'] = { 
             width: '100px',
             'max-width': '100px'
           }
        },
        pageSize: 'A4',
        pageMargins: [0, 0, 0, 0], // try #1 setting margins
        margin: [0, 0, 0, 0],
        content: [{
            style: 'fullWidth'
        }],
        styles: { // style for printing PDF body
            fullWidth: {
                fontSize: 14,
                bold: true,
                alignment: 'right',
                margin: [10, 10, 10, 10]
            }
        },

        exportOptions: {
            order: 'applied',
            stripHtml: true,
            modifier: {
                pageMargins: [0, 0, 0, 0], // try #3 setting margins
                margin: [0, 0, 0, 0], // try #4 setting margins
                padding: [5,5,5,5],
                alignment: 'center'
            },
            body: {
                margin: [0, 0, 0, 0],
                pageMargins: [0, 0, 0, 0]
            } // try #5 setting margins         
            ,
            columns: [9, 10, 11, 12, 13,14,15,16] //column id visible in PDF    
            ,
            format: {
                body: function ( data, row, column, node ) {
                    data = data.replace(/<.*?>/g, "");
                    return $.trim(data);

                    column
                }
            },
            columnGap: 1,
        },

    }],
  }).container().appendTo($('#coba'));

  $('.buttons.pdfHtml5').each(function() {
      $(this).removeClass('btn-default').addClass('btn btn-md btn-warning')
  })

  $('.money').mask('000,000,000,000,000', {reverse: true});
</script>
@endsection