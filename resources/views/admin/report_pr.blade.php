@extends('template.main')
@section('tittle')
Report Purchase Request
@endsection
@section('head_css')
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">
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
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title"> Total PR</h3>
          </div>
          <div class="box-body">
            <canvas id="myPieChart" style="height: 250px; width: 787px;" height="350" width="787"></canvas>
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
            <canvas id="myPieChartAmount" style="height: 250px; width: 787px;" height="350" width="787"></canvas>
          </div>
        </div>

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"> Total PR (By Category)</h3>
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
      
  </section>

@endsection

@section('scriptImport')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">

    var chart1 = document.getElementById("myPieChart");
    var chart2 = document.getElementById("myPieChartAmount");
    var chart3 = document.getElementById("myBarChart");
    var chart4 = document.getElementById("barChartByType");

    $.ajax({
      type:"GET",
      url:"getTotalPrbyType",
      success:function(result){
        var myPieChart = new Chart(chart1, {
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
        var labelCategoryPR = Object.keys(result.data[0])
        var valueCategoryPR = Object.values(result.data[0])
        // console.log(Object.keys(result.data[0]))
        // console.log(Object.values(result.data[0]))

        var myPieChart = new Chart(chart2, {
          type: 'pie',
          data: {
            // labels: ['Barang dan Jasa','Barang', 'Jasa', 'Bank Garansi','Service', 'Pajak Kendaraan', 'ATK', 'Aset', 'Tinta', 'Training', 'Ujian', 'Tiket', 'Akomodasi', 'Swab Test', 'Other'],
            labels: labelCategoryPR,
            indexLabel: "#percent%",
            percentFormatString: "#0.##",
            toolTipContent: "{y} (#percent%)",
            datasets: [{
              data: valueCategoryPR,
              // data: [amount_bnj,amount_barang, amount_jasa,amount_bg,amount_servis,amount_pajak,amount_atk,amount_aset,amount_tinta,amount_training,amount_ujian,amount_tiket,amount_akomodasi, amount_swab, amount_other],
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
              "#9980FA",],
            }],
          },
          options: {
            legend: {
                display: true,
                position:'right'
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
      url:"getTotalPrByMonth",
      success:function(result){
        console.log(result)
        var total_ipr = result.data.map(function(e) {
            return e.IPR
        })

        var total_epr = result.data.map(function(e) {
            return e.EPR
        })

        var barChartByStatus = new Chart(chart3, {
          type: 'bar',
          data: {
              labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "Desember"],
              labels2:[total_ipr,total_epr],        
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
            }
            ]
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

        var barChartByStatus = new Chart(chart4, {
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
            }
            ]
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
            }
          }
        ],
        "order":[],
      pageLength: 10,
    })

    $('#dataTablePid').DataTable({
      "ajax":{
          "type":"GET",
          "url":"{{url('getTotalNominalByPid')}}"
        },
        "columns": [
          { 
            render: function (data, type, row, meta){
              return ++meta.row             
            }
          },
          { "data": "project_id"},
          { "data": "total"},
          { 
            render: function ( data, type, row ) {
              return new Intl.NumberFormat('id').format(row.nominal)
            }
          }
        ],
        "order":[],
      pageLength: 10,
    })

  </script>
@endsection
