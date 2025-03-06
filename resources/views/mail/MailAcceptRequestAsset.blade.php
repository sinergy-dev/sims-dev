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

	table tr th{
		padding: 5px
	}

	table tr td{
		padding: 5px
	}

	.span-success{
		background-color: rgb(0, 141, 76);
		color: white;
		border-color: #008d4c;
	}

	.span-progress{
		background-color: #00c0ef;
		color: white;
		border-color: #00c0ef;
	}

	.span-pending{
		background-color: rgb(224, 142, 11);
		color: white;
		border-color: #d58512;
	}

	.span-reject{
		background-color: rgb(224, 142, 11);
		color: white;
		border-color: #ac2925;
	}

	.button {
		border: none;
		color: white;
		padding: 15px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
		background-color: #7868e6;
		border-radius: 4px;
	}
</style>
<body style="display:block;width:900px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		<center><img src="{{asset('image/sims_sangar_2.png')}}" style="width: 30%; height: 30%" readonly></center>
	</div>
	<div style="color: #141414;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;line-height: 1.5em;padding-left: 13px">
	<p>
		Dear {{$users->name}},
	</p>
	@foreach($req_asset['asset'] as $data)		
		@if($data->status == 'ACCEPT')
			<b><i>Request Asset</i></b><h3><span class="span-success">ACCEPTED</span></h3>			
		@elseif($data->status == 'ON PROGRESS')
			<b><i>Request Asset</i></b><h3><span class="span-progress">ON PROGRESS</span></h3>	
			<p>New notes : {{$req_asset['notes']}}</p>
			<span>Detail notes asset dapat dilihat pada aplikasi!</span>		
		@elseif($data->status == 'PENDING')
			<b><i>Request Asset</i></b><h3><span class="span-pending">PENDING</span></h3>
		@else
			<b><i>Request Asset</i></b><h3><span class="span-reject">REJECTED</span></h3>
	@endif	
	<table style="text-align: left;margin: 5px;">
		<tr>
			<th>Nama</th>
			<th> : </th>
			<td>{{$data->nama}}</td>
		</tr>
		<tr>
			<th>Kategori</th>
			<th> : </th>
			<td>{{$data->kategori}}</td>
		</tr>
		<tr>
			<th>Merk</th>
			<th> : </th>
			<td>{{$data->merk}}</td>
		</tr>
		<tr>
			<th>Keperluan</th>
			<th> : </th>
			<td>{{$data->used_for}}</td>
		</tr>
		<tr>
			<th>Deskripsi</th>
			<th> : </th>
			<td>{{$data->link}}</td>
		</tr>
		<tr>
			<th>Reason</th>
			<th> : </th>
			<td>{{$data->reason}}</td>
		</tr>
		<tr>
			<th>Duration</th>
			<th> : </th>
			<td>{{$data->duration}}</td>
		</tr>
	</table>
	@endforeach
	<br>
	<p>Untuk melihat pada halaman asset silahkan klik button</p>
	<a href="{{url('/asset_hr')}}" target="_blank"><button class="button">Detail Asset</button></a>
	<br>
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
</body>