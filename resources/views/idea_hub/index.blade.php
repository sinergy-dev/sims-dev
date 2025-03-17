@extends('template.main')
@section('title')
    Idea Hub
@endsection
@section('head_css')
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.css" integrity="sha512-OWGg8FcHstyYFwtjfkiCoYHW2hG3PDWwdtczPAPUcETobBJOVCouKig8rqED0NMLcT9GtE4jw6IT1CSrwY87uw==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/themes/blue/pace-theme-barber-shop.min.css" integrity="sha512-7qRUmettmzmL6BrHrw89ro5Ki8CZZQSC/eBJTlD3YPHVthueedR4hqJyYqe1FJIA4OhU2mTes0yBtiRMCIMkzw==" crossorigin="anonymous" referrerpolicy="no-referrer"  as="style" onload="this.onload=null;this.rel='stylesheet'"/>
    <style type="text/css">
        .DTFC_LeftBodyLiner {
            overflow: hidden;
        }
        th{
            text-align: center;
        }
        td>.truncate{
            /*word-wrap: break-word; */
            word-break:break-all;
            white-space: normal;
            width:200px;
        }
        @media screen and (max-width: 768px) {
            .btn-action-letter{
                float: left!important;
            }
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Idea Hub
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Idea Hub</li>
        </ol>
    </section>

    <section class="content">
        @if (session('update'))
            <div class="alert alert-warning" id="alert">
                {{ session('update') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops->quote_number}}</h4></div>
        @endif

        @if (session('sukses'))
            <div class="alert alert-success notification-bar"><span>Notice : </span> {{ session('success') }}<button type="button" class="dismisbar transparant pull-right"><i class="fa fa-times fa-lg"></i></button><br>Get your Quote Number :<h4> {{$pops2->quote_number}}</h4></div>
        @endif

        @if (session('alert'))
            <div class="alert alert-success" id="alert">
                {{ session('alert') }}
            </div>
        @endif
        <div class="row" style="margin-bottom:10px" id="filterBox">
            <div class="col-md-3" >
                <div class="form-group">
                    <label>Range Date Idea : </label>
                    <button type="button" class="btn btn-default btn-flat pull-left" style="width:100%" id="inputRangeDate">
                        <i class="fa fa-calendar"></i> Date range picker
                        <span>
                                <i class="fa fa-caret-down"></i>
                            </span>
                    </button>
                </div>
            </div>
        </div>
        @if($role == 'Chief Operating Officer' || $role == 'Chief Executive Officer' || $role == 'VP Solutions & Partnership Management'
        || $role == 'VP Sales' || $role == 'VP Internal Chain Management' || $role == 'VP Synergy System Management' || $role == 'VP Program & Project Management'
        || $role == 'VP Human Capital Management Management' || $role == 'VP Financial & Accounting')
            <div class="row">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered nowrap table-striped dataTable data" id="data_all" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Idea</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table table-bordered nowrap table-striped dataTable data" id="data_point" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Point</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <div class="tab-content">
                                        <div class="tab-pane active" role="tabpanel">
                                            <div class="table-responsive">
                                                <table class="table table-bordered nowrap table-striped dataTable data" id="data_all" width="100%" cellspacing="0">
                                                    <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name</th>
                                                        <th>Idea</th>
                                                        <th>Business Concept</th>
                                                        <th>Divisi</th>
                                                        <th>Create Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif

        <div id="ideaHub" class="modal fade" role="dialog" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content modal-style">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                        <h4 class="modal-title">Detail Idea</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Idea</label>
                                    <input type="text" class="form-control" name="idea" id="idea" disabled required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Business Concept</label>
                                    <textarea class="form-control" name="konsep_bisnis" id="konsep_bisnis" cols="30" rows="10" disabled required></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Reference</label>
                                    <textarea class="form-control" name="referensi_bisnis" id="referensi_bisnis" cols="30" rows="10" disabled required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama" disabled required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Divisi</label>
                                    <input type="text" class="form-control" name="divisi" id="divisi" disabled required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Posisi</label>
                                    <input type="text" class="form-control" name="posisi" id="posisi" disabled required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Create Date</label>
                                    <input type="datetime-local" class="form-control" name="create_date" id="create_date" disabled required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('scriptImport')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.8/sweetalert2.min.js" integrity="sha512-FbWDiO6LEOsPMMxeEvwrJPNzc0cinzzC0cB/+I2NFlfBPFlZJ3JHSYJBtdK7PhMn0VQlCY1qxflEG+rplMwGUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.4/pace.min.js" integrity="sha512-2cbsQGdowNDPcKuoBd2bCcsJky87Mv0LEtD/nunJUgk6MOYTgVMGihS/xCEghNf04DPhNiJ4DZw5BxDd1uyOdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.js" integrity="sha512-SSQo56LrrC0adA0IJk1GONb6LLfKM6+gqBTAGgWNO8DIxHiy0ARRIztRWVK6hGnrlYWOFKEbSLQuONZDtJFK0Q==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
    <script type="text/javascript">

        $('#inputRangeDate').daterangepicker({
            ranges: {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },function (start, end) {
            $('#inputRangeDate').html("")
            $('#inputRangeDate').html('<i class="fa fa-calendar"></i> <span>' + start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY') + '</span>&nbsp<i class="fa fa-caret-down"></i>');

            var startDay = start.format('YYYY-MM-DD');
            var endDay = end.format('YYYY-MM-DD');

            $("#startDateFilter").val(startDay)
            $("#endDateFilter").val(endDay)

            startDate = start.format('D MMMM YYYY');
            endDate = end.format('D MMMM YYYY');

            if (startDay != undefined && endDay != undefined) {
                searchCustom(startDay,endDay)
            }
        });

        function searchCustom(startDate,endDate){
            var  tempStartDate = 'startDate=', tempEndDate = 'endDate='


            if (startDate != undefined) {
                tempStartDate = tempStartDate + startDate
            }else{
                localStorage.removeItem("arrFilterBack")
            }

            if (endDate != undefined) {
                tempEndDate = tempEndDate + endDate
            }else{
                localStorage.removeItem("arrFilterBack")
            }

            var temp = "?" + tempStartDate + '&' + tempEndDate
            showFilterData(temp)
            // DashboardCounterFilter(temp)

            return localStorage.setItem("arrFilter", temp)
        }

        function showFilterData(temp,arrStatusBack,arrTypeBack){
            Pace.restart();
            Pace.track(function() {
                $("#data_all").DataTable().ajax.url("{{url('/idea_hub/getDataByFilter')}}" + temp).load()
                $("#data_point").DataTable().ajax.url("{{url('/idea_hub/getDataPoint')}}" + temp).load()
            })
        }

        $("#inputFilterDivision").select2({
            placeholder: " Select Division",
            // allowClear: true,
            multiple:true,
            closeOnSelect:true,
        })

        initIdeaTable();

        function initIdeaTable(temp) {
            var temp = ''
            if (temp == undefined) {
                temp = '?' + temp
            }else{
                temp = ''
            }
            // InitiateFilterParam();
            // DashboardCounter(temp)
            let roleName = "";
            $("#data_all").DataTable({
                "ajax":{
                    "type":"GET",
                    "url":"{{url('/idea_hub/getDataByFilter')}}" + temp,
                },
                @if($role == 'Chief Operating Officer' || $role == 'Chief Executive Officer' || $role == 'VP Solutions & Partnership Management'
                   || $role == 'VP Sales' || $role == 'VP Internal Chain Management' || $role == 'VP Synergy System Management' || $role == 'VP Program & Project Management'
                   || $role == 'VP Human Capital Management Management' || $role == 'VP Financial & Accounting')
                    "columns": [
                        {  "data": null,
                            "width": "5%",
                            "render": function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { "data": "name","width": "20%" },
                        { "data": "ide","width": "80px" },
                        { "width": "8%",
                            "render": function (data, type, row) {
                                return `<button style='width:70px' class="btn btn-xs btn-primary btnDetail" onclick="detailIdea(${row.id})" >Detail</button>`;
                            },
                        },
                    ],
                @else
                    "columns": [
                        {  "data": null,
                            "width": "5%",
                            "render": function (data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        { "data": "name","width": "15%" },
                        { "data": "ide","width": "20%" },
                        { "data": "konsep_bisnis","width": "30%" },
                        { "data": "divisi" },
                        { "data": "date" },
                        { "width": "8%",
                            "render": function (data, type, row) {
                                return `<button style='width:70px' class="btn btn-xs btn-primary btnDetail" onclick="detailIdea(${row.id})" >Detail</button>`;
                            },
                        },
                    ],
                @endif
                "searching": true,
                // "scrollX": true,
                // "order": [[0, "desc"]],
                "ordering": true,
                "pageLength": 20,
            });

            $("#data_point").DataTable({
                "ajax":{
                    "type":"GET",
                    "url":"{{url('/idea_hub/getDataPoint')}}" + temp,
                },
                "columns": [
                    {  "data": null,
                        "width": "5%",
                        "render": function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { "data": "name","width": "70%" },
                    { "data": "point","width": "80px" },
                ],
                "searching": true,
                // "scrollX": true,
                // "order": [[0, "desc"]],
                "ordering": true,
                "pageLength": 20,
            })
        }

        function detailIdea(id) {
            $.ajax({
                url : '{{url('/idea_hub/getDetail')}}'+'/' + id,
                type: 'GET',
                success: function (result) {
                    $('#idea').val(result.ide);
                    $('#konsep_bisnis').text(result.konsep_bisnis);
                    $('#referensi_bisnis').text(result.referensi);
                    $('#divisi').val(result.divisi);
                    $('#posisi').val(result.posisi);
                    let date = new Date(result.created_at);
                    date.setHours(date.getHours() + 7);
                    let formattedDate = date.toISOString().slice(0, 19);
                    $('#create_date').val(formattedDate);
                    // $('#create_date').val(result.created_at);
                    $('#nama').val(result.name);
                    $('#ideaHub').modal('show');
                }
            })
        }

    </script>

@endsection