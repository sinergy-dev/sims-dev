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
		  border: 1px solid grey;
		}

		#bg_ket {
			border-radius: 10px;
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
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" style="width: 10%; height: 10%">
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 24px">
				<b>Raise to tender!</b>
			</p>
			<p style="font-size: 16px">
				The following is a detail of the list of lead register changed from solution design to tender process status.
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<center><b>{{$data->lead_id}} - {{$data->opp_name}}</b></center>
					<tr>
						<th>Presales</th>
						<th> : </th>
						<td>{{$data->presales_name}}</td>
					</tr>
					<tr>
						<th>Sales</th>
						<th> : </th>
						<td>{{$data->sales_name}}</td>
					</tr>
					<tr>
						<th>Customer Name</th>
						<th> : </th>
						<td>{{$data->customer_legal_name}}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<th> : </th>
						<td><div class="money">{{$data->amount}}</div></td>
					</tr>
					<tr>
						<th>Status</th>
						<th> : </th>
						<td><span style="padding: 5px;background-color: #ffc033;color: white;border-radius: 3px">Tender Process</span></td>
					</tr>
				</table>
			</div><br>
			<p style="font-size: 16px">
				To access the Application please click the following button.<br><br>
			</p>
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td style="border-radius: 2px;" bgcolor="#ED2939">
									<a href="{{url('/detail_project',$data->lead_id)}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; align-items: center;">
										Lead ID
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
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
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>