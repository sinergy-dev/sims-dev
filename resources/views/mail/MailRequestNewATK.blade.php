<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 0px solid grey;
		  padding-top: 14px;
		}

		table, th {
			padding-left: 15px;
		}

		#bg_ket {
			border-radius: 10px;
		}

		#bg_cuti {
			border-radius: 2px;
		}

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
		}

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/

		.button {
			border: none;
			color: white;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			background-color: #7868e6;
			border-radius: 4px;
		}
	</style>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<center><img src="{{ asset('image/atk_req.png')}}" style="width: 50%; height: 50%;"></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p>
				Hello {{$get_user->name}},
			</p>
			<p>
				@if($status_barang == 'ATK')
					Request New Asset, berikut rinciannya:
				@else
					Request New Logistik, berikut rinciannya:
				@endif
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 14px">
					<tr>
						<th>Request By</th>
						<th> : </th>
						<td>{{$req_atk['nama_peminjam']}}</td>
					</tr>
					<tr>
						<th>Request Date</th>
						<th> : </th>
						<td>{{date('d-M-Y', strtotime($req_atk['request_date']))}}</td>
					</tr>
				</table>
				<table style="text-align: left;margin: 5px; font-size: 14px">
					<tr>
						<th width="40%">Name / Merk</th>
						<th width="5%">Qty</th>
						@if($status == 'REQUEST')
							<th width="55%">Link</th>
						@else
							<th width="55%">Note</th>
						@endif			
					</tr>
					@foreach($req_atk['variable'] as $data)
					<tr style="text-align: left; margin: 10px; font-size: 14px">
						@if($status == 'REQUEST')
							<td>{{$data['nama']}}</td>
							<td>{{$data['qty']}}</td>
							<td><a href="{{$data['link']}}" target="_blank">{!!substr($data['link'],0,35)!!}...</a></td>
						@else
							<td>{{$data['nama_barang']}}</td>
							<td>{{$data['qty_akhir']}}</td>
							<td>{{$data['keterangan']}}</td>
						@endif
					</tr>
					@endforeach
				</table>
			</div>
			<p style="font-size: 16px">
				@if($status_barang == 'ATK')
					Silahkan klik link berikut ini untuk melihat Detail Request ATK.<br>
				@else
					Silahkan klik link berikut ini untuk melihat Detail Request Logistik.<br>
				@endif
			</p>
			@if($status_barang == 'ATK')
				<center><a href="{{url('/asset_atk')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request ATK</button></a></center>
			@else
				<center><a href="{{url('/asset_logistik')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request Logistik</button></a></center>
			@endif
			
			<br>
		</div>
	</div>
</body>
<footer style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center><i class="fa fa-phone"></i>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>