@extends('template.template_admin-lte')
@section('content')

<section class="content-header">
  <h1>
   Detail Delivery Person Management
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Delivery Person</li>
    <li class="active">SIP</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="box">
    <div class="box-body">
      <ul class="timeline timeline-inverse">
        <!-- timeline time label -->
        <li class="time-label">
              <span class="bg-red">
                7 April. 2020
              </span>
        </li>
        <!-- /.timeline-label -->
        <!-- timeline item -->
        <li>
          <i class="fa fa-bicycle bg-blue"></i>

          <div class="timeline-item">
            <span class="time" style="background-color: white;font-size: 14px"><i class="fa fa-clock-o"></i> 12:05</span>

            <h3 class="timeline-header"><a href="#">Arifin</a> On road</h3>

            <div class="timeline-body">
              Kirim invoice ke bla bla bla bla,

              dengan note :

              note
            </div>
          </div>
        </li>
        <!-- END timeline item -->
        <!-- timeline item -->
        <li>
          <i class="fa fa-comments bg-yellow"></i>

          <div class="timeline-item">
            <span class="time" style="background-color: white;font-size: 14px"><i class="fa fa-clock-o"></i> 12:35</span>

            <h3 class="timeline-header"><a href="#">Arifin</a> Write Note...</h3>

            <div class="timeline-body">
              lupa bawa berkas ini jadi agak telat
            </div>
          </div>
        </li>
        <!--finish-->
        <li>
          <i class="fa fa-check bg-green"></i>

          <div class="timeline-item">
            <span class="time" style="background-color: white;font-size: 14px"><i class="fa fa-clock-o"></i> 14:00</span>

            <h3 class="timeline-header"><a href="#">Arifin</a> Activity has been Done!</h3>

            <div class="timeline-body">
              sudah diterima oleh pak abdul
            </div>
          </div>
        </li>
        <li>
          <i class="fa fa-clock-o bg-gray"></i>
        </li>
      </ul>
    </div>  
  </div>
  <!-- /.timeline -->

</section>

@endsection