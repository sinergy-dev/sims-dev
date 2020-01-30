@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Direktur</a>
        </li><!-- 
        <li class="breadcrumb-item active">Direktur</li> -->
      </ol>

      <div class="row">
		<div class="col-md-12">
			<button class="btn btn-primary padding-spacing pull-right">Tambah</button>
		</div>      	
      </div>

      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Lead Table</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Lead id</th>
                  <th>Sales name</th>
                  <th>Contact</th>
                  <th>Opty name</th>
                  <th>Closing date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Lead id</th>
                  <th>Sales name</th>
                  <th>Contact</th>
                  <th>Opty name</th>
                  <th>Closing date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                </tr>
              </tfoot>
              <tbody>
                <tr>
                  <td>Tiger Nixon</td>
                  <td>System Architect</td>
                  <td>Edinburgh</td>
                  <td>61</td>
                  <td>2011/04/25</td>
                  <td>$320,800</td>
                  <td>2011/04/25</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
      </div>

  </div>
</div>
@endsection
