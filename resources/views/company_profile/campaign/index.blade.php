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
        Capmaign
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Campaign</li>
    </ol>
</section>

<div class="content">
    <div style="text-align: end; background: white; padding: 10px;">
        <a href="{{url('/campaign/create')}}" class="btn btn-success">Add</a>
    </div>
    <div class="table table-responsive" style="background: white; padding: 10px;">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <td>
                        Title
                    </td>
                    <td>
                        Image
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
                        {{$row->title}}
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                            data-bs-target="#imageModal" onClick="image('{{$row->image}}')">See Image</button>
                    </td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a href="{{url('/campaign/edit', $row->id)}}" class="btn btn-sm btn-warning text-white">Edit</a>
                        <form action="{{url('/campaign/delete', $row->id)}}" method="post">
                            @csrf
                            {{method_field('delete')}}
                            <button class="btn btn-sm btn-danger remove-data">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="text-align: center;">
            <img id="img_modal" style="width: 80%; margin: 20px;" alt="">
        </div>
    </div>
</div>
@endsection

@section('scriptImport')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('script')
<script>
    function image(name) {
        document.querySelector('#img_modal').setAttribute('src', '/storage/images/campaign/' + name)
        $("#imageModal").modal("show");
    }

    $('.remove-data').click(function (event) {
        var form = $(this).closest("form");
        event.preventDefault();
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success m-1',
                cancelButton: 'btn btn-danger m-1'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    '',
                    'success'
                )
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    '',
                    'error'
                )
            }
        })
    });

    $('#myTable').DataTable()
</script>
@endsection