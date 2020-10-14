<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$admin->name}},
		<br>Mohon bantuan untuk <i>Approving</i> peminjaman barang dengan keterangan berikut:
	</p>
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Tanggal Peminjaman</th>
			<th> : </th>
			<td>{{date('d/m/Y', strtotime($peminjaman->tgl_peminjaman))}}</td>
		</tr>
		<tr>
			<th>Tanggal Pengembalian</th>
			<th> : </th>
			<td>{{date('d/m/Y', strtotime($peminjaman->tgl_pengembalian))}}</td>
		</tr>
		<tr>
			<th>Nama Peminjam</th>
			<th> : </th>
			<td>{{$peminjaman->name}}</td>
		</tr>
		<tr>
			<th>Kategori Barang</th>
			<th> : </th>
			<td>{{$peminjaman->kategori}}</td>
		</tr>
		<tr>
			<th>Keperluan</th>
			<th> : </th>
			<td>{{$peminjaman->keperluan}}</td>
		</tr>
		<tr>
			<th>Lokasi Peminjaman</th>
			<th> : </th>
			<td>{{$peminjaman->keterangan}}</td>
		</tr>
	</table>
	<br>
	<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td style="border-radius: 2px;" bgcolor="#ED2939">
							<a href="{{url('/asset_pinjam#peminjaman')}}" target="_blank" style="padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">
								Peminjaman Barang
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