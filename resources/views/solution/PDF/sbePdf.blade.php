<!DOCTYPE html>
<html>
<head>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.17/css/AdminLTE.min.css"> -->

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style type="text/css">
    body{
      font-family: "Helvetica Neue", Arial, sans-serif;
      font-size: 12px;
    }

    .page {
    page-break-after:always;
    position: relative;
    }

    table{
      border-spacing: 0;
      width: 100%;
      padding-left: 20px;
      border-collapse: collapse;
      /*border: 0.5px solid #CCC;*/
      border: none;
      padding-top: 10px;
      font-family: "Helvetica Neue", Arial, sans-serif;
      font-size: 12px;
    }

    .table-bordered, .table-bordered th, .table-bordered td {
      border: 0.5px solid black;
      width: 100%;
    }

    .table-bordered-child, .table-bordered-child th, .table-bordered td {
      border: 0.5px solid black;
      width: 75%;
    }

  /*  th {
    background: #404853;
    background: linear-gradient(#687587, #404853);
    border-left: 0.5px solid rgba(0, 0, 0, 0.2);
    border-right: 0.5px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 12px;
    padding: 8px;
    text-align: left;
    text-transform: uppercase;
    }
    th:first-child {
    border-top-left-radius: 4px;
    border-left: 0;
    }
    th:last-child {
    border-top-right-radius: 4px;
    border-right: 0;
    }
    td {
    border-right: 0.5px solid #c6c9cc;
    border-bottom: 0.5px solid #c6c9cc;
    font-size: 10.5px;
    padding: 8px;
    }
    td:first-child {
    border-left: 0.5px solid #c6c9cc;
    }
    tr:first-child td {
    border-top: 0;
    }*/
/*    tr:nth-child(even) td {
    background: #e8eae9;
    }*/
/*    tr:last-child td:first-child {
    border-bottom-left-radius: 4px;
    }
    tr:last-child td:last-child {
    border-bottom-right-radius: 4px;
    }*/
    img {
      width: 40px;
      height: 40px;
      border-radius: 100%;
    }
    .center {
      text-align: center;
    }

    /* .footer{
      clear: both;
      position: relative;
      height: 200px;
      margin-bottom: 0px;
      margin-top:auto;
    } */

    .footer {
      position: fixed;
      padding: 10px 10px 0px 10px;
      bottom: 0;
      width: 100%;
    }

    .left {
        flex: 1; /* Takes up 1/2 of the container */
        padding-left: 10px;
    }

    .right {
        flex: 1; /* Takes up 1/2 of the container */
        padding-left: 10px;
        margin-left: 150px;
    }
  </style>
  <link rel="stylesheet" href="">
  <title>SBE - {{$getAll->opp_name}}</title>
