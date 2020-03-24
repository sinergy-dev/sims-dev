@extends('template.template_admin-lte')
@section('content')
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
              <table class="table table-bordered display nowrap" id="report-territory" width="100%" cellspacing="0">
                <thead>
                  <tr>
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
                <?php $number = 1; ?>
                @foreach($territory_loop as $terr)
                <tr>
                    <th colspan="8">{{$terr->id_territory}}</th>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                    <td style="display: none;"></td>
                </tr>
                <tbody id="territory" name="territory">
                  <tr>
                    <td>
                      [Customer A] - [Sales]
                    </td>
                    <td>
                      init
                    </td>
                    <td>
                      open
                    </td>
                    <td>
                      [sd
                    </td>
                    <td>
                      tp
                    </td>
                    <td>
                      win
                    </td>
                    <td>
                      lose
                    </td>
                    <td>
                      total
                    </td>
                  </tr>
                </tbody>
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
    $('#report-territory').DataTable({
      "columnDefs": [
        { "width": "10%", "targets": 1 }
        { "width": "10%", "targets": 2 }
        { "width": "10%", "targets": 3 }
        { "width": "10%", "targets": 4 }
        { "width": "10%", "targets": 5 }
        { "width": "10%", "targets": 6 }
        { "width": "10%", "targets": 7 }
      ]
    });

    
  </script>
@endsection