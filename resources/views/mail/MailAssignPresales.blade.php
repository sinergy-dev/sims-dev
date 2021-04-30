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
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" href="https://app.sinergy.co.id/login" style="width: 30%; height: 30%" readonly>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear {{$data->presales_name}},</b>
				@if($status == 'assign')
				<br><p>you are assigned by presales manager</p>		
				@else
				<br><p>you are re-assigned by presales manager</p>
				@endif
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<center><b>{{$data->lead_id}} - {{$data->opp_name}}</b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th>Nama Sales</th>
						<th> : </th>
						<td>{{$data->sales_name}}</td>
					</tr>
					<tr>
						<th>Amount</th>
						<th> : </th>
						<td><div class="money">{{$data->amount}}</div></td>
					</tr>
					<tr>
						<th>Status</th>
						<th> : </th>
						<td><span style="padding: 5px;background-color: #f2562b;color: white;border-radius: 3px">Open</span></td>
					</tr>
				</table>
			</div>
			<p style="font-size: 16px">
				To access the Application please click the following button.<br>
			</p>
			<center>
				<!-- <a href="{{url('/detail_project',$data->lead_id)}}" target="_blank" class="btn btn-danger btn-block" type="button"><b>Lead ID</b></a> -->
				<a href="{{url('/detail_project',$data->lead_id)}}" class="btn btn-info" role="button">Lead ID</a>
			</center>
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