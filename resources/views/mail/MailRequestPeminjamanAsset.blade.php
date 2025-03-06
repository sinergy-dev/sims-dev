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
	</style>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" href="https://app.sinergy.co.id/login" style="width: 30%; height: 30%" readonly>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				Hello {{$users->name}},
				@if($req_asset->status == 'PENDING')
				<br>Permohonan Peminjaman Asset,
				@elseif($req_asset->status == 'AVAILABLE')
				<br>Terima kasih atas Pengembalian Asset,
				@endif
				berikut rinciannya:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px">
					@if($req_asset->status == 'PENDING')
						<tr>
							<th style="vertical-align: top">Kategori</th>
							<th style="vertical-align: top"> : </th>
							<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
							<td>{!!$req_asset->note!!}</td>
						</tr>
						<tr>
							<th style="vertical-align: top">Note</th>
							<th style="vertical-align: top"> : </th>
							<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
							<td>{!!$req_asset->keterangan!!}</td>
						</tr>
						<tr>
							<th>Request By</th>
							<th> : </th>
							<!-- <td>Bima Aldi Pratama</td> -->
							<td>{{$req_asset->name}}</td>
						</tr>
						<tr>
							<th>Request Date</th>
							<th> : </th>
							<!-- <td>2021-02-14</td> -->
							<td>{{date('d-M-Y', strtotime($req_asset->created_at))}}</td>
						</tr>
					@elseif($req_asset->status == 'AVAILABLE')
						<tr>
							<th>Name</th>
							<th> : </th>
							<!-- <td>SEPEDA MOTOR HONDA SUPRA X125</td> -->
							<td>{{$req_asset->nama_barang}}</td>
						</tr>
						<tr>
							<th>Description</th>
							<th> : </th>
							<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
							<td>{{$req_asset->description}}</td>
						</tr>
						<tr>
							<th>Tanggal Peminjaman</th>
							<th> : </th>
							<!-- <td>2021-02-14</td> -->
							<td>{{date('d-M-Y', strtotime($req_asset->tgl_peminjaman))}}</td>
						</tr>
						<tr>
							<th>Tanggal Pengembalian</th>
							<th> : </th>
							<!-- <td>2021-02-14</td> -->
							<td>{{date('d-M-Y', strtotime($req_asset->tgl_pengembalian))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{!!$req_asset->keterangan!!}</td>
						</tr>
					@endif

					@if($req_asset->status == 'AVAILABLE')
						<tr>
							<th>Lokasi</th>
							<th> : </th>
							<td>{{$req_asset->lokasi}}</td>
						</tr>
					@endif
				</table>
			</div>
			@if($req_asset->status == 'PENDING')
				<p style="font-size: 16px">
					Silahkan klik link berikut ini untuk melihat Detail Request ATK.<br>
				</p>
				<center><a href="{{url('/asset_hr')}}#request_asset" target="_blank"><button class="button">Request Asset</button></a></center>
			@endif
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
<footer style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
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