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
</head>
<body style="display: block; width: 600px; margin-left: auto; margin-right: auto; color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/result.png')}}" style="width: 50%; height: 50%">
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
						<td>Rp.{{number_format($pid_info->amount_pid, 2, '.', ',')}}</td>
					</tr>
					<tr>
						<th>No. PO</th>
						<th> : </th>
						<td>{{$pid_info->no_po}}</td>
					</tr>
					<tr>
						<th>No Quote</th>
						<th> : </th>
						<td>{{$pid_info->quote_number_final}}</td>
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
				Application Development
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