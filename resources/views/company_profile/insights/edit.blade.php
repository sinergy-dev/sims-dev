@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

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
        <li class="active">Edit</li>
    </ol>
</section>

<div class="content">
    <form action="{{url('/insights/update', $data->id)}}" method="post"
        enctype="multipart/form-data" style="background: white; padding: 10px;">
        @csrf
        {{method_field('put')}}
        <div class="mt-2">
            <label for="">Title</label>
            <input type="text" name="title" class="form-control" value="{{$data->title}}">
        </div>
        <div class="mt-2">
            <label for="">Change image</label>
            <div>
                <img src="{{asset('storage/images/insights/' .$data->image)}}" alt="" class="img-fluid"
                    style="width: 200px; margin: 10px 0px;">
            </div>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mt-2">
            <label for="">Type</label>
            <select name="type" id="type_insights" onChange="changeType()" class="form-control">
                <option value="{{$data->type}}">{{$data->type}} --current</option>
                <option value="Blog">Blog</option>
                <option value="News">News</option>
            </select>
        </div>
        <div class="mt-2" id="blog_about">
            <label for="">About Product</label>
            <select name="id_product[]" id="about_select" class="" multiple="multiple" style="width: 100%;">
                @foreach($product as $row)
                <option value="{{$row->id}}" @if(in_array($row->id, array_column($tag2, 'id_product'))) selected
                    @endif> {{$row->name_tech}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mt-2" id="news_tag">
            <label for="">Tag</label>
            <select name="tag_name[]" id="tag_select" class="" multiple="multiple" style="width: 100%;">
                @foreach($tag as $row)
                <option value="{{$row->tag_name}}" @if(in_array($row->id, array_column($connector, 'id_tag'))) selected
                    @endif> {{$row->tag_name}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mt-2">
            <label for="">Text</label>
            <textarea name="paragraph" id="editor" rows="5" class="form-control">{{$data->paragraph}}</textarea>
        </div>
        <div class="mt-2 text-end">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
</div>
@endsection

@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/39.0.1/ckeditor.min.js"
    integrity="sha512-sDgY/8SxQ20z1Cs30yhX32FwGhC1A4sJJYs7kwa2EnvCeepR/S1NbdXNLd6TDJC0J5cV34ObeQIYekYRK8nJkQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
            console.error(error);
        });


    changeType()

    function changeType() {
        if (document.querySelector('#type_insights').value == 'Blog') {
            document.querySelector('#blog_about').style.display = 'block';
            document.querySelector('#news_tag').style.display = 'none';

        } else if (document.querySelector('#type_insights').value == 'News') {

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