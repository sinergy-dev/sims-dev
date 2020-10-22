<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p>
		Dear {{$users->name}},
		@if($max->date_max == '1')
		Batas waktu peminjaman aset kamu akan berakhir besok.
		@else
		Batas waktu pengembalianmu sudah lewat. Segera kembalikan aset yang kamu pinjam ya.
		@endif
		<br>Berikut reminder untuk waktu pengembalian asset yang kamu pinjam:
	</p>
	<table style="text-align: left;margin: 5px;">
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
			<th>Lokasi Peminjaman</th>
			<th> : </th>
			<td>{{$peminjaman->keterangan}}</td>
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