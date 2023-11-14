@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
<style>
    .mt-2 {
        margin-top: 15px;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
        Insights
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="">Insights</li>
        <li class="active">Create</li>
    </ol>
</section>

<div class="content">
    <form action="{{url('/insights/store')}}" method="post" class="bg-white p-3 rounded"
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
            <label for="">Type</label>
            <select name="type" id="type_insights" onChange="changeType()" class="form-control">
                <option value="">Choose---</option>
                <option value="blog">Blog</option>
                <option value="news">News</option>
            </select>
        </div>
        <div class="mt-2" id="blog_about">
            <label for="">About Product</label>
            <select name="id_product[]" id="about_select" class="" multiple="multiple" style="width: 100%;">
                @foreach($product as $row)
                <option value="{{$row->id}}">{{$row->name_tech}}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-2" id="news_tag">
            <label for="">Tag</label>
            <select name="tag_name[]" id="tag_select" class="" multiple="multiple" style="width: 100%;">
                @foreach($tag as $row)
                <option value="{{$row->tag_name}}">{{$row->tag_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mt-2">
            <label for="">Text</label>
            <textarea name="paragraph" id="editor" rows="5" class="form-control"></textarea>
        </div>
        <div class="mt-2" style="text-align: end;">
            <button type="submit" class="btn btn-success">Add</button>
        </div>
    </form>
</div>
@endsection

@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/39.0.1/ckeditor.min.js"
    integrity="sha512-sDgY/8SxQ20z1Cs30yhX32FwGhC1A4sJJYs7kwa2EnvCeepR/S1NbdXNLd6TDJC0J5cV34ObeQIYekYRK8nJkQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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


    document.querySelector('#blog_about').style.display = 'none';
    document.querySelector('#news_tag').style.display = 'none';

    function changeType() {
        if (document.querySelector('#type_insights').value == 'blog') {

            document.querySelector('#blog_about').style.display = 'block';
            document.querySelector('#news_tag').style.display = 'none';

        } else if (document.querySelector('#type_insights').value == 'news') {

            document.querySelector('#blog_about').style.display = 'none';
            document.querySelector('#news_tag').style.display = 'block';

        } else {

            document.querySelector('#blog_about').style.display = 'none';
            document.querySelector('#news_tag').style.display = 'none';

        }
    }

    $('#about_select').select2({
        multiple: true,
    });

    $('#tag_select').select2({
        tags: true,
        tokenSeparators: [',', ' '], // Memisahkan opsi saat enter
        createTag: function (params) {
            var term = $.trim(params.term);
            if (term === '') {
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true // Menandai tag yang baru ditambahkan
            };
        }
    });

    $('#tag_select').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.newTag) {
            console.log("Tag baru ditambahkan:", data.text);
            // Lakukan aksi yang Anda inginkan dengan tag baru
        }
    });
</script>
@endsection