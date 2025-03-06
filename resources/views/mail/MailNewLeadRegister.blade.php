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
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar.png')}}" style="width: 30%; height: 30%">
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>New lead in the apps!</b>
			</p>
			<p style="font-size: 16px">
				The following is a detail of the new lead register.
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<center><b>{{$data->lead_id}} - {{$data->opp_name}}</b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th>Sales</th>
						<th> : </th>
						<td>{{$data->name}}</td>
					</tr>
					<tr>
						<th>Customer Name</th>
						<th> : </th>
						<td>{{$data->customer_legal_name}}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<th> : </th>
						<td>Rp.{{number_format($data->amount, 2, '.', ',')}}</td>
					</tr>
					<tr>
						<th>Status</th>
						<th> : </th>
						<td><span style="padding: 5px;background-color: #605ca8; color: white; border-radius: 3px">Initial</span></td>
					</tr>
				</table>
			</div>
			<p style="font-size: 16px">
				To access the Application please click the following button.<br>
			</p>
			<center><a href="{{url('/project')}}" target="_blank"><button class="button"> Lead ID </button></a></center>
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