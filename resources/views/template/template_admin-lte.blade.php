<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIP - SIMSApp</title>
  <link rel="icon" type="image/png" href="../img/siplogooke.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('/template2/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{asset('/template2/dist/css/AdminLTE.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/select2/dist/css/select2.min.css')}}">
  <!-- Fixed Column -->
  <link href="{{asset('css/fixedColumns.dataTables.min.css')}}" rel='stylesheet' />
  <link href="{{asset('css/fixedColumns.bootstrap.min.css')}}" rel='stylesheet' />

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Morris charts -->
  <link rel="stylesheet" href="{{asset('template2/bower_components/morris.js/morris.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('template2/dist/css/AdminLTE.min.css')}}">
  {{-- Dimatikan sementara sampai di gunakan lagi --}}
  {{-- <link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet"> --}}
  <!-- <link rel="stylesheet" type="text/css" href="codebase/dhtmlxgantt.css"> -->
  <!--Swal-->
  <script src="{{asset('js/sweetalert2.min.js')}}"></script>
  <script src="{{asset('js/sweetalert2.js')}}"></script>
  <script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
  <!-- <script src="http://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script> -->
  <!-- <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script> -->
  {{-- Dimatikan sementara sampai di gunakan lagi --}}
  {{-- <script src="https://cdn.dhtmlx.com/gantt/edge/sources/dhtmlxgantt.js"></script> --}}
  
  <!-- <script src="codebase/dhtmlxgantt.js"></script> -->
  {{-- Dimatikan sementara sampai di gunakan lagi --}}
  {{-- <script src="https://export.dhtmlx.com/gantt/api.js"></script> --}}

  <!-- upload gambar -->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="{{asset('js/materialize.min.js')}}"></script>
  <script type="text/javascript">
    (function($){
      $(function(){
        // $('.button-collapse').sideNav();
      });
    })(jQuery);
  </script>

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('template2/dist/css/skins/_all-skins.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    .loader {
      border: 16px solid #f3f3f3;
      border-radius: 50%;
      border-top: 16px solid blue;
      border-right: 16px solid green;
      border-bottom: 16px solid red;
      border-left: 16px solid pink;
      width: 120px;
      height: 120px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .dropbtn {
      background-color: #4CAF50;
      color: white;
      font-size: 12px;
      border: none;
      width: 140px;
      height: 30px;
      border-radius: 5px;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 140px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      z-index: 1;
    }


    .dropdown-content .year:hover {background-color: #ddd;}

    .dropdown:hover .dropdown-content {display: block;}

    .dropdown:hover .dropbtn {background-color: #3e8e41;}

    .transparant-filter{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
    }

    .transparant{
      background-color: Transparent;
      background-repeat:no-repeat;
      border: none;
      cursor:pointer;
      overflow: hidden;
      outline:none;
      width: 25px;
    }
    .alert-box {
        color:#555;
        border-radius:10px;
        font-family:Tahoma,Geneva,Arial,sans-serif;font-size:14px;
        padding:10px 36px;
        margin:10px;
    }
    .alert-box span {
        font-weight:bold;
        text-transform:uppercase;
    }
    .error {
        background:#ffecec;
        border:1px solid #f5aca6;
    }
    .success {
        background:#e9ffd9 ;
        border:1px solid #a6ca8a;
    }
    .warning {
        background:#fff8c4 ;
        border:1px solid #f2c779;
    }
    .notice {
        background:#e3f7fc;
        border:1px solid #8ed9f6;
    }
    div div ol li a{
      font-size: 14px;
    }

    div div i{
      font-size: 14px;
    }

      color:#fff;
        background-color:dodgerBlue;
      }

     .inputWithIconn.inputIconBg i{
        background-color:#aaa;
        color:#fff;
        padding:7px 4px;
        border-radius:4px 0 0 4px;
      }

     .inputWithIconn{
        position:relative;
      }

      .inputWithIconn i{
        position:absolute;
        left:0;
        top:28px;
        padding:9px 8px;
        color:#aaa;
        transition:.3s;
      }

      .inputWithIconn input[type=text]{
        padding-left:40px;
      }
      label.status-lose:hover{
        border-radius: 10%;
        background-color: grey;
        text-align: center;
        width: 75px;
        height: 30px;
        color: white;
        padding-top: 3px;
        cursor: zoom-in;
      }
      table.center{
        text-align: center;
      }

      .stats_item_number {
        white-space: nowrap;
        font-size: 2.25rem;
        line-height: 2.5rem;
        
        &:before {
          display: none;
        }
      }

      .txt_success {
        color: #2EAB6F;
      }

      .txt_warn {
        color: #f2562b;
      }

      .txt_sd {
        color: #04dda3;
      }

      .txt_tp{
        color: #f7e127;
      }

      .txt_win{
        color: #246d18;
      }

      .txt_lose{
        color: #e5140d;
      }

      .txt_smaller {
        font-size: .75em;
      }

      .flipY {
        transform: scaleY(-1);
        border-bottom-color: #fff;
      }

      .txt_faded {
        opacity: .65;
      }

      .txt_primary{
        color: #007bff;
      }

      .card {
        position: relative;
        margin-bottom: 24px;
        background-color: #fff;
        -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
      }
  </style>
  @yield('head_css')

</head>
  @if(isset($sidebar_collapse))
  <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
  @else
  <body class="hold-transition skin-blue sidebar-mini">
  @endif

<!-- ./wrapper -->
<div class="wrapper">

  @show
  @section('header')
  @include('template.header_admin-lte')
  @show

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  @show
  @section('footer')
  @include('template.footer_admin-lte')
  @show

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{asset('template2/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<!-- <script src="{{asset('template2/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script> -->
<!-- FastClick -->
<script src="{{asset('template2/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('template2/dist/js/adminlte.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('template2/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap  -->
<script src="{{asset('template2/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('template2/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('template2/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('template2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('template2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('template2/bower_components/chart.js/Chart.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) --><!-- 
<script src="{{asset('template2/dist/js/pages/dashboard2.js')}}"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="{{asset('template2/dist/js/demo.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('template2/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
@yield('scriptImport')
<!-- DataTables -->
<script>
  $(function () {
    $(".activeable").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')

    $(".activeable2").has('a[href="' + location.protocol + '//' + location.host + location.pathname + '"]').addClass('active')

    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

@yield('script')

</body>
</html>
