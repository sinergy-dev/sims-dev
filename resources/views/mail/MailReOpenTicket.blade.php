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
		<center><img src="{{ asset('image/weekly1.png')}}" style="width: 50%; height: 50%"></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear {{$data["to"]}},</b>
			</p>
			<p style="font-size: 16px">
				I hereby, {{$data["requestor"]}} request a request to reopen the ticket with the following details :
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<ul>
					<li>
						<b>ID Ticket : </b> {{$data["id_ticket"]}}
					</li>
					<li>
						<b>Customer : </b> {{$data["customer"]}}
					</li>
					<li>
						<b>Problem : </b> {{$data["problem"]}}
					</li>
					<li>
						<b>Last Update : </b> {{$data["last_update"]}}
					</li>
				</ul>
			</div>
			<p style="font-size: 16px">
				The reasons why this is done can be seen below
			</p>
			<div id="bg_ket2" style="background-color: #ececec; padding: 10px">
				<ul>
					<li><b>{{$data["reopen_reason"]}}</b></li>
				</ul>
			</div>
			<p style="font-size: 16px">
				If you allow the reopening, you can immediately click the Allow button.
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