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
	<title>Laporan Lead Register</title>
</head>
<body>
<h3 class="center">LAPORAN LEAD REGISTER</h3>
 <table id="pseudo-demo">
                      <thead>
                        <tr>
                          <th>
                            Lead ID
                          </th>
                          <th>
                            Customer 
                          </th>
                          <th>
                            Opty Name
                          </th>
                          <th>
                            Create Date
                          </th>
                          <th>
                            Owner
                          </th>
                          <th>
                            Amount
                          </th>
                          <th>
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($customer as $data)
                        <tr>
                          <td class="py-1">
                            {{$data->lead_id}}
                          </td>
                          <td>
                            {{$data->brand_name}}
                          </td>
                          <td>
                            {{$data->opp_name}}
                          </td>
                          <td>
                            {!!substr($data->created_at,0,10)!!}
                          </td>
                          <td>
                            {{$data->name}}
                          </td>
                          <td ><i class="money">{{$data->amount}}</i>
                            
                          </td>
                          <td>
                            @if($data->result == 'OPEN')
                              <label class="status-initial">INITIAL</label>
                            @elseif($data->result == '')
                              <label class="status-open">OPEN</label>
                            @elseif($data->result == 'SD')
                              <label class="status-sd">SD</label>
                            @elseif($data->result == 'TP')
                              <label class="status-tp">TP</label>
                            @elseif($data->result == 'WIN')
                              <label class="status-win">WIN</label>
                            @else
                              <label class="status-lose">LOSE</label>
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