@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Presales Manager</a>
        </li>
        
      </ol>

<div class="card mb-3">
        <div class="card-header">
          <i ></i> Lead Table</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Lead id</th>
                  <th>Sales name</th>
                  <th>Contact</th>
                  <th>Opty name</th>
                  <th>Close date</th>
                  <th>Owner</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              
              <tbody>
                <tr>
                  <td>AA/BB/CC</td>
                  <td>Hendy</td>
                  <td>Bank BJB</td>
                  <td>Hendy</td>
                  <td>2011/04/25</td>
                  <td>Bank BJB</td>
                  <td>Rp. 20.000.000</td>
                  <td><div class="status-initial">Open</div></td>
                  <td><button class="btn btn-primary margin-bottom" id="btn-asign">Asign</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
  </div>
</div>
@endsection

<!--MODAL ADD PROJECT-->
<div class="modal fade" id="modalAsign" role="dialog">
    <div class="modal-dialog modal-md">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Asign To</h4>
        </div>
        <div class="modal-body">
          
          <div class="form-group">
            <label for="">Contact</label>
            <select class="form-control">
              <option value="">Option</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
  </div>