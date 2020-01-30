<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">
    .page {
    page-break-after:always;
    position: relative;
    }
		table {
    border-spacing: 0;
    width: 100%;
    }
/*    th {
    background: #404853;
    background: linear-gradient(#687587, #404853);
    border-left: 1px solid rgba(0, 0, 0, 0.2);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 12px;
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
    }*/
    th{
      border-right: 1px solid;
      border-left: 1px solid;
      border-top: 1px solid;
      border-bottom: 1px solid;
    }
    td {
    border-right: 1px solid ;
    border-bottom: 1px solid;
    font-size: 12px;
    padding: 8px;
    text-align: left;
    vertical-align: center;
    }
    td:first-child {
    border-left: 1px solid ;
    }
    tr:first-child td {
    border-top: 0;
    }
    tr.heads td{
      background: #e8eae9;
      padding:4px; 
      vertical-align: center;
      font-size: 12px;
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
	<title>Laporan PR Asset Management</title>
</head>
<body>
<img src="img/sippng.png" style="width:160px;height:80px;padding-left: 570px;" />
  <br><br>
 <table id="pseudo-demo" class="page">
                      <thead>
                        <tr>
                          <th colspan="9">
                            <h3 class="center">Status Pembayaran PR Internal DVG</h3>
                          </th>
                        </tr>
                        <tr class="heads">
                          <td>
                            Created Date
                          </td>
                          <td>
                            No. Purchase Request
                          </td>
                          <td>
                           To
                          </td>
                          <td>
                           Personel
                          </td>
                          <td>
                           Product
                          </td>
                          <td>
                           Qty
                          </td>
                          <td>
                           Amount
                          </td>
                          <td>
                           Description
                          </td>
                          <td>
                           Note
                          </td>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($datas as $data)
                        <tr>
                          <td>
                            {{$data->date_handover}}
                          </td>
                          <td>
                            {{$data->no_pr}}
                          </td>
                          <td>
                            {{$data->to_agen}}
                          </td>
                          <td>
                            {{$data->name}}
                          </td>
                          <td>
                            @foreach($produks as $produk)
                              @if($data->id_pam == $produk->id_pam)
                                {{$produk->name_product}}<br> 
                              @endif
                            @endforeach
                          </td>
                          <td>
                            @foreach($produks as $produk)
                              @if($data->id_pam == $produk->id_pam)
                              {{$produk->qty}}<br>
                              @endif
                            @endforeach
                          </td>
                          <td>
                            @foreach($produks as $produk)
                              @if($data->id_pam == $produk->id_pam)
                              {{$produk->nominal}}<br>
                              @endif
                            @endforeach
                          </td>
                          <td>
                            {{$data->ket_pr}}
                          </td>
                          <td>
                            {{$data->note_pr}}
                          </td>
                        </tr>
                      @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="6" style="float: right;">
                            <i style="float: right;">Total Amount</i>
                          </td>
                          <td>
                            <i class="money">{{$total_amount}}</i>                           
                          </td>
                          <td colspan="2">
                            
                          </td>
                        </tr>
                      </tfoot>
                    <img src="img/footer.PNG" style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 10px; width: 775px; height: 130px"/>
                    </table>
<!-- <img src="img/footer.PNG" style="width:800px;height: 130px; A_CSS_ATTRIBUTE:all;position: absolute"> -->
</body>
</html>
@section('script')
<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
<script type="text/javascript">
     $('.money').mask('000,000,000,000,00', {reverse: true});
</script>
@endsection