</head>
<body>
  <img src="img/header-sbe.png" style="height:15px;width: 100%;" />
  <br><br>
  <div style="page-break-inside: avoid;">
    <h2 class="center">Service Budget Estimate(SBE) - {{$getAll->customer_legal_name}}<br>
      {{$getAll->opp_name}}
    </h2>
    <table style="width:100%">
      <tr>
        <td style="width:23%">Release Date</td>
        <td style="text-align: left">{{date('D, F j, Y')}}</td>
      </tr>
      <tr>
        <td>Presales ID</td>
        <td>{{$getAll->lead_id}}</td>
      </tr>
      <tr>
        <td>Project Owner</td>
        <td>{{$getAll->owner}}</td>
      </tr>
      <tr>
        <td>Project Location</td>
        <td>{{$getAll->project_location}}</td>
      </tr>
      <tr>
        <td>Duration Project</td>
        <td>{{$getAll->duration}}</td>
      </tr>
      <tr>
        <td>Estimate Running</td>
        <td>{{$getAll->estimated_running}}</td>
      </tr>
    </table>
  </div>
  
  <div style="page-break-inside: avoid;"> 
    @foreach($getFunction as $keys => $datas)
      <div style="display: flex;margin-top: -70px;">
        <div class="left">
          @if($keys == "Implementation")
            <div style="width: 100px;height: 50px;background-color: #789de5;color: white;text-align: center;margin-left: 10px;margin-top: 10px;padding-top: 25px;">
              <b style="text-align: center;">
                  {{$keys}}
              </b>
            </div>
          @elseif($keys == "Maintenance")
            <div style="width: 100px;height: 50px;background-color: #ea3323;color: white;text-align: center;margin-left: 10px;margin-top: 10px;padding-top: 25px;">
              <b style="text-align: center;">
                  {{$keys}}
              </b>
            </div>
          @else
            <div style="width: 100px;height: 50px;background-color: #f19e38;color: white;text-align: center;margin-left: 10px;margin-top: 10px;padding-top: 25px;">
              <b style="text-align: center;">
                  {{$keys}}
              </b>
            </div>
          @endif
        </div>
        <div class="right">
          @foreach($datas as $data_config)
            <table class="table-bordered">
                @if($keys == "Implementation")
                  <thead style="background-color:#789de5">
                @elseif($keys == "Maintenance")
                  <thead style="background-color:#ea3323">
                @else
                  <thead style="background-color:#f19e38">
                @endif
                <tr>
                  <th style="width: 20px;text-align: center;">No</th>
                  <th style="text-align: center;">Function</th>
                  <th style="text-align: center;">Total</th>
                </tr>
              </thead> 
              <?php $i = 0 ?>
              @foreach($data_config->get_function as $key => $datas_config)
              <tbody>
                  <tr>
                    <td style="text-align: center;">{{++$i}}</td>
                    <td style="text-align: left;">{{$key}}</td>
                    <td style="text-align: right">IDR {{number_format($datas_config['total_nominal'])}}</td>
                  </tr>
              </tbody>
              @endforeach
              <tfoot>
                <tr>
                  <th colspan="2" style="text-align:right;">Grand Total Cost</th>
                  <th style="text-align:right;">IDR {{number_format($data_config->detail_config_nominal)}}</th>
                </tr>
              </tfoot>   
            </table>
          @endforeach
        </div>
      </div>
    @endforeach   
        <table style="width:96.5%;">
          <tr>
            <th style="text-align:right;width: 65%;">Grand Total SBE Operational</th>
            <th style="text-align:right">IDR {{number_format($getNominal)}}</th>
          </tr>
        </table>  
        <table style="width: 100%;text-align: center;margin-top: 20px;">
          <tr>
            @foreach($getSign as $data_sign)
            <td>
              <div style="margin-top:15px">
                @if($data_sign->position == "SOL Manager")
                Approval By:<br><br>
                @else
                Issued By:<br><br>
                @endif
                <img src="{{$data_sign->ttd_digital}}" style="height:50px;width: 50px;background-size:cover ;">
                <br><u>{{$data_sign->name}}</u><br>
                {{$data_sign->position}}
              </div>
              <u></u>
              <br>
              <i>
                <b></b>
              </i>
            </td>
            @endforeach
          </tr>
        </table>  
      <br><br><br><br><br><br>
      <p>Note : If you have any further inqueries, please do not hesitate to contact us. We thank you for your kind attention.</p>
      <img src="img/header-sbe.png" class="footer" style="width:100%;height: 15px; A_CSS_ATTRIBUTE:all;position: absolute" />
  </div>
  
  @foreach($getConfig as $keyConf => $dataConfigs)
  <div style="page-break-before: always;margin-top: 50px;">
    <h3 style="text-align:center;">
      Service Budget Estimate(SBE) - {{$getAll->customer_legal_name}}<br>
      {{$getAll->opp_name}}
    </h3>
    <table>
      <tr>
        @if($keyConf == "Implementation")
          <td colspan="6" style="text-align:center;background-color: #789de5;color: white;"><b>{{$keyConf}}</b></td>
        @elseif($keyConf == "Maintenance")
          <td colspan="6" style="text-align:center;background-color: #ea3323;color: white;"><b>{{$keyConf}}</b></td>
        @else
          <td colspan="6" style="text-align:center;background-color: #f19e38;color: white;"><b>{{$keyConf}}</b></td>
        @endif
      </tr>
      @foreach($dataConfigs as $data_configs)
        <tr style="padding:30px">
          <td colspan="6">
            <table style="padding:30px">
              <tr>
                <td>Release Date</td>
                <td>{{date('D, F j, Y')}}</td>
              </tr>
              <tr>
                <td>Presales ID</td>
                <td>{{$getAll->lead_id}}</td>
              </tr>
              <tr>
                <td>Project Owner</td>
                <td>{{$getAll->owner}}</td>
              </tr>
              <tr>
                <td>Project Location</td>
                <td>{{$data_configs->project_location}}</td>
              </tr>
              <tr>
                <td>Duration Project</td>
                <td>{{$data_configs->duration}}</td>
              </tr>
              <tr>
                <td>Estimate Running</td>
                <td>{{$data_configs->estimated_running}}</td>
              </tr>
            </table>
          </td>
        </tr>
        @foreach($data_configs->detail_all_config_choosed as $data_config_choosed)
            <table style="width:100%">
              @if($keyConf == "Implementation")
                <tr style="border: 0.5px solid black;background-color: #789de5;">
              @elseif($keyConf == "Maintenance")
                <tr style="border: 0.5px solid black;background-color: #ea3323;">
              @else
                <tr style="border: 0.5px solid black;background-color: #f19e38;">
              @endif
                  <th style="border: 0.5px solid black;width: 5%;">No</th>
                  <th style="border: 0.5px solid black;width: 30%;">Items</th>
                  <th style="border: 0.5px solid black;width: 30%;">Detail Items</th>
                  <th style="border: 0.5px solid black;">Mandays</th>
                  <th style="border: 0.5px solid black;">Engineer</th>
                  <th style="border: 0.5px solid black;width: 30%;">Total</th>
                </tr>
                <?php $k = 0 ?>
                @foreach($data_config_choosed as $keyDatasConfigChoosed => $datasConfigChoosed)
                  <tr style="border: 0.5px solid black;">
                    <td style="border: 0.5px solid black;text-align: center;">{{++$k}}</td>
                    <td style="border: 0.5px solid black;text-align: center;">
                      @if($keyDatasConfigChoosed == 0)
                        {{$datasConfigChoosed->item}}
                      @endif
                    </td>
                    <td style="border: 0.5px solid black;text-align: left;">
                        {{$datasConfigChoosed->detail_item}}
                    </td>
                    <td style="border: 0.5px solid black;text-align: center;">{{$datasConfigChoosed->qty}}</td>
                    <td style="border: 0.5px solid black;text-align: center;">{{$datasConfigChoosed->manpower}}</td>
                    <td style="border: 0.5px solid black;text-align: right;">IDR {{number_format($datasConfigChoosed->total_nominal)}}</td>
                  </tr>
                @endforeach
                @foreach($data_configs->get_function as $key => $data_config_function_nominal)
                  @if($key == $datasConfigChoosed->item)
                    <tr style="border: 0.5px solid black;">
                      <th style="border: 0.5px solid black;" colspan="5">Total Cost</th>
                      <th style="border: 0.5px solid black;text-align: right;">IDR {{number_format($data_config_function_nominal['total_nominal'])}}</th>
                    </tr>
                  @endif
                @endforeach
            </table>
        @endforeach
      @endforeach
    </table>
    <?php $j = 0;$h = 0?>
    @foreach($getFunction as $keys => $datas)
      @if($keys == $keyConf)
        <table class="table-bordered-child" style="border: 0.5px solid black;">
          @if($keys == "Implementation")
            <thead style="background-color:#789de5">
          @elseif($keys == "Maintenance")
            <thead style="background-color:#ea3323">
          @else
            <thead style="background-color:#f19e38">
          @endif
            <tr>
              <th style="width: 20px;text-align: center;">No</th>
              <th style="border: 0.5px solid black;text-align: center;">Function</th>
              <th style="border: 0.5px solid black;text-align: center;">Total</th>
            </tr>
          </thead>  
          @foreach($datas as $data_config)
          <tbody>
              @foreach($data_config->get_function as $key_config => $datas_config)
            <tr>
              <td style="border: 0.5px solid black;text-align: center;">{{++$j}}</td>
              <td style="border: 0.5px solid black;text-align: left;">{{$key_config}}</td>
              <td style="border: 0.5px solid black;text-align: right;">IDR {{number_format($datas_config['total_nominal'])}}</td>
            </tr>
              @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="2" style="text-align:right;">Grand Total Cost</th>
              <th style="text-align:right;">IDR {{number_format($data_config->detail_config_nominal)}}</th>
            </tr>
          </tfoot>
          @endforeach
        </table>
      @endif
    @endforeach
      <table class="table-bordered" style="width: 100%;">
        @if($keyConf == "Implementation")
          <tr style="background-color:#789de5">
        @elseif($keyConf == "Maintenance")
          <tr style="background-color:#ea3323">
        @else
          <tr style="background-color:#f19e38">
        @endif
            <th style="width:20px">No</td>
            <th>Scope of Work</th>
          </tr>
          <tr>
            <td>{{++$h}}</td>
            <td>{!!nl2br($data_configs->sow)!!}</td>
          </tr>
        @if($keyConf == "Implementation")
          <tr style="background-color:#789de5">
        @elseif($keyConf == "Maintenance")
          <tr style="background-color:#ea3323">
        @else
          <tr style="background-color:#f19e38">
        @endif
          <th width="5%">No</th>
          <th>Out of Scope</th>
        </tr>
        <tr>
          <td>{{++$h}}</td>
          <td>{!!nl2br($data_configs->oos)!!}</td>
        </tr>
      </table>
  </div>
  @endforeach
            
<!-- <img src="img/footer.PNG" style="width:800px;height: 130px; A_CSS_ATTRIBUTE:all;position: absolute"> -->
</body>
</html>