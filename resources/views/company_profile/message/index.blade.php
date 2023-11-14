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
        Message
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
        <li class="active">Message</li>
    </ol>
</section>
<div class="content">
    <div class="table table-responsive" style="background: white; padding: 10px;">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        For
                    </td>
                    <td>
                        Business
                    </td>
                    <td>
                        Email
                    </td>
                    <td class="text-center">
                        Action
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{$row->name}}</td>
                    <td>{{$row->for}}</td>
                    <td>{{$row->business}}</td>
                    <td>{{$row->email}}</td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a onClick="showDetail({{$row->id}})" class="btn btn-sm btn-warning text-white"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">Detail</a>
                        <form action="{{url('/message/delete', $row->id)}}" method="post">
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

<div class="modal fade" id="messageEdit" tabindex="-1" aria-labelledby="messageEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <section class="content-header">
                    <h1>
                        Message
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="/"><i class="fa fa-firefox"></i>CP Website</a></li>
                        <li class="">Message</li>
                        <li class="active">Detail</li>
                    </ol>
                </section>

                <div class="content" style="padding-top: 10px; display:flex; justify-content: center;">
                    <div class="table table-responsive"
                        style="background: white; padding: 10px; max-width: 700px; box-shadow: 0 0 5px 0 grey; font-size: 15px;">
                        <p id="from"></p>
                        <p id="email"></p>
                        <p id="phone"></p>
                        <p id="department"></p>
                        <p id="for"></p>
                        <p id="company"></p>
                        <p id="request" style="text-align: justify;"></p>
                    </div>
                </div>
            </div>
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

    function showDetail(id) {
        $("#messageEdit").modal("show");

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/message/detail/" + id,
            success: function (response) {
                $("#from").text( "From : " + response.name)
                $("#for").text( "For : " + response.for )
                $("#company").text( "Company : " + response.business )
                $("#email").text( "Email : " + response.email )
                $("#phone").text( "Phone : 0" + response.phone )
                $("#request").text( "Request : " + response.request )
                $("#department").text( "Department : " + response.department )

                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log("Kesalahan: " + error);
            }
        });
    }

    $('#myTable').DataTable()
</script>
@endsection