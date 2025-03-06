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
		<div style="line-height: 1.5em">
			<center><img src="{{ asset('image/change_owner_new2.png')}}" style="width: 50%; height: 50%;"></center>
		</div>
		<div style="line-height: 1.5em;padding-left: 13px;">
			<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
				<p style="font-size: 16px">
					<h3>Change Lead Register Ownership</h3>
					This email is intended to inform you that there has been a change in Lead Register Ownership. <br>For more details, please see below.<br>
				</p>
				<div id="bg_ket" style="background-color: #ececec; padding: 10px; text-align: justify; text-justify: inter-word;">
					@foreach($data as $value)
					<p>
						Lead ID &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <a href="https://app.sinergy.co.id/project/detailSales/{{$value['lead_id']}}">{{$value['lead_id']}}</a>
					</p>
					<p>
						Sales Before &nbsp&nbsp&nbsp&nbsp: {{$value['before_sales']}}
					</p>
					<p>
						Sales After &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: {{$value['after_sales']}}
					</p>
					<p>
						Change Date &nbsp&nbsp: {{$value['date_change']}}
					</p>
					<p>
						Changer &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: {{$value['changer']}}
					</p>
					<hr>
					@endforeach
				</div>
				<!-- <div id="bg_ket" style="background-color: #ececec; padding: 10px">
					<table style="text-align: left;margin: 5px; font-size: 14px" class="tableLead">
						@foreach($data as $value)
							<tr>
								<th>Lead ID</th>
								<th> : </th>
								<th><a href="https://app.sinergy.co.id/project/detailSales/{{$value['lead_id']}}">{{$value['lead_id']}}</a></th>
							</tr>
							<tr>
								<th>Sales Before</th>
								<th> : </th>
								<th>{{$value['before_sales']}}</th>
							</tr>
							<tr>
								<th>Sales After</th>
								<th> : </th>
								<th>{{$value['after_sales']}}</th>
							</tr>
							<tr>
								<th>Change Date</th>
								<th> : </th>
								<th>{{$value['date_change']}}</th>
							</tr>
							<tr>
								<th>Changer</th>
								<th> : </th>
								<th>{{$value['changer']}}</th>
							</tr><hr>
						@endforeach
					</table>
				</div> -->
				<p style="font-size: 16px">
					That is what can be conveyed.<br>	
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