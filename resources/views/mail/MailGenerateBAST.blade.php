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
	</style>
</head>
<!--ini blade email-->
<body style="display:block;width:800px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<center><img src="{{ asset('/image/sims_sangar_2.png')}}" style="width: 10%; height: 10%"></center>
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				<b>Dear {{$data[0]["name"]}},</b>
			</p>
			<p style="font-size: 16px">
				BAST has been generate with the following details :
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<ul>
					<li>
						<b>ID Asset : </b> {{$data[0]["id_asset"]}}
					</li>
					<li>
						<b>Category : </b> {{$data[0]["category"]}}
					</li>
					<li>
						<b>Type Device : </b> {{$data[0]["type_device"]}}
					</li>
					<li>
						<b>Spesifikasi : </b> {{$data[0]["spesifikasi"]}}
					</li>
				</ul>
			</div>
			<p style="font-size: 16px">
				This is following url for BAST file,
			</p>
			<div>
				<a href="{{$data[0]['link_drive']}}">Berita Acara {{$data[0]["id_asset"]}}</a>
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