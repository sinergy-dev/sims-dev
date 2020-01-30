@extends('template.template')
@section('content')

<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">General Affair</a>
        </li>
      </ol>


<div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i> <b>List Item</b>
           <button type="button" class="btn btn-success-sales pull-right" data-target="#modalGA" data-toggle="modal"><i class="fa fa-plus"> </i> &nbspItem</button>
      </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="datatableq" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID Item</th>
                  <th>Item Name</th>
                  <th>Quantity</th>
                  <th>Information</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($datas as $data)
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <th>
                  	<a><button class="btn btn-sm btn-primary fa fa-pencil fa-lg" data-target="#modalEditItem" data-toggle="modal" style="width: 40px;height: 40px;text-align: center;" onclick="barang('{{$data->item_name}}','{{$data->quantity}}','{{$data->info}}')">
                    </button></a>

                    <a href="{{ url('delete', $data->id_item) }}"><button class="btn btn-sm btn-danger fa fa-trash fa-lg" style="width: 40px;height: 40px;text-align: center;" onclick="return confirm('Are you sure want to delete this data?')">
                    </button></a>
                  </th>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Sinergy Informasi Pratama © 2018</div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <!-- <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Sinergy Informasi Pratama © 2018</small>
        </div>
      </div>
    </footer> -->



    <!--MODAL ADD-->  
    <div class="modal fade" id="modalGA" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Add Item</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_barang')}}" id="modalAddItem" name="modalAddItem">
            @csrf
            <div class="form-group">
                <label>ID Item</label>
                <input class="form-control" id="id_item" name="id_item" required>
            </div>

            <div class="form-group">
                <label>Item Name</label>
                <input class="form-control" id="nama_item" name="nama_item" required>
            </div>
            <div class="form-group">
                <label>Quantity</label>
                <input class="form-control" id="quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label>Information</label>
                <input class="form-control" id="info" name="info" required>
            </div>        
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>


<!--MODAL EDIT-->  
    <div class="modal fade" id="modalEditItem" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Item</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/barang/update')}}" id="modalEditItem" name="modalEditItem">
            @csrf
          <!-- <div class="form-group">
            <label for="lead_id">Lead Id</label>
            <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder="Lead Id" readonly required>
          </div> -->
          	<div class="form-group">
                <label>ID Item</label>
                <input class="form-control" id="edit_id_item" name="id_item">
            </div>

            <div class="form-group">
                <label>Item Name</label>
                <input class="form-control" id="edit_nama_item" name="nama_item">
            </div>
            
            <div class="form-group">
                <label>Quantity</label>
                <input class="form-control" id="edit_quantity" name="quantity" required>
            </div>

            <div class="form-group">
                <label>Information</label>
                <input class="form-control" id="edit_info" name="info" required>
            </div>      
             
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
             <!--  <button type="submit" class="btn btn-primary" id="btn-save" value="add"  data-dismiss="modal" >Submit</button>
              <input type="hidden" id="lead_id" name="lead_id" value="0"> -->
              <button type="submit" class="btn btn-success"><i class="fa fa-check"> </i>&nbspUpdate</button>
              <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspUpdate</button> -->
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

@endsection