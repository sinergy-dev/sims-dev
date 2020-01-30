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
    font-size: 11px;
    padding: 8px;
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
	<title>Laporan Status Open</title>
</head>
<body>
  <img src="img/sippng.png" style="width:160px;height:80px;padding-left: 570px;"/>
  <br><br>
<h3 class="center">LAPORAN STATUS OPEN</h3>
<br>
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
                      @foreach($open as $data)
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
                            {{$data->created_at}}
                          </td>
                          <td>
                            {{$data->name}}
                          </td>
                          <td>
                            {{number_format($data->amount,2,',','.')}}
                          </td>
                          <td>
                            @if($data->result == 'OPEN')
                              <label class="status-initial">INITIAL</label>
                            @elseif($data->result == '')
                              <label class="status-open">OPEN</label>
                            @elseif($data->result == 'SD')
                              <label class="status-sd">SOLUTION DESIGN</label>
                            @elseif($data->result == 'TP')
                              <label class="status-tp">TENDER PROCESS</label>
                            @elseif($data->result == 'WIN')
                              <label class="status-win">WIN</label>
                            @else
                              <label class="status-lose">LOSE</label>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                      @foreach($sd as $data)
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
                            {{$data->created_at}}
                          </td>
                          <td>
                            {{$data->name}}
                          </td>
                          <td>
                            {{number_format($data->amount,2,',','.')}}
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
                      @foreach($tp as $data)
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
                            {{$data->created_at}}
                          </td>
                          <td>
                            {{$data->name}}
                          </td>
                          <td>
                            {{number_format($data->amount,2,',','.')}}
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
                    <img src="img/footer.PNG" style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 10px; width: 775px; height: 130px"/>
                    </table>
                    <!-- <img src="img/footer.PNG" style="width:800px;height: 130px; A_CSS_ATTRIBUTE:all;position: absolute"> -->
</body>
</html>