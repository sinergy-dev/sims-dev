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
	@if($req_atk->status == 'ACCEPT')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/atk_accept.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p>
					Hello {{$req_atk->name}},
				</p>
				<p>
					@if($status_barang == 'ATK')
						<br><b>Request ATK diapprove,</b> berikut rinciannya:
					@else
						<br><b>Request Logistik diapprove,</b> berikut rinciannya:
					@endif
					
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px">
						<tr>
							<th>Nama Barang</th>
							<th> : </th>
							<td>{{$req_atk->nama_barang}}</td>
						</tr>
						<tr>
							<th>Quantity</th>
							<th> : </th>
							<td>{{$req_atk->qty_akhir}}</td>
						</tr>
						<tr>
							<th>Tanggal Request</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$req_atk->keterangan}}</td>
						</tr>
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
				<!-- <center><a href="{{url('/asset_atk')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request ATK</button></a></center> -->
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Application Development
				</p>
			</div>
		</div>

	@elseif($req_atk->status == 'REJECT' || $req_atk->status == 'REJECTED')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/atk_reject.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p>
					Hello {{$req_atk->name}},
				</p>
				<p>
					@if($status_barang == 'ATK')
						<br><b>Maaf request ATK ditolak</b>, berikut rinciannya:
					@else
						<br><b>Maaf request Logistik ditolak</b>, berikut rinciannya:
					@endif
					
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px">
						<tr>
							<th>Nama Barang</th>
							<th> : </th>
							@if($req_atk->status == 'REJECT')
							<td>{{$req_atk->nama_barang}}</td>
							@else
							<td>{{$req_atk->nama}}</td>
							@endif
						</tr>
						<tr>
							<th>Quantity</th>
							<th> : </th>
							@if($req_atk->status == 'REJECT')
							<td>{{$req_atk->qty_akhir}}</td>
							@else
							<td>{{$req_atk->qty}}</td>
							@endif
						</tr>
						<tr>
							<th>Tanggal Request ATK</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							@if($req_atk->status == 'REJECT')
							<td>{{$req_atk->note}}</td>
							@else
							<td>{{$req_atk->note_reject}}</td>
							@endif
						</tr>
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
				<!-- <center><a href="{{url('/asset_atk')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request ATK</button></a></center> -->
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Application Development
				</p>
			</div>
		</div>

	@elseif($req_atk->status == 'PROCESS')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/atk_process.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p>
					Hello {{$req_atk->name}},
				</p>
				<p>
					@if($status_barang == 'ATK')
						<br><b>Request ATK anda sedang diproses,</b> berikut rinciannya:
					@else
						<br><b>Request Logistik anda sedang diproses,</b> berikut rinciannya:
					@endif
					
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px">
						<tr>
							<th>Nama Barang</th>
							<th> : </th>
							<td>{{$req_atk->nama}}</td>
						</tr>
						<tr>
							<th>Quantity</th>
							<th> : </th>
							<td>{{$req_atk->qty}}</td>
						</tr>
						<tr>
							<th>Tanggal Request</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$req_atk->keterangan}}</td>
						</tr>
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
				<!-- <center><a href="{{url('/asset_atk')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request ATK</button></a></center> -->
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Application Development
				</p>
			</div>
		</div>

	@elseif($req_atk->status == 'DONE')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/atk_done.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p>
					Hello {{$req_atk->name}},
				</p>
				<p>
					<br><b>Barang sudah datang,</b> berikut rinciannya:
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px">
						<tr>
							<th>Nama Barang</th>
							<th> : </th>
							<td>{{$req_atk->nama}}</td>
						</tr>
						<tr>
							<th>Quantity</th>
							<th> : </th>
							<td>{{$req_atk->qty}}</td>
						</tr>
						<tr>
							<th>Tanggal Request</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$req_atk->keterangan}}</td>
						</tr>
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
				<!-- <center><a href="{{url('/asset_atk')}}#peminjaman_asset_atk" target="_blank"><button class="button">Request ATK</button></a></center> -->
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Application Development
				</p>
			</div>
		</div>

	@else
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/atk_done.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p>
					Hello {{$get_user->name}},
				</p>
				<p>
					@if($status_barang == 'ATK')
						<br><b>Berikut request ATK</b> oleh:
					@else
						<br><b>Berikut request Logistik</b> oleh:
					@endif
					
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px">
						<tr>
							<th>Request By</th>
							<th> : </th>
							<td>{{$req_atk->name}}</td>
						</tr>
						<tr>
							<th>Nama Barang</th>
							<th> : </th>
							<td>{{$req_atk->nama_barang}}</td>
						</tr>
						<tr>
							<th>Quantity</th>
							<th> : </th>
							<td>{{$req_atk->qty_akhir}}</td>
						</tr>
						<tr>
							<th>Tanggal Request</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$req_atk->keterangan}}</td>
						</tr>
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
				
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					BCD - Dev
				</p>
			</div>
		</div>
	@endif
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