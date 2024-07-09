<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<style type="text/css">
		table {
		  width: 100%;
		  font-size: 11px;
		}

		table, td {
		  border-collapse: collapse;
		  border: 1px solid #000;
		}

		thead {
		  display: table; /* to take the same width as tr */
		  width: calc(100% - 10px);
		}

		tbody {
		  display: block; /* to enable vertical scrolling */
		  max-height: 1000px; /* e.g. */
		  overflow-y: scroll; /* keeps the scrollbar even if it doesn't need it; display purpose */
		}

		tr {
		  display: table; /* display purpose; th's border */
		  width: 100%;
		  box-sizing: border-box; /* because of the border (Chrome needs this line, but not FF) */
		}

		td {
		  text-align: center;
		  border-bottom: none;
		  border-left: none;
		}

		#bg_ket {
			border-radius: 10px;
		}

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
		}

		.button {
			border: none;
			color: white;
			/*padding: 15px 32px;*/
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			background-color: #7868e6;
			border-radius: 4px;
		}

		.button-action {
          display: flex;
          padding: 4px;
          border: 1px solid #ccc;
          background-color: #ED2939;
          border: 1px solid #ED2939;
          border-radius: 2px; 
          font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; 
          color: #ffffff;
          text-decoration: none;
          font-weight:500;
          display: inline-block;
          align-items: center;
          cursor: pointer;
          border-radius: 5px;
          width: 60px;
          height: 25px;
          text-align: center;
          vertical-align: middle;
      	}

      	svg {
          font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; 
          font-weight: bold;
          fill: currentColor; /* Use the current text color as the fill color for the SVG */
      	}

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/
	</style>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  	<script type="text/javascript">
  	</script>
</head>
<body style="display:block;width:800px;margin-left:auto;margin-right:auto;">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" style="width: 100px; height: 40px">
	</div>
	<div style="line-height: 1.5em">
		<center><img src="{{asset('image/maintenance.png')}}" href="https://app.sinergy.co.id/login" style="width: 250px; height: 250px" readonly></center>
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear All!</b>
			</p>
			<p style="font-size: 14px">
				Below is a list of assets that are nearing the end of maintenance:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px;">
				<table>
					<thead>
						<tr>
							<th width="7%">ID Asset</th>
							<th width="10%">Category</th>
							<th width="10%">Location</th>
							<th width="10%">Customer</th>
							<th width="12%">PID</th>
							<th width="15%">Period</th>
							<th width="10%">Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data['data'] as $data)
						<tr>
							<td width="10%">{{$data->id_asset}}</td>
							<td width="10%">{{$data->category}}</td>
							<td width="15%">{{$data->lokasi}}</td>
							<td width="10%">{{$data->client}}</td>
							<td width="10%">{{$data->pid}}</td>
							<td width="20%">{{$data->periode}}</td>
							<td width="10%"><a href="{{url('asset/detail')}}?id_asset={{$data->id_asset}}" target="_blank" class="button-action">Asset</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div style="width:800px;word-break: break-all;">
				<p style="font-size: 14px">
					Please check again, if there are errors or questions please contact the Developer Team (Ext: 384) or email to development@sinergy.co.id.<br>
					Thank you.
				</p>
			</div>
			<p style="font-size: 14px">
				Best Regard,
			</p><br>
			<p style="font-size: 14px">
				Application Development
			</p>
		</div>
	</div>
</body>
<footer style="display:block;width: 800px;margin-left:auto;margin-right:auto;">
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 14px">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>