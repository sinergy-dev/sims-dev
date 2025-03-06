<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$data["to"]}},<br>
		<br>
		Berikut Report Bulanan - Customer per ({{date('01 F Y', strtotime(date('d F Y')))}} s/d {{date('t F Y', strtotime(date('d F Y')))}})<br>
	</p>
	<table style="text-align: left;margin: 5px;" id="report">
		<thead>
          <tr>
            <th>Customer - Sales</th>
            <th>INITIAL</th>
            <th>OPEN</th>
            <th>SD</th>
            <th>TP</th>
            <th>WIN</th>
            <th>LOSE</th>
            <th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data["ter"] as $ter)
          <tr>
            <th colspan="8">{{$ter->id_territory}}</th>
          </tr>
	          @foreach($data["cus"] as $cus)
	          	@if($cus->id_territory == $ter->id_territory)
		          <tr>
		          	<td>{{$cus->brand_name}} - {{$cus->name}}</td>
		          	<td>{{$cus->INITIAL}}</td>
		          	<td>{{$cus->OPEN}}</td>
		          	<td>{{$cus->SD}}</td>
		          	<td>{{$cus->TP}}</td>
		          	<td>{{$cus->WIN}}</td>
		          	<td>{{$cus->LOSE}}</td>
		          	<td>{{$cus->All}}</td>
		          </tr>
		        @endif
	          @endforeach
          @endforeach
        </tbody>
	</table>
	<p>
		Jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
		Demikian yang dapat kami sampaikan
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Application Development</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 – Indonesia |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div>
<style type="text/css">
	#report {
	  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	#report td, #report th {
	  border: 1px solid #ddd;
	  padding: 8px;
	}

	#report tr:nth-child(even){background-color: #f2f2f2;}

	#report tr:hover {background-color: #ddd;}

	#report th {
	  padding-top: 12px;
	  padding-bottom: 12px;
	  text-align: left;
	  background-color: #4CAF50;
	  color: white;
	}
</style>