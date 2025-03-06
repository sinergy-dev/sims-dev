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

		#bg_ket2 {
			border-radius: 10px;
		}

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
		}

		.moneyBefore:before{
			content:"Rp";
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
		<center><img src="{{ asset('image/nominal_change.png')}}" style="width: 50%; height: 50%"></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear {{$data["to"]}},</b>
			</p>
			<p style="font-size: 16px">
				<!-- I hereby, {{$data["requestor"]}} request a request to reopen the ticket with the following details : -->
				@if($data["type"] == "Change Customer")
				I am here, {{$data["requestor"]}}, asking to make a customer change to the Lead Register with the following details
				@else
				I am here, {{$data["requestor"]}}, asking to make a nominal change to the Lead Register with the following details
				@endif
				
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<ul>
					<li>
						<b>Lead ID : </b> {{$data["lead_id"]}}
					</li>
					<li>
						<b>Project : </b> {{$data["project"]}}
					</li>
					<li>
						@if($data["type"] == "Change Customer")
						<b>Customer : </b> {{$data["customer_before"]}}
						@else
						<b>Customer : </b> {{$data["customer"]}}
						@endif
					</li>
					<li>
						<b>Created at : </b> {{substr($data["created_at"],0,10)}}
					</li>
				</ul>
			</div>
			<p style="font-size: 16px">
				Condition before change :
			</p>
			<div id="bg_ket2" style="background-color: #EDBABA; padding: 10px">
				<ul>
					<li>
						@if($data["type"] == "Change Customer")
						<b>Customer Before : </b> {{$data["customer_before"]}}
						@else
						<b>Nominal Before : </b> Rp.{{ number_format($data["nominal_before"], 2) }} 
						@endif
					</li>
				</ul>
			</div>

			<p style="font-size: 16px">
				Conditions requested to be changed :
			</p>
			<div id="bg_ket2" style="background-color: #B0DEB2; padding: 10px">
				<ul>
					<li>
						@if($data["type"] == "Change Customer")
						<b>Customer After : </b> {{$data["customer_after"]}}
						@else
						<b>Nominal After : </b> Rp.{{number_format($data["nominal_after"], 2)}}
						@endif
					</li>
				</ul>
			</div>

			<p style="font-size: 16px">
				The reasons why this is done can be seen below :
			</p>
			<div id="bg_ket2" style="background-color: #ececec; padding: 25px">
				<b>{{$data["reason"]}}</b></ul>
			</div>
			<p style="font-size: 16px">
				If you allow to make these changes, you can directly press the 'Allow Change' button below.
			</p>
			<div>
				<center>
					<a href="{{$data['url']}}" target="_blank">
						<button class="button" style="
							border-radius: 10px;
							background-color: #4CAF50; /* Green */
							border: none;
							color: white;
							padding: 15px 32px;
							text-align: center;
							text-decoration: none;
							display: inline-block;
							font-size: 16px;"> Allow Change </button>
					</a>
				</center>
			</div>
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