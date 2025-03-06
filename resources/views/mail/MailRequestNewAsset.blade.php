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

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
		}

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

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/

		.table_request th{
			border: 1px solid;
		}

		.table_request td{
			border: 1px solid;
		}
	</style>
</head>
<body style="display:block;width:900px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<center><img src="{{asset('image/sims_sangar_2.png')}}" style="width: 30%; height: 30%" readonly></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				Dear {{$users->name}},
			</p>
			<p>
				Ada request asset baru, bantu cek dan approve ya! untuk melanjutkan proses request asset<br>
				berikut rinciannya:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px;width: 100%;">
					<tr>
						<th>Request By</th>
						<th> : </th>
						<!-- <td>Bima Aldi Pratama</td> -->
						<td>{{$req_asset['requestor_name']}}</td>
					</tr>
					<tr>
						<th>Request Date</th>
						<th> : </th>
						<!-- <td>2021-02-14</td> -->
						<td>{{date('d-M-Y', strtotime($req_asset['request_date']))}}</td>
					</tr>
				</table>
				<table style="text-align: left;margin: 5px; font-size: 16px;width: 100%;" class="table_request">
						<tr>
							<th>Name/Merk</th>
							<th>Category</th>
							<th>Merk</th>
							<th>Description</th>
							<th>Used for</th>
							<th>Duration</th>
							<th>Qty</th>		
						</tr>
					@foreach($req_asset['insertdata'] as $data)
						<tr>
							<td>{{$data['nama']}} {{$data['merk']}}</td>
							<td>{{$data['kategori']}}</td>
							<td>{{$data['merk']}}</td>
							<td>{{$data['link']}}</td>
							<td>{{$data['keperluan']}}</td>
							@if($data['duration'] == "Lifetime")
								<td>{{$data['duration']}}</td>
							@else
								<td>{{$data['duration_start']}} - {{$data['duration_end']}}</td>
							@endif
							<td>{{$data['qty']}}</td>
						</tr>
					@endforeach	
				</table>
			</div>
			<p style="font-size: 16px">
				Silahkan klik link berikut ini untuk melihat Detail Request Asset.<br>
			</p>
			<center><a href="{{url('/asset_hr')}}#request_asset" target="_blank"><button class="button">Request Asset</button></a></center>
			<p style="font-size: 16px">
				Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.
			</p>
			<p style="font-size: 16px">
				Best Regard,
			</p><br>
			<p style="font-size: 16px">
				Application Development
			</p>
		</div>
	</div>
</body>
<footer style="display:block;width:900px;margin-left:auto;margin-right:auto;color: #000000">
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