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
				Dear {{$admin->name}},
				Mohon bantuan untuk <i>Approving</i> peminjaman barang dengan keterangan berikut:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px">
					<tr>
						<th>Tanggal Peminjaman</th>
						<th> : </th>
						<td>{{date('d/m/Y', strtotime($peminjaman->tgl_peminjaman))}}</td>
					</tr>
					<tr>
						<th>Tanggal Pengembalian</th>
						<th> : </th>
						<td>{{date('d/m/Y', strtotime($peminjaman->tgl_pengembalian))}}</td>
					</tr>
					<tr>
						<th>Nama Peminjam</th>
						<th> : </th>
						<td>{{$peminjaman->name}}</td>
					</tr>
					<tr>
						<th>Kategori Barang</th>
						<th> : </th>
						<td>{{$peminjaman->kategori}}</td>
					</tr>
					<tr>
						<th>Keperluan</th>
						<th> : </th>
						<td>{{$peminjaman->keperluan}}</td>
					</tr>
					<tr>
						<th>Lokasi Peminjaman</th>
						<th> : </th>
						<td>{{$peminjaman->keterangan}}</td>
					</tr>
				</table>
			</div><br>
			<center><a href="{{url('/asset_pinjam#peminjaman')}}" target="_blank"><button class="button">Peminjaman Barang</button></a></center>
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