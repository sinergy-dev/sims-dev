@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<style>
    .mt-2 {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Campaign
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="">Campaign</li>
        <li class="active">Create</li>
    </ol>
</section>

<div class="content">
    <form action="{{url('/campaign/store')}}" method="post" style="padding: 10px; background: white;"
        enctype="multipart/form-data">
        @csrf
        <div class="mt-2">
            <label for="">Title</label>
            <input required type="text" name="title" class="form-control">
        </div>
        <div class="mt-2">
            <label for="">Choose image</label>
            <input required type="file" name="image" class="form-control">
        </div>
        <div class="mt-2">
            <label for="">Text</label>
            <textarea name="paragraph" id="editor" rows="5" class="form-control"></textarea>
        </div>
        <div class="mt-2 text-end">
            <button type="submit" class="btn btn-success">Add</button>
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
