<style type="text/css">
</style>
<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Hello {{$users->name}},
		@if($req_asset->status == 'PENDING')
		<br><b>Permohonan Peminjaman Asset</b>,
		@elseif($req_asset->status == 'AVAILABLE')
		<br><b>Terima kasih atas Pengembalian Asset</b>,
		@endif
		berikut rinciannya:
	</p>
	<table style="text-align: left;margin: 5px;">		
		@if($req_asset->status == 'PENDING')
		<tr>
			<th style="vertical-align: top">Kategori</th>
			<th style="vertical-align: top"> : </th>
			<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
			<td>{!!$req_asset->note!!}</td>
		</tr>
		<tr>
			<th style="vertical-align: top">Note</th>
			<th style="vertical-align: top"> : </th>
			<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
			<td>{!!$req_asset->keterangan!!}</td>
		</tr>
		<tr>
			<th>Request By</th>
			<th> : </th>
			<!-- <td>Bima Aldi Pratama</td> -->
			<td>{{$req_asset->name}}</td>
		</tr>
		<tr>
			<th>Request Date</th>
			<th> : </th>
			<!-- <td>2021-02-14</td> -->
			<td>{{date('d-M-Y', strtotime($req_asset->created_at))}}</td>
		</tr>
		@elseif($req_asset->status == 'AVAILABLE')
		<tr>
			<th>Name</th>
			<th> : </th>
			<!-- <td>SEPEDA MOTOR HONDA SUPRA X125</td> -->
			<td>{{$req_asset->nama_barang}}</td>
		</tr>
		<tr>
			<th>Description</th>
			<th> : </th>
			<!-- <td>Motor Supra X Biru Digunakan ARIFIN</td> -->
			<td>{{$req_asset->description}}</td>
		</tr>
		<tr>
			<th>Tanggal Peminjaman</th>
			<th> : </th>
			<!-- <td>2021-02-14</td> -->
			<td>{{date('d-M-Y', strtotime($req_asset->tgl_peminjaman))}}</td>
		</tr>
		<tr>
			<th>Tanggal Pengembalian</th>
			<th> : </th>
			<!-- <td>2021-02-14</td> -->
			<td>{{date('d-M-Y', strtotime($req_asset->tgl_pengembalian))}}</td>
		</tr>
		<tr>
			<th>Note</th>
			<th> : </th>
			<td>{!!$req_asset->keterangan!!}</td>
		</tr>
		@endif		
		
		@if($req_asset->status == 'AVAILABLE')
		<tr>
			<th>Lokasi</th>
			<th> : </th>
			<td>{{$req_asset->lokasi}}</td>
		</tr>
		@endif
	</table>
	<br>
	@if($req_asset->status == 'PENDING')
		Silahkan klik link berikut ini untuk melihat Detail Request ATK.<br>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<table cellspacing="0" cellpadding="0">
						<tr>
							<td style="border-radius: 2px;" bgcolor="#ED2939">
								<a href="{{url('/asset_atk')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
									Request Asset
								</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	@endif	
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