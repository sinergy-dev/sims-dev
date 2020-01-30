<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear Bu {{$users->name}},
		<br>Mohon bantuan untuk pembuatan ID Project untuk project dengan detail sebagai berikut:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Lead Id</th>
			<th> : </th>
			<td>{{$pid_info->lead_id}}</td>
		</tr>
		<tr>
			<th>Nama Project</th>
			<th> : </th>
			<td>{{$pid_info->opp_name}}</td>
		</tr>
		<tr>
			<th>Nama Sales</th>
			<th> : </th>
			<td>{{$pid_info->name}}</td>
		</tr>
		<tr>
			<th>Amount</th>
			<th> : </th>
			<td>{{$pid_info->amount_pid}}</td>
		</tr>
		<tr>
			<th>No. PO</th>
			<th> : </th>
			<td>{{$pid_info->no_po}}</td>
		</tr>
		<tr>
			<th>No Quote</th>
			<th> : </th>
			<td>{{$pid_info->quote_number2}}</td>
		</tr>
	</table>
	<br>
	Silahkan klik link berikut ini untuk membuat ID Project.<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url($pid_info->url_create)}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Create ID Project
							</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p>
		Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.
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
</div>