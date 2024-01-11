@extends('template.main')
@section('tittle')
Report Tagging
@endsection
@section('head_css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <style type="text/css">
    .a{
      vertical-align: middle;
    }

    .speech-bubble {
      position: relative;
      background:white;
      border-radius: .4em;
      padding: 10px;
      margin-top: 10px;
      box-shadow: 10px 5px 10px 5px #8f9aff;
    }

    .speech-bubble:after {
      content: '';
      position: absolute;
      top: 0;
      left: 3%;
      width: 0;
      height: 0;
      border: 9px solid transparent;
      border-bottom-color: white;
      border-top: 0;
      margin-left: -9px;
      margin-top: -9px;
    }

    .dataTables_filter {display: none;}

    .header th{
      background-color: #dddddd;
    }

    .capitalize{
       text-transform:capitalize;
    }
  </style>
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Report Tagging
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
      <li class="active">Report Tagging</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-table"></i> Report Tagging</h3>
          </div> 
          <div class="box-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter by Brand</label>
                  <select class="form-control" style="width: 90%;max-width: 250px" id="searchTagsProduct"></select> <span style="padding:5px;"><b>AND</b></span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter by Technology</label>
                  <select class="form-control" style="width: 100%;max-width: 250px" id="searchTagsTechnology"></select> 
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter by Person <small>(One or More)</small></label>
                  <select class="form-control capitalize" style="width: 100%;max-width: 250px" id="searchTagsPerson"></select>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Filter by Date</label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control" style="width: 100%" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                    <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                   <button class="btn btn-primary btn-sm" id="apply-btn" style="margin-top: 25px"><i class="fa   fa-check-circle"></i> Apply</button>
                  <button class="btn btn-info btn-sm reload-table" id="reload-table" style="margin-top: 25px"><i class="fa fa-refresh"></i> Refresh</button>
                  <!-- <button class="btn btn-danger btn-sm report-pdf" style="margin-top: 25px"><i class="fa fa-file-pdf-o"></i> PDF</button> -->
                  <button class="btn btn-success btn-sm report-excel" onclick="exportExcel('{{action('ReportController@reportExcelTag')}}')" style="margin-top: 25px"><i class="fa fa-file-excel-o"></i> Excel</button>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 col-xs-12">
                <!-- <div class="table-responsive"> -->
                  <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                    <thead>
                      <tr class="header">
                        <th>Lead ID</th>
                        <th>Customer</th>
                        <th>Opty Name</th>                    
                        <th>Persona</th>
                        <th>Brand/technology</th>
                        <th width="15%">Price/Set Tagging</th>
                        <th width="15%">Nominal (Deal Price)</th>
                        <th>Nominal</th>
                        <th>Nominal</th>
                      </tr>
                    </thead>
                  </table>
                <!-- </div> -->
              </div>
            </div>
          </div>   
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scriptImport')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
@endsection
@section('script')  
  <script type="text/javascript">
    $.ajax({
        url:"sales/getProductTag",
        type:"GET",
        success:function(result){
          $("#searchTagsProduct").select2().val("");
          var arr = result.results;
          var selectOption = [];
          var otherOption;
          var data = {
              id: -1,
              text: 'All Product'
          };

          // selectOption.push(otherOption)
          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          var TagProduct = $("#searchTagsProduct").select2({
            placeholder: " Select #Brand",
            allowClear: true,
            multiple:true,
            data:selectOption,
            templateSelection: function(selection,container) {
              if (selection.text == 'All Product') {
                return $.parseHTML('<span>' + selection.text + '</span>');
              }else{
                return $.parseHTML('<span>' + selection.text + '</span>');
              }
            }
          })

          $('#searchTagsProduct').on("select2:select", function(evt, f, g) {
            var selected_element = $(evt.currentTarget);
            var select_val = selected_element.val();
            if (select_val == -1) {
              disableOtherOpt(evt, this, true);
            }else {
              disableAllOpt(evt, this, true);
            }
          });

          $('#searchTagsProduct').on("select2:unselect", function(evt) {
            disableOtherOpt(evt, this, false);
            disableAllOpt(evt, this, false);
          });

          function disableOtherOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id != "-1") {
                data.disabled = disabled;
              }
            });
          }

          function disableAllOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id == "-1") {
                data.disabled = disabled;
              }
            });
          }
        }
    
    })

    $.ajax({
        url:"sales/getTechTag",
        type:"GET",
        success:function(result){
          
          $("#searchTagsTechnology").select2().val("");
          var arr = result.results;
          var selectOption = [];
          var otherOption;

          var data = {
              id: -1,
              text: 'All Technology'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          var TagTechnology = $("#searchTagsTechnology").select2({
            placeholder: " Select #Technology",
            allowClear: true,
            multiple:true,
            data:selectOption,
            templateSelection: function(selection,container) {
              if (selection.text == 'All Technology') {
                $(container).css("background-color", "#32a852");
                $(container).css("border-color","#32a852");
                return $.parseHTML('<span>' + selection.text + '</span>');
              }else{
                $(container).css("background-color", "#32a852");
                $(container).css("border-color","#32a852");
                return $.parseHTML('<span>' + selection.text + '</span>');
              }
            }
          })

          $('#searchTagsTechnology').on("select2:select", function(evt, f, g) {
            var selected_element = $(evt.currentTarget);
            var select_val = selected_element.val();
            if (select_val == -1) {
              disableOtherOpt(evt, this, true);
            }else {
              disableAllOpt(evt, this, true);
            }
          });

          $('#searchTagsTechnology').on("select2:unselect", function(evt) {
            disableOtherOpt(evt, this, false);
            disableAllOpt(evt, this, false);
          });

          function disableOtherOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id != "-1") {
                data.disabled = disabled;
              }
            });
          
          }

          function disableAllOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id == "-1") {
                data.disabled = disabled;
              }
            });
          
          }


          $('#searchTagsTechnology').on('change', function(){

          });
        }
    
    })

    $.ajax({
        url:"sales/getPersonaTags",
        type:"GET",
        success:function(result){
          // console.log(result)
          $("#searchTagsPerson").select2().val("");
          var arr = result;
          var selectOption = [];
          // var otherOption = "All";
          var data = {
              id: 2,
              text: 'All Person'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          var TagPersona = $("#searchTagsPerson").select2({
            placeholder: " Select #Sales #Presales",
            allowClear: true,
            multiple:true,
            data:selectOption,
            templateSelection: function(selection,container) {
              if (selection.text == 'All') {
                return $.parseHTML('<span>' + selection.text + '</span>');
              }else{
                var selectedOption = $(selection.element).parent('optgroup').attr('label');
                  if(selectedOption == 'Sales') {
                      $(container).css("background-color", "#e6a715");
                      $(container).css("border-color","#e6a715");
                      return selection.text;
                  }else if (selectedOption == 'Presales') {
                      $(container).css("background-color", "#e0511d");
                      $(container).css("border-color","#e0511d");
                      return $.parseHTML('<span>' + selection.text.toLowerCase() + '</span>');
                  }else{
                      return $.parseHTML('<span>' + selection.text + '</span>');
                  }
              }
            
            }
          })

          // $("#searchTagsPerson").on('select2:select', function (e) {
          //    var args = JSON.stringify(e.params, function(k, v) {
          //       // if (v && v.nodeName) return "[DOM node]";
          //       if (v instanceof $.Event) return "[$.Event]";
          //       return v;
          //     });
          //     // $("#results").append( + "<br />\r\n");
          //   // console.log(args)
          // });


        }
    
    })

    initTableBefore();

    function initTableBefore(){
      $('#data_lead').DataTable({
        "columnDefs":[
            {
              "targets":[7,8],
              "visible":false
            },
            { targets: 'no-sort', orderable: false }
          ],
        "paging": false,
        "scrollX": true
      });
      // $('#data_lead').append( '<tr><td colspan="5">' + '<center>No available data</center>' + '</td></tr>' );
      // $("#data_lead").DataTable({
      //   "paging": false
      // });
    }

    var start = moment().startOf('year');
    var end = moment().endOf('year');

    function cb(start,end){
        $('#reportrange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'))

        start_date  = start.format("YYYY-MM-DD 00:00:00");
        end_date    = end.format("YYYY-MM-DD 00:00:00");
    }

    $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'This Month'   : [moment().startOf('month'), moment().endOf('month')],
            'Last 3 Month' : [moment().startOf('month').subtract(3, 'months'), moment().endOf('month')],
            'Last 6 Month' : [moment().startOf('month').subtract(6, 'months'), moment().endOf('month')],
            'Last Year'    : [moment().startOf('year').subtract(1, 'year'),moment().endOf('year').subtract(1, 'year')],
            'This Year'    : [moment().startOf('year'),moment().endOf('year')],
          },
          locale: {
            format: 'DD/MM/YYYY'
          }
        },
      cb);

    cb(start,end);

    $("#apply-btn").click(function(){
      var TagsProduct = [];
      var TagsTechno = [];
      var TagsPersona = [];

      if ($("#searchTagsProduct").val() != '-1') {
        TagsProduct = TagsProduct + "&TagsProduct[]=" + $("#searchTagsProduct").val();
      }

      if ($("#searchTagsTechnology").val() != '-1') {
        TagsTechno  = TagsTechno + "&Tagstechno[]=" + $("#searchTagsTechnology").val();
      }

      if ($("#searchTagsPerson").val() != '2') {
        TagsPersona = TagsPersona + "&TagsPersona[]=" + $("#searchTagsPerson").val();
      }

      if ($("#searchTagsProduct").val() == "" || $("#searchTagsTechnology").val() == "" || $("#searchTagsPerson").val() == "") {
        Swal.fire({
          title: 'Can`t Process',
          text: "Please Select All Tags to filter!",
          icon: 'error',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Cancel',
          cancelButtonText: 'Okey',
        });
      }else{
          $('#data_lead').DataTable().clear().destroy();
          var table = $("#data_lead").DataTable({
              "ajax":{
                "type":"GET",
                "url":"{{url('/getFilterTags')}}?="+ TagsProduct + TagsTechno + TagsPersona + "&start_date=" + start_date + "&end_date=" + end_date,
              },
              "columns": [ 
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return row.lead_id;
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return row.lead_id;
                      }else{
                        return "";
                      }
                    }
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return row.brand_name;
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return row.brand_name;
                      }else{
                        return "";
                      }
                    }
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return row.opp_name;
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return row.opp_name;
                      }else{
                        return "";
                      }
                    }
                  }
                },
                {
                  render: function ( data, type, row, meta) {
                    if (meta.row == 0) {
                      if (row.name_sales == null) {
                        return row.name_presales;
                      }else if (row.name_presales == null) {
                        return row.name_sales;
                      }else{
                        return row.name_sales +','+ row.name_presales;
                      }                      
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        if (row.name_sales == null) {
                          return row.name_presales;
                        }else if (row.name_presales == null) {
                          return row.name_sales;
                        }else{
                          return row.name_sales + ',' + row.name_presales ;
                        }
                      }else{
                        return "";
                      }
                    }
                  }
                }, 
                {
                  render: function ( data, type, row, meta ) {
                    var append = ""
                    $.each(row.tag_product,function(key,value){
                      append += "<span class='badge bg-blue'>"+value+"</span>" + " <span class='badge bg-green'>"+row.tag_tech[key]+"</span><br>"
                    })
                    return append;
                  }
                }, 
                {
                  render: function ( data, type, row, meta ) {
                    var append = ""
                    $.each(row.tag_price,function(key,value){
                      append += $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(value) + "<br>"
                    })
                    return append
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount);
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.amount);
                      }else{
                        return "";
                      }
                    }
                  },
                  "orderData":[7]
                },
                { 
                  "data":"amount",
                  "targets":[6],
                  "searchable":true 
                }, 
                { 
                  className: "sum3",
                  data: null,
                  render: function ( data, type, row ) {
                     return row.amount
                  } 
                }     
              ],
              "columnDefs":[
                { orderable: true, targets: [6] },
                { orderable: false, targets: '_all' },
                { targets: [7,8], visible: false}
              ],
              "order": [[ 6, "desc" ]],
              "aaSorting": [],
              // "info":false,
              "scrollX": true,
              "processing": true,
              "paging": false,
          })
      }
    })

    $("#reload-table").click(function(){
      location.reload();
      $('#data_lead').DataTable().clear().destroy();
      $('#data_lead').DataTable({
        "columnDefs":[
            {
              "targets":[7,8],
              "visible":false
            },
            { targets: 'no-sort', orderable: false }
          ],
        "paging": false
      });
      $("#searchTagsProduct").val(null).trigger("change");
      $("#searchTagsTechnology").val(null).trigger("change");
      $("#searchTagsPerson").val(null).trigger("change");
    })

    function exportExcel(url){
      var TagsProduct = [];
      var TagsTechno = [];
      var TagsPersona = [];

      if ($("#searchTagsProduct").val() != '-1') {
        TagsProduct = TagsProduct + "&TagsProduct[]=" + $("#searchTagsProduct").val();
      }

      if ($("#searchTagsTechnology").val() != '-1') {
        TagsTechno  = TagsTechno + "&Tagstechno[]=" + $("#searchTagsTechnology").val();
      }

      if ($("#searchTagsPerson").val() != '2') {
        TagsPersona = TagsPersona + "&TagsPersona[]=" + $("#searchTagsPerson").val();
      }

      window.location = url + "?start_date=" + start_date + "&end_date=" + end_date  + TagsProduct + TagsTechno + TagsPersona 
    }

    
  </script>
@endsection