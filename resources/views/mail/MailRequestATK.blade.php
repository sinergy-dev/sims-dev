<style type="text/css">
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	@if($req_atk->status == 'ACCEPT')
	<p>
		Hello Sinergy,
		<br>Hore request ATK Kamu di Approve! Ayo ambil ATK yang kamu butuhkan:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$req_atk->name}}</td>
		</tr>
		<tr>
			<th>Nama Barang</th>
			<th> : </th>
			<td>{{$req_atk->nama_barang}}</td>
		</tr>
		<tr>
			<th>Quantity</th>
			<th> : </th>
			<td>{{$req_atk->qty_akhir}}</td>
		</tr>
		<tr>
			<th>Tanggal req_atk ATK</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{{$req_atk->reason_leave}}</td>
		</tr>
	</table>
	<br>
	@else
	<p>
		Hello Sinergy,
		<br>Berikut request ATK oleh:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$req_atk->name}}</td>
		</tr>
		<tr>
			<th>Nama Barang</th>
			<th> : </th>
			<td>{{$req_atk->nama_barang}}</td>
		</tr>
		<tr>
			<th>Quantity</th>
			<th> : </th>
			<td>{{$req_atk->qty_akhir}}</td>
		</tr>
		<tr>
			<th>Tanggal request ATK</th>
			<th> : </th>
			<td>{{date('d-M-Y', strtotime($req_atk->created_at))}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{{$req_atk->reason_leave}}</td>
		</tr>
	</table>
	<br>
	@endif
	Silahkan klik link berikut ini untuk melihat Detail Request ATK.<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url('/asset_atk')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Request ATK.
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