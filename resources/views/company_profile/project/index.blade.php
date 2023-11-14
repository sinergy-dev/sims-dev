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
        Project Reference
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Project</li>
    </ol>
</section>

<div class="content">
    <div class="table table-responsive"  style="padding: 10px; background: white;">
        <div style="margin-bottom: 10px; text-align: end;">
            <a href="/project-references/create" class="btn btn-success">Add</a>
        </div>
        <table class="table table-stripped" id="myTable">
            <thead>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        Image
                    </td>
                    <td>
                        Product
                    </td>
                    <td>
                        Type
                    </td>
                    <td style="text-align: center;">
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
                        <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                            data-bs-target="#imageModal" onClick="image('{{$row->image}}')">See Image</button>
                    </td>
                    <td>
                        {{$row->partner_section->name_tech}}
                    </td>
                    <td>
                        @if($row->type == 1)
                        FSI and Banking
                        @elseif($row->type == 2)
                        Government
                        @elseif($row->type == 3)
                        Manufacturing
                        @elseif($row->type == 4)
                        Telco & Service Provider
                        @elseif($row->type == 5)
                        Retail
                        @elseif($row->type == 6)
                        Education
                        @endif
                    </td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a href="{{url('/project-references/edit', $row->id)}}"
                            class="btn btn-warning btn-sm text-white">Edit</a>
                        <form action="{{url('/project-references/destroy', $row->id)}}" method="post">
                            @csrf
                            {{method_field('delete')}}
                            <button class="btn btn-danger btn-sm remove-data">Delete</button>
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
@endsection

@section('script')
<script>
    $('#myTable').DataTable()

    function image(name) {
        document.querySelector('#img_modal').setAttribute('src', '/storage/images/project/' + name)
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

</script>
@endsection