<!DOCTYPE html>
<html lang="en">
<!--  -->

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/png" href="../img/logopng.png">
  <title>SIP - Sales App</title>
  <!-- Bootstrap core CSS-->
  <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="{{asset('vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="{{asset('vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{asset('css/sb-admin.css')}}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-slider.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/select2.css')}}">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel='stylesheet' href="{{asset('css/fullcalendar.css')}}" />
  <link href="{{asset('css/fullcalendar.min.css')}}" rel='stylesheet' />
  <link href="{{asset('css/fixedColumns.dataTables.min.css')}}" rel='stylesheet' />
  <link href="{{asset('css/fixedColumns.bootstrap.min.css')}}" rel='stylesheet' />
  <link href="{{asset('css/fullcalendar.print.min.css')}}" rel='stylesheet' media='print' />
  <style type="text/css">
    table.dataTable th,
    table.dataTable td {
      font-size: 12px;
    }
    div div ol li{
      font-size: 14px;
    }
    div.card-header{
      font-size: 14px;
    }
    button.btn{
      font-size: 12px;
    }
    div.dropdown-menu{
      font-size: 12px;
    }
    div.modal{
      font-size: 14px;
    }
    div.modal.form-control{
      font-size: 12px;
    }
    input[type="text"]
    {
      font-size:14px;
    }
  </style>
   
</head>

<body class="fixed-nav sticky-footer" id="page-top">
    @show
    @section('header')
    @include('template.header')
    @show
  <!--content-->
    <div class="">
        @yield('content')
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    
    <script src="{{asset('js/bootstrap-slider.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/jquery/jquery.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.js')}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin.min.js')}}"></script>
    <!-- Custom scripts for this page-->
    <script src="{{asset('js/sb-admin-datatables.min.js')}}"></script>
    <script src="{{asset('js/sb-admin-charts.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>
    <script src="{{asset('js/style.js')}}"></script>
    <script src="{{asset('js/ajaxscript.js')}}"></script>
    <!-- <script src="{{asset('js/jquery.samask-masker.js')}}"></script> -->
    <script src="{{asset('js/sb-admin-charts.min.js')}}"></script>
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>
    <!-- <script src="{{asset('js/jquery.mask.min.js')}}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="{{asset('js/dataTables.fixedColumns.min.js')}}"></script>

    <script type="text/javascript">
      $(function(){
        $(".activeable_group").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')

        $(".activeable_menu").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')
      })
    </script>
    
    @yield('script')
  </div>
</body>
</html>
