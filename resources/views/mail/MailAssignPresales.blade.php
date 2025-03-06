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
		<center><img src="{{ asset('image/assign.png')}}" href="https://app.sinergy.co.id/login" style="width: 50%; height: 50%" readonly></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear {{$data['data']->presales_name}},</b>
				@if($data['status'] == 'assign')
				<br><p style="font-size: 14px">You are assigned by {{$data['assignBy']}}</p>		
				@else
				<br><p style="font-size: 14px">You are re-assigned by {{$data['assignBy']}}</p>
				@endif
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<center><b>{{$data['data']->lead_id}} - {{$data['data']->opp_name}}</b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th>Nama Sales</th>
						<th> : </th>
						<td>{{$data['data']->sales_name}}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<th> : </th>
						<td>Rp.{{number_format($data['data']->amount, 2, '.', ',')}}</td>
					</tr>
					<tr>
						<th>Status</th>
						<th> : </th>
						<td>
						@if($data['data']->result_modif == 'OPEN')
							<span style="padding: 5px;background-color:#f2562b;color: white;border-radius: 3px">
						@elseif($data['data']->result_modif == 'Solution Design')
							<span style="padding: 5px;background-color:#04dda3;color: white;border-radius: 3px">
						@endif
							{{$data['data']->result_modif}}
						</span>
						</td>
					</tr>
				</table>
			</div>
			<p style="font-size: 16px">
				To access the Application please click the following button.<br>
			</p>

			<center><a href="{{url('/project/detailSales',$data['data']->lead_id)}}" target="_blank"><button class="button">Lead ID</button></a></center>
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