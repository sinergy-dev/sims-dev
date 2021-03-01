@extends('template.template_admin-lte')
@section('content')
<section class="content-header">
  <h1>Kebijakan & Peraturan</h1>
    <ol class="breadcrumb">
      <li><a href="/"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
</section>

<section class="content">
	<div class="box">
	    <div class="box-header with-border">
	      <div class="pull-right">
	          <a href="{{url('/add_bgaransi')}}">
	            <button class="btn btn-sm btn-success pull-right float-right margin-left-custom" id="AddGuide"><i class="fa fa-plus"> </i>&nbsp Guide</button>
	          </a>
	      </div>
	    </div>

	    <div class="box-body">
		      <div class="table-responsive">
		        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
		          <thead>
		            <tr>
		            <th>Kebijakan & peraturan</th>
		            <th width="40%">Source of Information</th>
		            <th width="20%">Action</th>
		            </tr>
		          </thead>
		          <tbody>
		          	<tr>
		          		<td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		          		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
		          		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
		          		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
		          		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
		          		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</td>
		          		<td><a><i>lorem ipsum</i></a></td>
		          		<td></td>
		          	</tr>
		          </tbody>
		          <tfoot>
		          </tfoot>
		        </table>
		      </div>
	  	</div>
    </div>
	</div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="AddGuideModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Guide Line</h5>
      </div>
      <div class="modal-body" id="">
      	<div class="form-group">
      		<textarea class="form-control" id="description" name="description"></textarea>
      	</div>
      	<div class="form-group">
      		<textarea class="form-control" id="link" name="link"></textarea>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitGuide">Submit</button>
      </div>
    </div>
  </div>
</div>
@endsection
