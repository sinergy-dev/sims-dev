@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">

@endsection

@section('content')
<section class="content-header">
    <h1>
        Create
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Project</li>
    </ol>
</section>

<div class="content">
    <form action="{{url('/project-references/store')}}" method="post" enctype="multipart/form-data"
        style="background: white; padding: 10px;">
        @csrf
        <label for="">Project Name</label>
        <input type="text" name="name" class="form-control">

        <label for="">Image</label>
        <input type="file" name="image" class="form-control">

        <label for="">Type</label>
        <select name="type" id="" class="form-control">
            <option value="1">FSI and Banking</option>
            <option value="2">Government</option>
            <option value="3">Telco & Service</option>
            <option value="4">Service</option>
            <option value="5">Retail</option>
            <option value="6">Education</option>
        </select>

        <label for="">Product</label>
        <select name="id_product" id="id_product" class="form-control">
            @foreach($product as $row)
            <option value="{{$row->id}}">{{$row->name_tech}}</option>
            @endforeach
        </select>

        <label for="">Description</label>
        <textarea name="desc" id="" cols="30" rows="10" class="form-control"></textarea>

        <div style="text-align: end; margin-top: 10px;">
            <button class="btn btn-success">Add</button>
        </div>
    </form>
</div>
@endsection

@section('scriptImport')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js">
</script>
@endsection

@section('script')
<script>
    $('#id_product').select2({
        multiple: false,
    });
</script>
@endsection