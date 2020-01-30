<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">
		    table {
    border-spacing: 0;
    width: 100%;
    }
    th {
    background: #404853;
    background: linear-gradient(#687587, #404853);
    border-left: 1px solid rgba(0, 0, 0, 0.2);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 12px;
    color: #fff;
    padding: 8px;
    text-align: left;
    text-transform: uppercase;
    }
    th:first-child {
    border-top-left-radius: 4px;
    border-left: 0;
    }
    th:last-child {
    border-top-right-radius: 4px;
    border-right: 0;
    }
    td {
    border-right: 1px solid #c6c9cc;
    border-bottom: 1px solid #c6c9cc;
    padding: 8px;
    font-size: 11px;
    }
    td:first-child {
    border-left: 1px solid #c6c9cc;
    }
    tr:first-child td {
    border-top: 0;
    }
    tr:nth-child(even) td {
    background: #e8eae9;
    }
    tr:last-child td:first-child {
    border-bottom-left-radius: 4px;
    }
    tr:last-child td:last-child {
    border-bottom-right-radius: 4px;
    }
    img {
    	width: 40px;
    	height: 40px;
    	border-radius: 100%;
    }
    .center {
    	text-align: center;
    }
	</style>
  <link rel="stylesheet" href="">
	<title>Report Cuti</title>
</head>
<body>
<h3 class="center">REPORT CUTI TAHUN {{$year}}</h3>
 <table id="pseudo-demo">
    <thead>
      <tr>
        <th>
          Nama
        </th>
        <th>
          Divisi 
        </th>
        <th>
          Tanggal Masuk Kerja
        </th>
        <th>
          Lama Bekerja
        </th>
        <th>
          Cuti Sudah diambil
        </th>
        <th>
          Sisa Cuti
        </th>
      </tr>
    </thead>
    <tbody>
    @foreach($cuti_index as $datas)
      <tr>
        <td>{{ucwords(strtolower($datas->name))}}</td>
        <td> 
          @if($datas->id_division == '-')
          WAREHOUSE
          @else
          {{$datas->id_division}}
          @endif
        </td>
        <td>{{str_replace('-', '/', $datas->date_of_entry)}}</td>
        <td>
          @if($datas->date_of_entrys > 365)
          {{ floor($datas->date_of_entrys / 365) }} Tahun {{ round($datas->date_of_entrys % 365 / 30 )}} Bulan
          @elseif($datas->date_of_entrys > 31)
          {{ floor($datas->date_of_entrys / 30)}} Bulan
          @else
          {{$datas->date_of_entrys}} Hari
          @endif
        </td>
        <td>
          @if($datas->niks < 1)
          1
          @else
          {{$datas->niks}}
          @endif
        Hari
        </td>
        <td>
          @if($datas->cuti == NULL)
          -
          @else
          {{$datas->cuti}} Hari
          @endif
        </td>
      </tr>
  @endforeach
    @foreach($cuti_list as $data)
      <tr>
        <td>{{ucwords(strtolower($data->name))}}</td>
        <td>
          @if($data->id_division == '-')
          WAREHOUSE
          @else
          {{$data->id_division}}
          @endif
        </td>
        <td>{{str_replace('-', '/', $data->date_of_entry)}}</td>
        <td>
          @if($data->date_of_entrys > 365)
          {{ floor($data->date_of_entrys / 365) }} Tahun {{ round($data->date_of_entrys % 365 / 30 )}} Bulan
          @elseif($data->date_of_entrys > 31)
          {{ floor($data->date_of_entrys / 30)}} Bulan
          @else
          {{$data->date_of_entrys}} Hari
          @endif
        </td>
        <td>
          0 Hari
        </td>
        <td>
          @if($data->cuti == NULL)
          -
          @else
          {{$data->cuti}} Hari
          @endif
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</body>
</html>
@section('script')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   <script type="text/javascript">
     $('.money').mask('000,000,000,000,000.00', {reverse: true});
   </script>
@endsection