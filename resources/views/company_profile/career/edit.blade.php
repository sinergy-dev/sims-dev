@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<style>
    .mt-3 {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Career
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="">Career</li>
        <li class="active">Edit</li>
    </ol>
</section>

<div class="content">
    <form id="form_career_update" action="{{url('/career/update', $data->id)}}" method="post" style="padding: 10px; background: white;">
        @csrf
        {{method_field('put')}}
            <div class="mt-3">
                <label for="">Position</label>
                <input required type="text" name="position" class="form-control" value="{{$data->position}}">
            </div>
            <div class="mt-3">
                <label for="">Location</label>
                <input required type="text" name="location" class="form-control" value="{{$data->location}}">
            </div>
            <div class="mt-3">
                <label for="">Status</label>
                <select name="status" class="form-control">
                    <option value="{{$data->status}}">{{$data->status}} --current</option>
                    <option value="Fulltime">Fulltime</option>
                    <option value="Contract">Contract</option>
                    <option value="Internship">Internship</option>
                </select>
            </div>
            <div class="mt-3">
                <label for="">Requirement</label>
                <textarea name="desc" cols="10" id="editor" class="form-control">
                    {{$data->desc}}
                </textarea>
            </div>
            <div class="mt-3 text-end">
            <button type="submit" class="btn btn-success">Save</button>
            </div>
    </form>
</div>
@endsection

@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/39.0.1/ckeditor.min.js"
    integrity="sha512-sDgY/8SxQ20z1Cs30yhX32FwGhC1A4sJJYs7kwa2EnvCeepR/S1NbdXNLd6TDJC0J5cV34ObeQIYekYRK8nJkQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('script')
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}'
            }
        })
        .catch(error => {
            console.log(error);
        });
</script>
@endsection