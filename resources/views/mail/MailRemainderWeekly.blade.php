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
				the following bellow is the weekly report as {{date('D, d F Y')}}.
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th>{{$data["proses_count"]}} Lead - On Prosses</th>
						<th></th>
						<td></td>
					</tr>
					@if($data["open_count"] != 0)
					<tr>
						<th>{{$data["open_count"]}} Lead - OPEN Phase</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<th valign="top">Detail</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<td colspan="3">
							<ul>
								@foreach($data["open_detail"] as $open)
								<li>
									<span style="font-family: 'Lucida Console', Monaco, monospace;">
										<a href="https://app.sinergy.co.id/detail_project/{{$open->lead_id}}">{{$open->lead_id}}</a> 
										[{{str_limit($open->brand_name, 20)}}]
									</span>
									 - {{$open->opp_name}}
									</li>
								@endforeach
							</ul>
						</td>
					</tr>
					@endif
					@if($data["sd_count"] != 0)
					<tr>
						<th>{{$data["sd_count"]}} Lead - SD Phase</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<th valign="top">Detail</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<td colspan="3">
							<ul>
								@foreach($data["sd_detail"] as $sd)
								<li>
									<span style="font-family: 'Lucida Console', Monaco, monospace;">
										<a href="https://app.sinergy.co.id/detail_project/{{$sd->lead_id}}">{{$sd->lead_id}}</a> 
										[{{str_limit($sd->brand_name, 20)}}]
									</span>
									 - {{$sd->opp_name}}
									</li>
								@endforeach
							</ul>
						</td>
					</tr>
					@endif
					@if($data["tp_count"] != 0)
					<tr>
						<th>{{$data["tp_count"]}} Lead - TP Phase</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<th valign="top">Detail</th>
						<th></th>
						<td></td>
					</tr>
					<tr>
						<td colspan="3">
							<ul>
								@foreach($data["tp_detail"] as $tp)
								<li>
									<span style="font-family: 'Lucida Console', Monaco, monospace;">
										<a href="https://app.sinergy.co.id/detail_project/{{$tp->lead_id}}">{{$tp->lead_id}}</a> 
										[{{str_limit($tp->brand_name, 20)}}]
									</span>
									 - {{$tp->opp_name}}
								</li>
								@endforeach
							</ul>
						</td>
					</tr>
					@endif
				</table>
			</div>
			<p style="font-size: 16px">
				Please immediately check back on those leads. If there are further progress updates, please immediately update the intended lead on the <a href="https://app.sinergy.co.id/">SIMS App</a>.
			</p>
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


<!-- <div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$data["to"]}},<br>
		<br>
		Following bellow report mingguan per {{date('D, d F Y')}}<br>
		Diharapkan untuk menjadi perhatian
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>{{$data["proses_count"]}} Lead - On Prosses</th>
			<th></th>
			<td></td>
		</tr>
		@if($data["sd_count"] != 0)
			<tr>
				<th>{{$data["sd_count"]}} Lead - SD Phase</th>
				<th></th>
				<td></td>
			</tr>
			<tr>
				<th valign="top">Detail</th>
				<th></th>
				<td></td>
			</tr>
			<tr>
				<td colspan="3">
					<ul>
						@foreach($data["sd_detail"] as $sd)
						<li>
							<span style="font-family: 'Lucida Console', Monaco, monospace;">
								<a href="https://app.sinergy.co.id/detail_project/{{$sd->lead_id}}">{{$sd->lead_id}}</a> 
								[{{str_limit($sd->brand_name, 20)}}]
							</span>
							 - {{$sd->opp_name}}
							</li>
						@endforeach
					</ul>
				</td>
			</tr>
		@endif
		@if($data["tp_count"] != 0)
		<tr>
			<th>{{$data["tp_count"]}} Lead - TP Phase</th>
			<th></th>
			<td></td>
		</tr>
		<tr>
			<th valign="top">Detail</th>
			<th></th>
			<td></td>
		</tr>
		<tr>
			<td colspan="3">
				<ul>
					@foreach($data["tp_detail"] as $tp)
					<li>
						<span style="font-family: 'Lucida Console', Monaco, monospace;">
							<a href="https://app.sinergy.co.id/detail_project/{{$tp->lead_id}}">{{$tp->lead_id}}</a> 
							[{{str_limit($tp->brand_name, 20)}}]
						</span>
						 - {{$tp->opp_name}}
					</li>
					@endforeach
				</ul>
			</td>
		</tr>
		@endif	
	</table>
	<p>
		Mohon untuk segera di periksa kembali terhadap lead tersebut. Bila ada update progres lebih lanjut harap segera update lead yang di maksud pada <a href="https://app.sinergy.co.id/">SIMS App</a><br>
		<br>
		Jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.<br>
		Demikian yang dapat kami sampaikan
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Tech - Dev</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 – Indonesia |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div> -->