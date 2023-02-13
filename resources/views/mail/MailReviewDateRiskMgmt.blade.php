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
			/*padding: 15px 32px;*/
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
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" style="width: 10%; height: 10%">
	</div>
	<div style="line-height: 1.5em">
		<center><img src="{{asset('image/risk_review.png')}}" href="https://app.sinergy.co.id/login" style="width: 50%; height: 50%" readonly></center>
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 24px">
				<b>Dear {{$data['to']}}!</b>
			</p>
			<p style="font-size: 16px">
				Berikut terdapat risk yang masih aktif, dengan detail sebagai berikut:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 12px" class="tableLead">
					<thead>
	                  <tr>
	                    <th>Project Id</th>
	                    <th>Risk Description</th>
	                    <th>Risk Owner</th>
	                    <th>Review Data</th>
	                  </tr>
	                </thead>
					<center><b></b></center>
					<tbody>
						@foreach($data['risk'] as $risk)
						<tr>
							<td><a href="{{url('/PMO/project/detail',$risk->id_pmo)}}">{{$risk->project_id}}</a></td>
							<td>{{$risk->risk_description}}</td>
							<td>{{$risk->risk_owner}}</td>
							<td>{{$risk->review_date}}</td>
						</tr>
						@endforeach
					</tbody>
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
								<td style="border-radius: 2px;">
									<a href="{{url('/PMO/project')}}" target="_blank" style="background-color: #ED2939;padding:8px 8px; border: 1px solid #ED2939;border-radius: 2px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; align-items: center;">
										Project
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
				BCD - Dev
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