@extends('template.main')
@section('tittle')
  Dashboard
@endsection
@section('head_css')
@endsection
@section('content')
  <section class="content-header">
    <h1>
      Dashboard
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
                <h3>150</h3>
                <p>New Orders</p>
              </div>
              <div class="icon">
                <i class="fa fa-table"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-green">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Bounce Rate</p>
              </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>44</h3>
                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-xs-12">
            <div class="small-box bg-red">
              <div class="inner">
                <h3>65</h3>
                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 col-xs-12">
            <div class="box box-default">
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
            <div class="box box-default">
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
            <div class="box box-default">
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
            <div class="box box-default">
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
            <div class="table-responsive">
              <table class="table no-margin" style="border: none;">
                  <tr>
                    <td style="border: none;">(2024-10-12) - Engineer has added Router to asset management</td>
                  </tr>
                  <tr>
                    <td style="border: none;">(2024-10-12) - Engineer has added Router to asset management</td>
                  </tr>
                  <tr>
                    <td style="border: none;">(2024-10-12) - Engineer has added Router to asset management</td>
                  </tr>
                  <tr>
                    <td style="border: none;">(2024-10-12) - Engineer has added Router to asset management</td>
                  </tr>
                  <tr>
                    <td style="border: none;">(2024-10-12) - Engineer has added Router to asset management</td>
                  </tr>
              </table>
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
    var chartAssetOwner = document.getElementById("doughnutChartAssetOwner");
    var chartCategory = document.getElementById("doughnutChartCategory");
    var chartVendor = document.getElementById("doughnutChartVendor");
    var chartClient = document.getElementById("doughnutChartClient");

    const dataAsset = {
      labels: [
        'SIP',
        'Distributor',
        'Principal'
      ],
      datasets: [{
        label: 'My First Dataset',
        data: [300, 50, 100],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)'
        ],
        hoverOffset: 4
      }]
    };

    const dataCategory = {
      labels: [
        'SIP',
        'Distributor',
        'Principal'
      ],
      datasets: [{
        label: 'My First Dataset',
        data: [300, 50, 100],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)'
        ],
        hoverOffset: 4
      }]
    };

    const dataVendor = {
      labels: [
        'SIP',
        'Distributor',
        'Principal'
      ],
      datasets: [{
        label: 'My First Dataset',
        data: [300, 50, 100],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)'
        ],
        hoverOffset: 4
      }]
    };

    const dataClient = {
      labels: [
        'SIP',
        'Distributor',
        'Principal'
      ],
      datasets: [{
        label: 'My First Dataset',
        data: [300, 50, 100],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
          'rgb(255, 205, 86)'
        ],
        hoverOffset: 4
      }]
    };

    var doughnutChartAssetOwner = new Chart(chartAssetOwner, {
      type:"doughnut",
      data: dataAsset,
      options: {
        responsive: true,
        legend: {
          position: 'top',
          display:false
        },
      },
    })

    var doughnutChartCategory = new Chart(chartCategory, {
      type:"doughnut",
      data: dataCategory,
      options: {
        responsive: true,
        legend: {
          position: 'top',
          display:false
        },
      },
    })

    var doughnutChartVendor = new Chart(chartVendor, {
      type:"doughnut",
      data: dataVendor,
      options: {
        responsive: true,
        legend: {
          position: 'top',
          display:false
        },
      },
    })

    var doughnutChartClient = new Chart(chartClient, {
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
                    text: label + " : " + value,
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
        }
      },
      centerText: {
        display: true,
        text: ""
      }
    })
  </script>
@endsection