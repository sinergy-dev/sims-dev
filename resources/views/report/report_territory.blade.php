@extends('template.template_admin-lte')
@section('content')
<style type="text/css">
  .dataTables_filter {
    display: none;
  }

  .header th:first-child{
    background-color: #dddddd;
  }

  .header th:nth-child(2){
    color: white;
    background-color: #7735a3;
  }

  .header th:nth-child(3){
    color: white;
    background-color: #f2562b;
  }

  .header th:nth-child(4){
    color: white;
    background-color: #04dda3;
  }

  .header th:nth-child(5){
    color: white;
    background-color: #f7e127;
  }

  .header th:nth-child(6){
    color: white;
    background-color: #246d18;
  }

  .header th:nth-child(7){
    color: white;
    background-color: #e5140d;
  }

  .header-child th{
    background-color: #f5f3ed;
  }

  
</style>
  <section class="content-header">
    <h1>
      Report Customer
    </h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Report</li>
      <li class="active">Report Customer</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i>Report Customer By Territory</i></h3>
          </div>
          <div class="box-body">
             <div class="table-responsive">
                <table class="table table-bordered display nowrap" id="report_territory" width="100%" cellspacing="0">
                    <thead>
                      <tr class="header">
                        <th>Territory-Customer</th>
                        <th>INITIAL</th>
                        <th>OPEN</th>
                        <th>SD</th>
                        <th>TP</th>
                        <th>WIN</th>
                        <th>LOSE</th>
                        <th>TOTAL</th>
                      </tr>
                    </thead>
                    @foreach($territory_loop as $terr)
                      <tr class="header-child">
                        <th colspan="8">{{$terr->id_territory}}</th>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                        <td style="display: none"></td>
                      </tr>
                      @foreach($datas as $data)
                          @if($data->id_territory == $terr->id_territory)
                          <tbody id="territory" name="territory">
                            <tr>
                              <td>
                                [{{$data->brand_name}}] - [{{$data->name}}]
                              </td>
                              <td>
                                {{$data->INITIAL}}
                              </td>
                              <td>
                                {{$data->OPEN}}
                              </td>
                              <td>
                                {{$data->SD}}
                              </td>
                              <td>
                                {{$data->TP}}
                              </td>
                              <td>
                                {{$data->WIN}}
                              </td>
                              <td>
                                {{$data->LOSE}}
                              </td>
                              <td>
                                {{$data->All}}
                              </td>
                            </tr>
                            
                          </tbody>
                      @endif
                        @endforeach
                    @endforeach
                </table>
              
            </div>  
          </div>
        </div>  
      </div>
    </div>
  </section>
@endsection
@section('script')
  <script type="text/javascript">
    $('#report_territory').DataTable({
      "bLengthChange": false,
      // "ordering":false,
      "pageLength": 20,
      "columnDefs": [
        { "width": "10%", "targets": 1,
          "width": "10%", "targets": 2,
          "width": "10%", "targets": 3,
          "width": "10%", "targets": 4,
          "width": "10%", "targets": 5,
          "width": "10%", "targets": 6,
          "width": "10%", "targets": 7

        }
      ]
    });

    // $('#ter_2').DataTable({
    //   "bLengthChange": false,
    //   // "ordering":false,
    //   "pageLength": 20,
    //   "columnDefs": [
    //     { "width": "10%", "targets": 1,
    //       "width": "10%", "targets": 2,
    //       "width": "10%", "targets": 3,
    //       "width": "10%", "targets": 4,
    //       "width": "10%", "targets": 5,
    //       "width": "10%", "targets": 6,
    //       "width": "10%", "targets": 7

    //     }
    //   ]
    // });

    
  </script>
@endsection