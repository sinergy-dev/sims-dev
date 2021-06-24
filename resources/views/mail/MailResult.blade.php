<!DOCTYPE html>
<html>
<head>
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
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
	<script type="text/javascript">
		console.log( $("#amounts").text())
	    $('.money').mask('000,000,000,000,000', {reverse: true});
	</script>
</head>
<body style="display: block; width: 600px; margin-left: auto; margin-right: auto; color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" style="width: 30%; height: 30%">
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				Dear Bu {{$users->name}},
			</p>
			<p style="font-size: 16px">
				Please create a Project ID for the project with the following details:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th>Lead Id</th>
						<th> : </th>
						<td>{{$pid_info->lead_id}}</td>
					</tr>
					<tr>
						<th>Nama Project</th>
						<th> : </th>
						<td>{{$pid_info->opp_name}}</td>
					</tr>
					<tr>
						<th>Nama Sales</th>
						<th> : </th>
						<td>{{$pid_info->name}}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<th> : </th>
						<td>{{$pid_info->amount_pid}}</td>
					</tr>
					<tr>
						<th>No. PO</th>
						<th> : </th>
						<td>{{$pid_info->no_po}}</td>
					</tr>
					<tr>
						<th>No Quote</th>
						<th> : </th>
						<td>{{$pid_info->quote_number2}}</td>
					</tr>
				</table>
			</div>
			<p style="font-size: 16px">
				Click the following link button to create a Project ID.
			</p>

			<!-- <center>
				<a href="{{url($pid_info->url_create)}}" class="btn btn-info" role="button">Create ID Project</a>
			</center> -->

			<center><a href="{{url($pid_info->url_create)}}" target="_blank"><button class="button"> Create ID project </button></a></center>

			<p style="font-size: 16px">
				Please check again, if there are errors or questions please contact the Developer Team (Ext: 384) or email to development@sinergy.co.id.<br>
				Thank you.
			</p>
			<p style="font-size: 16px">
				Best Regard,
			</p><br>
			<p style="font-size: 16px">
				Tech - Dev
			</p>
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


<!-- 
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear Bu {{$users->name}},
		<br>Mohon bantuan untuk pembuatan ID Project untuk project dengan detail sebagai berikut:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Lead Id</th>
			<th> : </th>
			<td>{{$pid_info->lead_id}}</td>
		</tr>
		<tr>
			<th>Nama Project</th>
			<th> : </th>
			<td>{{$pid_info->opp_name}}</td>
		</tr>
		<tr>
			<th>Nama Sales</th>
			<th> : </th>
			<td>{{$pid_info->name}}</td>
		</tr>
		<tr>
			<th>Amount</th>
			<th> : </th>
			<td>{{$pid_info->amount_pid}}</td>
		</tr>
		<tr>
			<th>No. PO</th>
			<th> : </th>
			<td>{{$pid_info->no_po}}</td>
		</tr>
		<tr>
			<th>No Quote</th>
			<th> : </th>
			<td>{{$pid_info->quote_number2}}</td>
		</tr>
	</table>
	<br>
	Silahkan klik link berikut ini untuk membuat ID Project.<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url($pid_info->url_create)}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Create ID Project
							</a>
						</td>

					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p>
		Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Tech - Dev</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 – Indonesia |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div> -->