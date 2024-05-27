@extends('template.main')
@section('tittle')
  Dashboard
@endsection
@section('head_css')
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Dashboard Asset Management
    </h1>
    <ol class="breadcrumb">
    <li><a href="{{url('asset/index')}}"><i class="fa fa-dashboard"></i> Asset</a></li>
    <li class="active">Dashboard</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-8 col-xs-12">
        <div class="row">
          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3 id="countAll" class="counter"></h3>
                <p>Total Assets</p>
              </div>
              <div class="icon">
                <i class="fa fa-table"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-green">
              <div class="inner">
                <h3 id="countInstalled" class="counter"></h3>
                <p>Installed</p>
              </div>
            <div class="icon">
              <i class="fa fa-gear"></i>
            </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3 id="countAvailable" class="counter"></h3>
                <p>Available</p>
              </div>
              <div class="icon">
                <i class="fa fa-archive"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-red">
              <div class="inner">
                <h3 id="countRma" class="counter"></h3>
                <p>RMA</p>
              </div>
              <div class="icon">
                <i class="fa fa-list"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Asset Owner</h3>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="chart-responsive">
                      <canvas id="doughnutChartAssetOwner" height="300" width="400" style="width: 400px; height: 300px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Category</h3>
              </div>

              <div class="box-body">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="chart-responsive">
                      <canvas id="doughnutChartCategory" height="300" width="400" style="width: 400px; height: 300px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Vendor</h3>
              </div>

              <div class="box-body">
                <div class="chart-responsive">
                  <canvas id="doughnutChartVendor" height="300" width="400" style="width: 400px; height: 300px;"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Client</h3>
              </div>

              <div class="box-body">
                <div class="row">
                  <div class="col-md-12 col-xs-12">
                    <div class="chart-responsive">
                      <canvas id="doughnutChartClient" height="300" width="400" style="width: 400px; height: 300px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-xs-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Recent Activities</h3>
          </div>
          <div class="box-body">
            <div class="table-responsive" style="scrollbar-width:thin">
              <ul id="tb_LogActivity" style="min-height: fit-content;max-height: 1000px;scr">
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
@section('scriptImport')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
@endsection
@section('script')
  <script type="text/javascript">
    let chartAssetOwner = document.getElementById("doughnutChartAssetOwner");
    let chartCategory = document.getElementById("doughnutChartCategory");
    let chartVendor = document.getElementById("doughnutChartVendor");
    let chartClient = document.getElementById("doughnutChartClient");
    var arrColorVendor = [], arrColorClient = [], arrColor = []

    $.ajax({
      url:"{{url('asset/getChartAssetOwner')}}",
      type:"GET",
      delay:200,
      success:function(result){
        const dataAsset = {
          labels: result[0].name,
          datasets: [{
            data: result[0].chart,
            backgroundColor: [
              "#2e4080",
              "#4459a6",
              "#5a73cc"
            ],
            hoverOffset: 4
          }]
        };

        var doughnutChartAssetOwner = new Chart(chartAssetOwner, {
          type:"doughnut",
          data: dataAsset,
          options: {
            responsive: true,
            showTooltips: true,
            legend: {
              display: false
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                    var dataLabel = data.labels[tooltipItem.index];
                    var value = tooltipItem.yLabel;
                    return dataLabel + ' - ' + data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
                }
              }
            },
          },
        })

        return doughnutChartAssetOwner
      }
    })

    $.ajax({
      url:"{{url('asset/getChartCategory')}}",
      type:"GET",
      success:function(result){
        const dataCategory = {
          labels: result[0].name,
          datasets: [{
            data: result[0].chart,
            backgroundColor: [
              '#0c2636',
              '#184d6e',
              '#31788c',
              '#44a6c2'
            ],
            hoverOffset: 4
          }]
        };

        var doughnutChartCategory = new Chart(chartCategory, {
          type:"doughnut",
          data: dataCategory,
          options: {
            responsive: true,
            legend: {
              position: 'top',
              display:false
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                    var dataLabel = data.labels[tooltipItem.index];
                    var value = tooltipItem.yLabel;
                    return dataLabel + ' - ' + data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
                }
              }
            },
          },
        })

        return doughnutChartCategory
      }
    })

    $.ajax({
      url:"{{url('asset/getColor')}}",
      type:"GET",
      success:function(result){
        $.each(result.colors,function(item,value){
          arrColorVendor.push("#"+value.hex)
          arrColorClient.push("#"+value.hex)
        })

        arrColorVendor = arrColorVendor
        arrColorClient = arrColorClient
      },
      async: false
    })

    $.ajax({
      url:"{{url('asset/getChartVendor')}}",
      type:"GET",
      success:function(response){
        InitiateChartVendor(arrColorVendor,response,"doughnutChartVendor")
      }
    })

    $.ajax({
      url:"{{url('asset/getChartClient')}}",
      type:"GET",
      success:function(response){
        InitiateChartClient(arrColorClient,response,"doughnutChartClient")     
      }
    })

    function InitiateChartVendor(arrColor,result,nameChart){
      const dataVendor = { 
        labels: result[0].name,
        datasets: [{
          data: result[0].chart,
          backgroundColor:arrColorVendor.slice(0, result[0].chart.length),
          hoverOffset: 4
        }]
      };

      var options = {
        legend: {
          position: 'top',
          display:false
        },
        tooltips: {
          callbacks: {
              label: function(tooltipItem, data) {
                  var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                  var dataLabel = data.labels[tooltipItem.index];
                  var value = tooltipItem.yLabel;
                  return dataLabel + ' - ' + data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
              }
          }
        },
      };

      var nameChart = new Chart(chartVendor, {
        type:"doughnut",
        data: dataVendor,
        options: options
      })
    } 

    function InitiateChartClient(arrColor,result,nameChart){
      const dataClient = {
        labels: result[0].name,
        datasets: [{
          data: result[0].chart,
          backgroundColor:arrColorClient.slice(0, result[0].chart.length),
          hoverOffset: 4
        }]
      };

      var nameChart = new Chart(chartClient, {
        type:"doughnut",
        data: dataClient,
        options: {
          responsive: true,
          legend: {
            position:'right',
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
                    var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
                    var arcOpts = chart.options.elements.arc;
                    var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
                    var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
                    var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

                    // We get the value of the current label
                    var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

                    return {
                      // Instead of `text: label,`
                      // We add the value to the string
                      text: label + " : " + value + "%",
                      fillStyle: fill,
                      strokeStyle: stroke,
                      lineWidth: bw,
                      hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                      index: i
                    };
                  });
                } else {
                  return [];
                }
              }
            }
          },
          tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                    var dataLabel = data.labels[tooltipItem.index];
                    var value = tooltipItem.yLabel;
                    return dataLabel + ' - ' + data['datasets'][0]['data'][tooltipItem['index']].toFixed(2) + '%';
                }
            }
          },
        },
        centerText: {
          display: true,
          text: ""
        }
      })
    }

    $.ajax({
      type:"GET",
      url:"{{'/asset/getLog'}}",
      success:function(result){
        var append = ""

        $.each(result,function(item,value){
          append = append + '<li style="padding:10px">'
            append = append + '('+ value.date_add +') - '+ value.operator + ' ' + value.activity
          append = append + '</li>'
        })

        $("#tb_LogActivity").append(append)
      }
    })

    $.ajax({
      url:"{{url('asset/getCountDashboard')}}",
      type:"GET",
      success:function(response){
        $("#countAll").text(response.countAll)
        $("#countInstalled").text(response.countInstalled)
        $("#countAvailable").text(response.countAvailable)
        $("#countRma").text(response.countRma)

        $('.counter').each(function () {
          var size = $(this).text().split(".")[1] ? $(this).text().split(".")[1].length : 0;
          $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
          }, {
            duration: 1000,
            step: function (func) {
               $(this).text(parseFloat(func).toFixed(size));
            }
          });
        });
      }
    })
  </script>
@endsection