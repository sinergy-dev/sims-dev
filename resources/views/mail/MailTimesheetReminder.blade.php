<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<style type="text/css">

		table {
			width: 100%;
		  	border-collapse: collapse;
		}

		td,
		th {
		  padding: 10px; /* Adjust the value as per your preference */
		  border: 1px solid black; /* Optional: Adds borders to cells */
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
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<center><img src="{{ asset('/image/timesheet.png')}}" style="width: 50%; height: 50%"></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				Hello {{$all['to']}}!
			</p>
			<p style="font-size: 16px">
				Good Morning, Happy Working `Sip People`. And don't forget to fill out the timesheet you've created!
			</p>
			<div style="background-color: #ececec; padding: 20px">
				<center><b>List Schedule Planned Timesheet!</b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px">
					<thead>
						<tr>
							<th>Activity</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Action</th>
						</tr>
					</thead>
					@foreach($all['data'] as $data)
					<tr>
						<td>{{$data->activity}}</td>
						<td>{{$data->start_date}}</td>
						<td>{{$data->end_date}}</td>
						<td><a href="{{url('/timesheet/timesheet')}}?id={{$data->id}}&start_date={{$data->start_date}}" target="_blank">Update Me!</a></td>
					</tr>
					@endforeach
				</table>
			</div>
			<p style="font-size: 16px">
				To access the application, please click this following button.
			</p>
			<center><a href="" target="_blank"><button class="button"> Timesheet </button></a></center>
			<p style="font-size: 16px">
				Please check again, if there any errors or questions please contact the developer team (ext:384) or email to development@sinergy.co.id.
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