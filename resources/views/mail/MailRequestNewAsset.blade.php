<style type="text/css">
	.tableBarang{
		border-collapse: collapse;
		width: 100%;
	}
	.tableBarang tr th{
		border: 1px solid #ddd;;
		text-align:left;
		padding: 5px
	}

	.tableBarang tr td{
		border: 1px solid #ddd;;
		text-align:left;
		padding: 5px
	}
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Hello {{$users->name}},
	</p>
	<b><i>Request New Asset</i></b>, berikut rinciannya:
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Request By</th>
			<th> : </th>
			<!-- <td>Bima Aldi Pratama</td> -->
			<td>{{$req_asset['nama_peminjam']}}</td>
		</tr>
		<tr>
			<th>Request Date</th>
			<th> : </th>
			<!-- <td>2021-02-14</td> -->
			<td>{{date('d-M-Y', strtotime($req_asset['request_date']))}}</td>
		</tr>
	</table>
	<table style="text-align: left;margin: 5px;" class="tableBarang">
		<tr style="border: solid 1px">
			<th width="40%">Name / Merk</th>
			<th width="5%">Qty</th>
			<th width="55%">Note(link)</th>			
		</tr>
		@foreach($req_asset['insertdata'] as $data)
		<tr>
			<td>{{$data['nama']}} {{$data['merk']}}</td>
			<td>{{$data['qty']}}</td>
			<td style="color: blue">{!!substr($data['link'],0,35)!!}...</td>
		</tr>
		@endforeach	
	</table>
	<br>
	Silahkan klik link berikut ini untuk melihat Detail Request Asset.<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url('/asset_hr')}}#request_asset" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Request Asset
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