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
	</script>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<!-- <center><img src="{{ asset('image/weekly1.png')}}" style="width: 50%; height: 50%"></center> -->
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Change Success</b>
			</p>
			<p style="font-size: 16px">
				Change request with the following details :
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<ul>
					<li>
						<b>ID Change Request : </b> {{$requestChange["id"]}}
					</li>
					<li>
						<b>Change Type : </b> {{$requestChange["type"]}}
					</li>
					<li>
						<b>Requester : </b> {{$requestChange["requester"]}}
					</li>
					<li>
						<b>Parameter 1 Before: </b> {{$requestChange["parameter1_before"]}}
					</li>
					<li>
						<b>Parameter 1 After : </b> {{$requestChange["parameter1_after"]}}
					</li>
					<li>
						<b>Parameter 2 Before : </b> {{$requestChange["parameter2_before"]}}
					</li>
					<li>
						<b>Parameter 2 After: </b> {{$requestChange["parameter2_after"]}}
					</li>
					<li>
						<b>Parameter 3 Before : </b> {{$requestChange["parameter3_before"]}}
					</li>
					<li>
						<b>Parameter 3 After: </b> {{$requestChange["parameter3_after"]}}
					</li>
					<li>
						<b>Change By : </b> {{$requestChange["change_by"]}}
					</li>
					<li>
						<b>Change At : </b> {{$requestChange["change_at"]}}
					</li>
					<li>
						<b>Status : </b> {{$requestChange["status"]}}
					</li>
				</ul>
			</div>
			
			<p style="font-size: 16px">
				Please check again, if there are errors or questions please contact the Developer Team (Ext: 384) or email to development@sinergy.co.id.<br>
				Thank you.
			</p>
		</div>
	</div>
</body>
</html>