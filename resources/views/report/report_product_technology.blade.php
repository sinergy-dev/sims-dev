@extends('template.template_admin-lte')
@section('content')
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
<section class="content-header">
  <h1>
    Report Lead Product Technology
  </h1>
  <ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Report</li>
    <li class="active">Report Lead Product Technology</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-table"></i> Report Lead</h3>
        </div>

        <div class="row">   
          <div class="col-md-12">
            <div class="col-md-4">
              <label>Filter by Product</label>
              <select class="form-control" style="width: 100%;max-width: 250px" id="searchTagsProduct"></select>
            </div>
            <div class="col-md-4">
              <label>Filter by Technology</label>
              <select class="form-control" style="width: 100%;max-width: 250px" id="searchTagsTechnology"></select>
            </div>
            <div class="col-md-4">
              <label>Filter by Person</label>
              <select class="form-control capitalize" style="width: 100%;max-width: 250px" id="searchTagsPerson"></select>
            </div>
          </div>  
          <div class="col-md-12" style="margin-top: 10px">
            <div class="col-md-4">
              <label>Filter by Date</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" style="width: 100%" id="reportrange" name="Dates" autocomplete="off" placeholder="Select days" required />
                <span class="input-group-addon" style="cursor: pointer" type="button" id="daterange-btn"><i class="fa fa-caret-down"></i></span>
              </div>
            </div>
            <div class="col-md-4">
              <button class="btn btn-primary btn-sm" id="apply-btn" style="margin-top: 25px"><i class="fa   fa-check-circle"></i> Apply</button>
               <button class="btn btn-info btn-sm reload-table" id="reload-table" style="margin-top: 25px"><i class="fa fa-refresh"></i> Refresh</button>
            </div>
          </div>     
          
          <!-- <div class="col-md-8" style="margin-top: 10px;"> -->
          <!--   <div class="input-group">
                <select class="form-control a" style="width: 100%;max-width: 250px" id="searchTags"></select>
                <span class="input-group-btn">
                    <button class="btn btn-info">MyButton</button>
                </s

                pan>
            </div> -->
         <!--    <label>Filter by Tags</label>
            <select class="form-control a" style="width: 100%" id="searchTags"></select>
            <button class="btn btn-primary a">Apply</button>    -->     
          <!-- </div>  -->
          </div>  

          <div class="box-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="data_lead" width="100%" cellspacing="0">
                <thead>
                  <tr class="header">
                    <th>Lead ID</th>
                    <th>Customer</th>
                    <th>Opty Name</th>                    
                    <th>Persona</th>
                    <th>Nominal</th>
                    <th>Product/Technology</th>
                    <th>List Price</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>       
        
        </div>
      </div>  
    </div>
  </div>
</section>

@endsection
@section('script')
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
  <script type="text/javascript">

    $.ajax({
        url:"sales/getProductTag",
        type:"GET",
        success:function(result){
          console.log(result)
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
            placeholder: " Select #Tags#Product",
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
            console.log(select_val);
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

               console.log(data);
            });
          }

          function disableAllOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id == "-1") {
                data.disabled = disabled;
              }

               console.log(data);
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
            placeholder: " Select #Tags#Technology",
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
            console.log(select_val);
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

               console.log(data);
            });
          
          }

          function disableAllOpt(evt, target, disabled) {

            var aaList = $("option", target);
            $.each(aaList, function (i, item) {
              var data = $(item).data("data");

              if (data.id == "-1") {
                data.disabled = disabled;
              }

               console.log(data);
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
              text: 'All'
          };

          selectOption.push(data)
          $.each(arr,function(key,value){
            selectOption.push(value)
          })

          var TagPersona = $("#searchTagsPerson").select2({
            placeholder: " Select #Tags#Sales#Presales",
            allowClear: true,
            multiple:true,
            data:selectOption,
            templateSelection: function(selection,container) {
              console.log(selection)
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
        "paging": false
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
      var TagsProduct;
      var TagsTechno;
      var TagsPersona;

      TagsProduct = $("#searchTagsProduct").val();
      TagsTechno  = $("#searchTagsTechnology").val();
      TagsPersona = $("#searchTagsPerson").val();

      console.log(TagsPersona)

      if (TagsProduct == "" || TagsTechno == "" || TagsPersona == "") {
        Swal.fire({
          title: 'Can`t Process',
          text: "Please Select All Tags to filter!",
          icon: 'danger',
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
                // "url":"{{url('/getFilterTags')}}?TagsProduct="+ TagsProduct,
                "url":"{{url('/getFilterTags')}}",
                "data":{
                  "TagsProduct":TagsProduct,
                  "Tagstechno":TagsTechno,
                  "TagsPersona":TagsPersona,
                  "start_date":start_date,
                  "end_date":end_date
                }
              },
              "columns": [
                // { "data": "lead_id" },  
                // { "data": "brand_name" },  
                // { "data": "opp_name" },  
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return "<b>"+row.lead_id+"</b>";
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return "<b>"+row.lead_id+"</b>";
                      }else{
                        return "";
                      }
                    }
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return "<b>"+row.brand_name+"</b>";
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return "<b>"+row.brand_name+"</b>";
                      }else{
                        return "";
                      }
                    }
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (meta.row == 0) {
                      return "<b>"+row.opp_name+"</b>";
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        return "<b>"+row.opp_name+"</b>";
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
                        return "<b>"+row.name_presales+"</b>";
                      }else if (row.name_presales == null) {
                        return "<b>"+row.name_sales+"</b>";
                      }else{
                        return "<b>"+row.name_sales +','+"</b>"+"<b>"+ row.name_presales+"</b>";
                      }                      
                    }else{
                      if (table.rows({ selected: true }).data()[meta.row]['lead_id'] != table.rows({ selected: true }).data()[meta.row -1]['lead_id']) {
                        if (row.name_sales == null) {
                          return "<b>"+row.name_presales+"</b>";
                        }else if (row.name_presales == null) {
                          return "<b>"+row.name_sales+"</b>";
                        }else{
                          return "<b>"+row.name_sales + ',' + "</b>"+"<b>" + row.name_presales +"</b>";
                        }
                      }else{
                        return "";
                      }
                    }
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
                  }
                },
                {
                  render: function ( data, type, row, meta ) {
                    if (row.name_product != null) {
                      return "<span style='color:#4075c9'>"+row.name_product+"</span>";
                    }
                    if (row.name_tech != null) {
                      return "<span style='color:#448c35;'>"+row.name_tech+"</span>";
                    }
                  }
                }, 
                {
                  render: function ( data, type, row, meta ) {
                    return $.fn.dataTable.render.number(',', '.', 0, 'Rp.').display(row.price);
                  }
                },
                // { "data": "amount" },        
              ],
              // "info":false,
              "scrollX": false,
              "ordering": false,
              "processing": true,
              "paging": false,
              // "columnDefs": [
              //   { "orderable": false, "targets": [0,1,2,3,4,5,6] }
              // ]
          })
      }
      // start_date  = $("#reportrange").val().split("-")[0];
      // end_date    = $("#reportrange").val().split("-")[1];
      // console.log($("#daterange-btn").datepicker("getDate"))

      console.log(start_date,end_date)
    })

    $("#reload-table").click(function(){
      location.reload();
      $('#data_lead').DataTable().clear().destroy();
      $('#data_lead').DataTable({
        "paging": false
      });
      $("#searchTagsProduct").val(null).trigger("change");
      $("#searchTagsTechnology").val(null).trigger("change");
      $("#searchTagsPerson").val(null).trigger("change");
    })

    
  </script>
@endsection