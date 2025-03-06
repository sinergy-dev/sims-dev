<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
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
	@if($hari['cuti_accept']->status == 'v')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/annual_leave_appoved_new_psd.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p style="font-size: 16px">
					Hello <b>Sinergy</b>, hore Cuti Kamu di Approve! Berikut Detail cuti kamu:
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px" class="tableLead">
						<tr>
							<th>Nama</th>
							<th> : </th>
							<th>{{$name_cuti->name}}</th>
						</tr>

						<tr>
							<th>Lama Cuti</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->days}} hari</td>
						</tr>

						<tr>
							<th>Tanggal Cuti <b style="color: green">(approved)</th>
							<th> : </th>
						</tr>

						<tr>
							<td>
								@foreach($ardetil as $data)
								<ul>
									
									<li>
										{{date('d-M-Y', strtotime($data))}}
									</li>
									
								</ul>
								@endforeach
							</td>
						</tr>

						<tr>
							<th>Tanggal Off cuti <b style="color: red">(rejected)<b></th>
							<th> : </th>
						</tr>

						<tr>
							<td>
								@if($ardetil_after != null)
									@foreach($ardetil_after as $data)
									<ul>
										
										<li>
											{{date('d-M-Y', strtotime($data))}}
										</li>
										
									</ul>
									@endforeach
								@else
								
								@endif				
							</td>
						</tr>

						<tr>
							<th>Alasan Reject Cuti</th>
							<th> : </th>
							<td>
								@if($hari['cuti_reject'] != '')
									{{$hari['cuti_reject']->decline_reason}}
								@else
								 
								@endif
							</td>
						</tr>

						<tr>
							<th>Tanggal Request Cuti</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
						</tr>

						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->reason_leave}}</td>
						</tr>
					</table>
				</div>
				<p style="font-size: 16px">
					Silahkan klik link berikut ini untuk melihat Detail Cuti.<br>	
				</p>
				<center><a href="{{url('/show_cuti')}}" target="_blank"><button class="button"> Leaving permit </button></a></center>
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Tech - Dev
				</p>
			</div>
		</div>

	@elseif($hari['cuti_accept']->status == 'd')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/annual_leave_not_appoved_new_psd.png')}}" style="width: 50%; height: 50%"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p style="font-size: 16px">
					Sorry cuti Kamu di decline sama Bos! Berikut Detail cuti kamu:
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px" class="tableLead">
						<tr>
							<th>Nama</th>
							<th> : </th>
							<td>{{$name_cuti->name}}</td>
						</tr>

						<tr>
							<th>Lama Cuti</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->days}} hari</td>
						</tr>

						<tr>
							<th>Tanggal Off cuti sbb</th>
							<th> : </th>
						</tr>

						<tr>
							<td>
								@foreach($ardetil as $data)
								<ul>
									
									<li>
										{{date('d-M-Y', strtotime($data))}}
									</li>
									
								</ul>
								@endforeach
							</td>
						</tr>
						<tr>
							<th>Tanggal Request Cuti</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->reason_leave}}</td>
						</tr>
						<tr>
							<th>Decline Reason</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->decline_reason}}</td>
						</tr>
					</table>
				</div>
				<p style="font-size: 16px">
					Silahkan klik link berikut ini untuk melihat Detail Cuti.<br>	
				</p>
				<!-- <table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-radius: 2px;" bgcolor="#ED2939">
										<a href="{{url('/show_cuti')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
											Leaving Permite.
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table> -->

				<center><a href="{{url('/show_cuti')}}" target="_blank"><button class="button"> Leaving permit </button></a></center>
				<br>
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Tech - Dev
				</p>
			</div>
		</div>

	@elseif($hari['cuti_accept']->status == 'R')
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/annual_leave_edit_new_psd.png')}}" style="width: 50%; height: 50%"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p style="font-size: 20px">
					Hai Bos, berikut re-schedule perizinan cuti oleh:
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px" class="tableLead">
						<tr>
							<th>Nama</th>
							<th> : </th>
							<td>{{$name_cuti->name}}</td>
						</tr>
						<tr>
							<th>Lama Cuti</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->days}} hari</td>
						</tr>
						<tr>
							<th>Tanggal Off cuti sebelumnya sbb</th>
							<th> : </th>
						</tr>
						<tr>
							<td>
								@foreach($ardetil as $data)
								<ul>
									
									<li>
										{{date('d-M-Y', strtotime($data))}}
									</li>
									
								</ul>
								@endforeach
							</td>
						</tr>
						@if($ardetil_after != "")
						<tr>
							<th>Tanggal Off cuti re-schedule sbb</th>
							<th> : </th>
						</tr>
						<tr>
							<td>
								@foreach($ardetil_after as $data)
								<ul>
									
									<li>
										{{date('d-M-Y', strtotime($data))}}
									</li>
									
								</ul>
								@endforeach
							</td>
						</tr>
						@endif
						<tr>
							<th>Tanggal Request Cuti</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
						</tr>
					</table>
				</div>
				<p style="font-size: 16px">
					Silahkan klik link berikut ini untuk melihat Detail Cuti.<br>	
				</p>
				<!-- <table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-radius: 2px;" bgcolor="#ED2939">
										<a href="{{url('/show_cuti')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
											Leaving Permite.
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table> -->

				<center><a href="{{url('/show_cuti')}}" target="_blank"><button class="button"> Leaving permit </button></a></center>
				<br>
				<p style="font-size: 16px">
					Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
					Terima kasih.
				</p>
				<p style="font-size: 16px">
					Best Regard,
				</p><br>
				<p style="font-size: 16px">
					Tech - Dev
				</p>
			</div>
		</div>

	@else
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/annual_leave_new_psd.png')}}" style="width: 50%; height: 50%"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p style="font-size: 16px">
					Hai Bos, berikut perizinan cuti oleh:
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px" class="tableLead">
						<tr>
							<th>Nama</th>
							<th> : </th>
							<td>{{$name_cuti->name}}</td>
						</tr>
						<tr>
							<th>Lama Cuti</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->days}} hari</td>
						</tr>
						<tr>
							<th>Tanggal Off cuti sbb</th>
							<th> : </th>
						</tr>
						<tr>
							<td>
								@foreach($ardetil as $data)
								<ul>
									
									<li>
										{{date('d-M-Y', strtotime($data))}}
									</li>
									
								</ul>
								@endforeach
							</td>
						</tr>
						<tr>
							<th>Tanggal Request Cuti</th>
							<th> : </th>
							<td>{{date('d-M-Y', strtotime($hari['cuti_accept']->date_req))}}</td>
						</tr>
						<tr>
							<th>Note</th>
							<th> : </th>
							<td>{{$hari['cuti_accept']->reason_leave}}</td>
						</tr>
					</table>
				</div>
				<p style="font-size: 16px">
					Silahkan klik link berikut ini untuk melihat Detail Cuti.<br>	
				</p>
				<!-- <table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-radius: 2px;" bgcolor="#ED2939">
										<a href="{{url('/show_cuti')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
											Leaving Permite.
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table> -->

				<center><a href="{{url('/show_cuti')}}" target="_blank"><button class="button"> Leaving permit </button></a></center>
				<br>
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