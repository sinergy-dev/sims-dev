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
        Edit
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Project</li>
    </ol>
</section>

<div class="content">
    <form action="{{url('/project-references/update', $data->id)}}" method="post" enctype="multipart/form-data" style="background: white; padding: 10px;">
        @csrf
        {{method_field('PUT')}}
        <label for="">Project Name</label>
        <input type="text" name="name" class="form-control" value="{{$data->name}}">

        <label for="">Image</label>
        <div>
            <img alt="" src="{{asset('storage/images/project/' .$data->image)}}" class="img-thumbnail col-md-6" id="img_edit" style="height: 100px; width: auto; object-fit: cover;">
        </div>
        <input type="file" name="image" class="form-control">

        <label for="">Type</label>
        <select name="type" id="" class="form-control">
            <option value="{{$data->type}}" selected>
                @if($data->type == 1)
                FSI and Banking
                @elseif($data->type == 2)
                Government
                @elseif($data->type == 3)
                Manufacturing
                @elseif($data->type == 4)
                Telco & Service Provider
                @elseif($data->type == 5)
                Retail
                @elseif($data->type == 6)
                Education
                @endif
                --current
            </option>
            <option value="1">FSI and Banking</option>
            <option value="2">Government</option>
            <option value="3">Telco & Service</option>
            <option value="4">Service</option>
            <option value="5">Retail</option>
            <option value="6">Education</option>
        </select>

        <label for="">Product</label>
        <select name="id_product" id="id_product" class="form-control">
            <option value="{{$data->id_product}}" selected>{{$data->partner_section->name_tech}} --current</option>
            @foreach($product as $row)
            <option value="{{$row->id}}">{{$row->name_tech}}</option>
            @endforeach
        </select>

        <label for="">Description</label>
        <textarea name="desc" id="" cols="30" rows="10" class="form-control">{{$data->desc}}</textarea>

        <div style="text-align: end; margin-top: 10px;">
            <button class="btn btn-success">Save</button>
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