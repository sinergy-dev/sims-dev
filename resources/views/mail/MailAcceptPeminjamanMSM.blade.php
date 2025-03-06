<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$users->name}},
		<br>
		@if($peminjaman->status == 'REJECT')
		Maaf peminjaman barang kamu di tolak.
		@else
			@if($peminjaman->status == 'RETURN')
			Terima kasih. Pengembalian barang anda dengan nomor transaksi peminjaman <b><i>{{$peminjaman->no_peminjaman}}</i> telah selesai</b>, Berikut detail pengembalian barang anda :
			@else
			Peminjaman barang anda telah disetujui dengan nomor transaksi <b><i>{{$peminjaman->no_peminjaman}}</i></b>, Berikut detail peminjaman barang anda :
			@endif
		@endif
	</p>
	@if($peminjaman->status != 'REJECT')
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
		@if($peminjaman->status == 'RETURN')
		<tr>
			<th>Tanggal Pengembalian (Actual)</th>
			<th> : </th>
			<td><b>{{date('d/m/Y h:i:s A', strtotime($peminjaman->updated_at))}}</b></td>
		</tr>
		@endif
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
			<th>Total Barang</th>
			<th> : </th>
			<td>
				{{$total_barang}}
			</td>
		</tr>
		<tr>
			<th>Nama Barang/[Serial Number]</th>
			<th> : </th>
			<td>
				@foreach($barang as $data)
				<tr>
					<td> 
					&nbsp&nbsp- {{$data->nama_barang}}/[<b>{{$data->serial_number}}</b>]
					</td>
				</tr>
				@endforeach
			</td>
		</tr>
		<tr>
			<th>Keperluan</th>
			<th> : </th>
			<td>{{$peminjaman->keperluan}}</td>
		</tr>
		<tr>
			@if($peminjaman->status == 'RETURN')
			<th>Lokasi Pengembalian</th>
			<th> : </th>
			<td>{{$peminjaman->location_return}}</td>
			@else
			<th>Lokasi Peminjaman</th>
			<th> : </th>
			<td>{{$peminjaman->keterangan}}</td>
			@endif
			
		</tr>
	</table>
	<br>
	<br>
	@else
	<b>
		Reject notes : 
	</b><br>
	{{$peminjaman->note}}!
	@endif
	<p>
		Mohon di periksa kembali, jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id.
	</p>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Application Development</i></h5>
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