<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$data["to"]}},<br>
		<br>
		Berikut report mingguan per {{date('D, d F Y')}}<br>
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
</div>