@extends('template.template')
@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Product</a>
        </li>
      </ol>
    
      @if (session('update'))
    <div class="alert alert-warning" id="alert">
        {{ session('update') }}
    </div>
      @endif

      @if (session('success'))
    <div class="alert alert-primary" id="alert">
        {{ session('success') }}
    </div>
      @endif

      @if (session('alert'))
    <div class="alert alert-success" id="alert">
        {{ session('alert') }}
    </div>
      @endif
      <div class="card mb-3">
        <div class="card-header">
           <i class="fa fa-table"></i>&nbsp<b>Master Inventory Table</b>
        </div>

        <div class="card-body" style="margin-left: 8px;margin-right: 10px;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;">
            <div class="col-md-6">
              <div class="">
                 <button class="btn btn-sm btn-primary margin-bottom pull-right" id="" data-target="#modal_kategori" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Category</button><br>
              </div><hr style="border: 1px solid #6c757d">
              <div class="table-responsive">
                
                <table class="table table-bordered nowrap" id="tb_kategori" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Name Category</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $i = 1?>
                  @foreach($category as $data)
                  <tr>
                    <td>
                      {{$i++}}
                    </td>
                    <td>
                      {{$data->category}}
                    </td>
                    <td>
                      <button class="btn btn-warning btn-sm" data-target="#modal_edit_category" data-toggle="modal" onclick="category('{{$data->id_category}}','{{$data->category}}')"><i class="fa fa-pencil"></i>&nbspEdit</button>
                      <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbspDelete</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
                </table>
              </div>
            </div>

            <div class="col-md-6">
              <div class="">
                 <button class="btn btn-sm btn-primary margin-bottom pull-right" id="" data-target="#modal_tipe" data-toggle="modal"><i class="fa fa-plus"> </i>&nbsp Type</button><br>
              </div><hr style="border: 1px solid #6c757d">
              <div class="table-responsive">
                
                <table class="table table-bordered nowrap" id="tb_tipe" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Type</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="products-list" name="products-list">
                  <?php $i = 1?>
                  @foreach($type as $data)
                  <tr>
                    <td>
                      {{$i++}}
                    </td>
                    <td>
                      {{$data->type}}
                    </td>
                    <td>
                      <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal_edit_type" onclick="tipe('{{$data->id_type}}','{{$data->type}}')"><i class="fa fa-pencil"></i>&nbspEdit</button>
                      <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbspDelete</button>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
                </table>
              </div>
            </div>
      </div>
  </div>
  
</div>

  <!--Modal Add Kategori-->
<div class="modal fade" id="modal_kategori" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('store_category')}}" id="modal_kategori" name="modal_kategori">
            @csrf
          <div class="form-group">
            <label for="">Category</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="category" id="category" required>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Add Tipe-->
<div class="modal fade" id="modal_tipe" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title">Add Type</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('/store_type')}}" id="modal_tipe" name="modal_tipe">
            @csrf
          <div class="form-group">
            <label for="">Type</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="type" id="type" required>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Edit-->
<div class="modal fade" id="modal_edit_category" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Category</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_category')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" name="id_category_edit" id="id_category_edit" hidden>
      
          <div class="form-group">
            <label for="">Category</label>
            <input type="text" class="form-control" placeholder="Enter Category" name="category_edit" id="category_edit">
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<!--Modal Edit-->
<div class="modal fade" id="modal_edit_type" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content modal-md">
        <div class="modal-header">
          <h4 class="modal-title">Edit Type</h4>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{url('update_type')}}" id="modaledit" name="modaledit">
            @csrf
          <input type="text" class="form-control" name="id_type_edit" id="id_type_edit" hidden>
      
          <div class="form-group">
            <label for="">Type</label>
            <input type="text" class="form-control" placeholder="Enter Type" name="type_edit" id="type_edit">
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class=" fa fa-times"></i>&nbspClose</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-check"> </i>&nbspSubmit</button>
            </div>
        </form>
        </div>
      </div>
    </div>
</div>

<style type="text/css">
   .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }

</style>

@endsection

@section('script')
  <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/select2.min.js')}}"></script>
  <script type="text/javascript">
    function category(id_category,category) {
      $('#id_category_edit').val(id_category);
      $('#category_edit').val(category);
    }

    function tipe(id_type,type) {
      $('#id_type_edit').val(id_type);
      $('#type_edit').val(type);
    }

    $("#alert").fadeTo(2000, 500).slideUp(500, function(){
         $("#alert").slideUp(300);
    });

    $('#tb_kategori').DataTable( {
     "scrollX": true,
     "columnDefs": [
        { "width": "20%", "targets": 2 }
      ]
    })

    $('#tb_tipe').DataTable( {
     "scrollX": true,
     "columnDefs": [
        { "width": "20%", "targets": 2 }
      ]
    })
  </script>
@endsection