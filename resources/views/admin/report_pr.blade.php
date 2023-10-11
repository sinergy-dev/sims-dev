@extends('template.main')
@section('tittle')
Report Purchase Request
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Report Purchase Request
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin</li>
      <li class="active">Purchase Request</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <select style="width: 100px; font-size: 14px;" class="form-control btn-primary" id="year_filter">
              <option value="{{$year}}"> &nbsp{{$year}}</option>
              @foreach($year_before as $years)
                @if($years->year != $year)
                  <option value="{{$years->year}}">{{$years->year}}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"> Total PR</h3>
          </div>
          <div class="box-body">
            <canvas id="myPieChart" height="350" width="787"></canvas>
          </div>
        </div>

        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"> Total Amount PR (By Type)</h3>
          </div>
          <div class="box-body">
            <canvas id="barChartByType"></canvas>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"> Total Amount PR (By Category)</h3>
          </div>
          <div class="box-body">
            <canvas id="myPieChartAmount" height="350" width="787"></canvas>
          </div>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"> Total PR (By Type)</h3>
          </div>
          <div class="box-body">
            <canvas id="myBarChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount PR (By Category)</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTableCat" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Category</th>
                      <th>Total</th>
                      <th>Amount</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount PR (By Id Project)</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTablePid" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>No</th>
                      <th>Project Id</th>
                      <th>Total</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Top 5 Supplier</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTableSupplierPr" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Supplier</th>
                      <th>Total</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount Internal PR (By Category)</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTableAmountIpr" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Category</th>
                      <th>Total</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Total Amount External PR (By Category)</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTableAmountEpr" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Category</th>
                      <th>Total</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>
    </div>

    <!-- <div class="row">
      <div class="col-lg-6 col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
          <h3 class="box-title">Top 5 Supplier</h3>
          </div>

          <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered display no-wrap" id="dataTableSupplierPr" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Supplier</th>
                      <th>Total</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody id="products-list" name="products-list">
                  </tbody>
                </table>
            </div>  
          </div>
        </div>
      </div>
    </div> -->
      
  </section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">

    var chart1 = document.getElementById("myPieChart");
    var chart2 = document.getElementById("myPieChartAmount");
    var chart3 = document.getElementById("myBarChart");
    var chart4 = document.getElementById("barChartByType");
    var theHelp = Chart.helpers;

    var myPieChartpr;
    var myPieChart;
    var barChartByMonth;
    var barChartByType;

    $.ajax({
      type:"GET",
      url:"getTotalPrbyType",
      success:function(result){
        myPieChartpr = new Chart(chart1, {
          type: 'pie',
          data: {
            labels: ["IPR", "EPR"],
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            toolTipContent: "{y} (#percent%)",
            datasets: [{
              data: result,
              backgroundColor: ['#04dda3', '#246d18'],
            }],
          },
          options: {
            legend: {
              display: true,
              labels: {
                generateLabels: function(chart) {
                  var data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map(function(label, i) {
                      var meta = chart.getDatasetMeta(0);
                      var ds = data.datasets[0];
                      var arc = meta.data[i];
                      var custom = arc && arc.custom || {};
                      var getValueAtIndexOrDefault = theHelp.getValueAtIndexOrDefault;
                      var arcOpts = chart.options.elements.arc;
                      var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
                      var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
                      var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);
                      return {
                        text: label + ": " + parseFloat(ds.data[i]).toFixed(2) + "% ",
                        fillStyle: fill,
                        strokeStyle: stroke,
                        lineWidth: bw,
                        hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                        index: i
                      };
                    });
                  }
                  return [];
                }
              }
            },
            tooltips: {
              mode: 'label',
              label: 'mylabel',
              callbacks: {
                label: function(tooltipItem, data) {
                  return data.labels[tooltipItem['index']] + ': ' + data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + ' %';
                }
              }
            },
          },
        });
      }
    })

    $.ajax({
      type:"GET",
      url:"/getAmountByCategory",
      success:function(result){
        var labelCategoryPR = result.label
        var valueCategoryPR = result.precentage
        // console.log(Object.keys(result.data[0]))
        // console.log(Object.values(result.data[0]))

        myPieChart = new Chart(chart2, {
          type: 'pie',
          data: {
            labels: labelCategoryPR,
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            toolTipContent: "{y} (#percent%)",
            datasets: [{
              data: valueCategoryPR,
              backgroundColor: [
              "#EA2027",
              "#EE5A24",
              "#F79F1F",
              "#FFC312",
              "#C4E538",
              "#A3CB38",
              "#009432",
              "#006266",
              "#1B1464",
              "#0652DD",
              "#1289A7",
              "#12CBC4",
              "#FDA7DF",
              "#D980FA",
              "#9980FA",
              '#4287F5',
              '#F542F5',
              '#CB42F5',],
            }],
          },
          options: {
            legend: {
              display: true,
              position:'right',
              labels: {
                generateLabels: function(chart) {
                  var data = chart.data;
                  if (data.labels.length && data.datasets.length) {
                    return data.labels.map(function(label, i) {
                      var meta = chart.getDatasetMeta(0);
                      var ds = data.datasets[0];
                      var arc = meta.data[i];
                      var custom = arc && arc.custom || {};
                      var getValueAtIndexOrDefault = theHelp.getValueAtIndexOrDefault;
                      var arcOpts = chart.options.elements.arc;
                      var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
                      var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
                      var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);
                      return {
                        text:  parseFloat(ds.data[i]).toFixed(2) + "% " + label ,
                        fillStyle: fill,
                        strokeStyle: stroke,
                        lineWidth: bw,
                        hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                        index: i
                      };
                    });
                  }
                  return [];
                }
              }
            },
            tooltips: {
              mode: 'label',
              label: 'mylabel',
              callbacks: {
                label: function(tooltipItem, data) {
                  return data.labels[tooltipItem['index']] + ': ' + parseFloat(data['datasets'][0]['data'][tooltipItem['index']]).toFixed(2) + ' %';
                }  
              }  
            },
          },
        });
      }
    })

    $.ajax({
      type:"GET",
      url:"getTotalAmountByType",
      success:function(result){
        var amount_IPR = result.data.map(function(e) {
            return e.amount_IPR
        })

        var amount_EPR = result.data.map(function(e) {
            return e.amount_EPR
        })

        barChartByType = new Chart(chart4, {
          type: 'bar',
          data: {
              labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"],
              labels2:[amount_IPR,amount_EPR],        
              datasets: [{
                label: "IPR",
                backgroundColor: "#04dda3",
                borderColor: "#04dda3",
                data: amount_IPR
              },
              {
                label: "EPR",
                backgroundColor: "#246d18",
                borderColor: "#246d18",
                data: amount_EPR
              }]
        },
        options: {
          tooltips: {
            callbacks: {
              title: function(tooltipItem, data) {
              },
              label: function(tooltipItem, data) {
                return data.datasets[tooltipItem.datasetIndex].label + ': Rp.' + data['labels2'][tooltipItem.datasetIndex][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              }
            }
          },
            scales: {
              xAxes: [{
                barPercentage: 0.10,
                barThickness: 10,
                gridLines: {
                  display:false
                }
              }]
            }
          }
        });

        // console.log(barChartByType.data.datasets)
      }
    })

    $.ajax({
      type:"GET",
      url:"getTotalPrByMonth",
      success:function(result){
        console.log(result)
        var total_ipr = result.data.map(function(e) {
            return e.IPR
        })

        var total_epr = result.data.map(function(e) {
            return e.EPR
        })

        barChartByMonth = new Chart(chart3, {
          type: 'bar',
          data: {
              labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"],
              datasets: [{
                label: "IPR",
                backgroundColor: "#04dda3",
                borderColor: "#04dda3",
                data: total_ipr
              },
              {
                label: "EPR",
                backgroundColor: "#246d18",
                borderColor: "#246d18",
                data: total_epr
              }]
        },
        options: {
          tooltips: {
            callbacks: {
              title: function(tooltipItem, data) {
              },
              label: function(tooltipItem, data) {
                return data.datasets[tooltipItem.datasetIndex].label + ' Total: ' + data['datasets'][tooltipItem.datasetIndex]['data'][tooltipItem['index']]
              }
            }
          },
            scales: {
              xAxes: [{
                barPercentage: 0.10,
                barThickness: 10,
                gridLines: {
                  display:false
                }
              }]
            }
          }
        });
      }
    })

    $('#dataTableCat').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTotalNominalByCat')}}"
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "category"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            },
            "orderData" : [4],
          },
          {
            "data":"nominal",
            "targets":[3],
            "visible":false
          }
        ],
        "order":[],
      pageLength: 10,
    })

    var table = $('#dataTablePid').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTotalNominalByPid')}}"
        },
        "columns": [
          {
            className: 'dt-control',
            orderable: false,
            data: null,
            defaultContent: '',
          },
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            },
          },
          { "data": "id_project"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            },
            "orderData" : [5],
          },
          {
            "data":"nominal",
            "targets":[3],
            "visible":false
          }
        ],
        "order":[],
      pageLength: 10,
    })

    $('#dataTablePid tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
 
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    var formatter = new Intl.NumberFormat('en-US', {
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    });
    /* Formatting function for row details - modify as you need */
    function format(d) {
        // `d` is the original data object for the row
      var append = ""
      append = append +'<table class="table table-bordered table-striped" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">' 
      append = append +'<tr>' 
      append = append +  '<td>No PR:</td>' 
      append = append +  '<td>Subject:</td>' 
      append = append +  '<td>Amount:</td>' 
      d.get_pr.forEach((item) => {
      //You can perform your desired function out here
      console.log(item.no_pr)
        append = append + '<tr>' 
          append = append +   '<td>'+ item.no_pr +'</td>' 
          append = append +   '<td>'+ item.title +'</td>' 
          append = append +   '<td>'+ formatter.format(item.amount) +'</td>'
        append = append + '</tr>'
      })
      append = append +'</table>' 

      return append;

    }

    $('#dataTableAmountIpr').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTotalNominalByCatIpr')}}"
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "category"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            },
            "orderData" : [4],
          },
          {
            "data":"nominal",
            "targets":[3],
            "visible":false
          }
        ],
        "order":[],
      pageLength: 10,
    })

    $('#dataTableAmountEpr').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTotalNominalByCatEpr')}}"
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "category"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            },
            "orderData" : [4],
          },
          {
            "data":"nominal",
            "targets":[3],
            "visible":false
          }
        ],
        "order":[],
      pageLength: 10,
    })

    $('#dataTableSupplierPr').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTopFiveSupplier')}}"
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "to_replace"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            },
            "orderData" : [4],
          },
          {
            "data":"nominal",
            "targets":[3],
            "visible":false
          }
        ],
        "order":[],
      pageLength: 10,
      searching: false,
      paging: false,
      info: false
    })

    $("#year_filter").change(function(){
      $('#dataTableCat').DataTable().ajax.url("{{url('getTotalNominalByCatYear')}}?year=" + this.value).load();
      $('#dataTablePid').DataTable().ajax.url("{{url('getTotalNominalByPidYear')}}?year=" + this.value).load();
      $('#dataTableAmountIpr').DataTable().ajax.url("{{url('getTotalNominalByCatIprYear')}}?year=" + this.value).load();
      $('#dataTableAmountEpr').DataTable().ajax.url("{{url('getTotalNominalByCatEprYear')}}?year=" + this.value).load();
      $('#dataTableSupplierPr').DataTable().ajax.url("{{url('getTopFiveSupplierYear')}}?year=" + this.value).load();

      myPieChartpr.data.labels = [];
      myPieChartpr.data.datasets.forEach((dataset) => {
          dataset.data = [];
      });
      myPieChartpr.update();

      myPieChart.data.labels = [];
      myPieChart.data.datasets.forEach((dataset) => {
          dataset.data = [];
      });
      myPieChart.update();

      barChartByMonth.data.labels = [];
      barChartByMonth.data.datasets.forEach((dataset) => {
          dataset.data = [];
      });
      barChartByMonth.update();

      barChartByType.data.labels = [];
      barChartByType.data.datasets.forEach((dataset) => {
          dataset.data = [];
      });
      barChartByType.update();

      $.ajax({
        type:"GET",
        url:"getTotalPrbyTypeYear",
        data: {
          year: this.value, 
        },
        success:function(result){
          myPieChartpr.data.labels = result['dataTotalPr']['label'];
          myPieChartpr.data.datasets.forEach((dataset) => {
              dataset.data = result['dataTotalPr']['data'];
          });
          myPieChartpr.update();

          myPieChart.data.labels = result['dataAmountByCat']['label'];
          myPieChart.data.datasets.forEach((dataset) => {
              dataset.data = result['dataAmountByCat']['precentage'];
          });
          myPieChart.update();

          barChartByMonth.data.labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"];
          var total_ipr = result['dataTotalPrByCat'].map(function(e) {
              return e.IPR
          })

          var total_epr = result['dataTotalPrByCat'].map(function(e) {
              return e.EPR
          })

          barChartByMonth.data.datasets = [{
            label: "IPR",
            backgroundColor: "#04dda3",
            borderColor: "#04dda3",
            data: total_ipr
          },
          {
            label: "EPR",
            backgroundColor: "#246d18",
            borderColor: "#246d18",
            data: total_epr
          }]
          barChartByMonth.update();


          barChartByType.data.labels = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"];
          var amount_IPR = result['dataAmountPrByType'].map(function(e) {
              return e.amount_IPR
          })

          var amount_EPR = result['dataAmountPrByType'].map(function(e) {
              return e.amount_EPR
          })

          console.log(amount_EPR)
          barChartByType.data.labels2 = [amount_IPR,amount_EPR]
          barChartByType.data.datasets = [{
            label: "IPR",
            backgroundColor: "#04dda3",
            borderColor: "#04dda3",
            data: amount_IPR
          },
          {
            label: "EPR",
            backgroundColor: "#246d18",
            borderColor: "#246d18",
            data: amount_EPR
          }]
          barChartByType.update();
        }
      })
    });

  </script>
@endsection
