@extends('template.main')
@section('tittle')
Insights
@endsection
@section('head_css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css">

@endsection

@section('content')
<section class="content-header">
    <h1>
        Register
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Register</li>
    </ol>
</section>

<div class="content">
    <div class="table table-responsive bg-white p-4 rounded" style="padding: 10px; background: white;">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        Email
                    </td>
                    <td>
                        Position
                    </td>
                    <td>
                        Status
                    </td>
                    <td class="text-center">
                        Action
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>
                        {{$row->name}}
                    </td>
                    <td>
                        {{$row->email}}
                    </td>
                    <td>
                        {{$row->career->position}}
                    </td>
                    <td>
                        {{$row->career->status}}
                    </td>
                    <td class="d-flex gap-2 justify-content-center">
                        <form action="{{url('/career/register', $row->id)}}" method="post">
                            @csrf
                            {{method_field('delete')}}
                            <a href="{{url('https://new.sinergy.co.id/storage/file/cv/') . $row->cv}}" target="_blank" class="btn btn-warning btn-sm text-white">See CV</a>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scriptImport')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
<script>
        $('#myTable').DataTable()
</script>
@endsection