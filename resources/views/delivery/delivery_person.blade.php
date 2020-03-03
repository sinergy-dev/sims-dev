@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
    Delivery Person Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Delivery Person</li>
    <li class="active">SIP</li>
  </ol>
</section>

<section class="content">
	<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-table"></i>&nbsp<b>Delivery Person & Messenger</b></h3>

      </div>

      <div class="box-body">
      	<div class="table-responsive">
           <table class="table table-bordered table-striped dataTable" id="data_all" width="100%" cellspacing="0">
           	<thead>
           		<tr>
           			<td>No</td>
           			<td>Messenger Person Name</td>
           			<td>Status</td>
           			<td>Activity</td>
           			<td>Date & Time Activity</td>
           		</tr>
           	</thead>
           	<tbody></tbody>
           </table>
        </div>
      </div>
    </div>
</section>


@endsection