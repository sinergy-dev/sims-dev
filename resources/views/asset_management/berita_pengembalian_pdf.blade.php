<!DOCTYPE html>
<html>
<head>
	<title>Purchase Request</title>
	<style type="text/css">
		.bodyEmail {
			line-height: 1.1;
			font-size: x-small;
			font-family: Lucida Sans Unicode, sans-serif;
		}
		@page { 
			margin: 80px 40px 100px 40px;
		}

		header { 
			position: fixed; 
			top: -60px; 
			left: 0px; 
			right: 0px; 
			height: 50px; 
		}
		footer { 
			position: fixed; 
			bottom: -50px; 
			left: 0px; 
			right: 0px; 
			height: 50px; 
		}
		.table_cover_footer {
			background-image:url("https://eod-api.sifoma.id//storage/image/pdf_image/footer7.png");
			background-repeat: no-repeat;
		}
		.bodyBAST {
			margin: 40px;
		}

		.table_asset th{
			border: 1px solid;
			text-align: center;
		} 

		.table_asset td{
			border: 1px solid;
			text-align: center;
		}
	</style>
</head>
<body class="bodyEmail">
	<header>
		<table style="width: 100%;" >
			<tr>
				<td style="width:20%;"></td>
				<td style="width:30%; text-align: right;">
					<img src="img/logosip.png" style="width:100px">
				</td>
			</tr>
		</table>
	</header>
	<footer>
		<table style="width: 100%" class="table_cover_footer">
			<tr>
				<td colspan="4" style="font: 11px;">
					<b style="color: #7f0f7e">PT. Sinergy Informasi Pratama</b>
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
					<b>Central Office</b>
				</td>
				<td style="width: 68%;font: 9px;">
					: Jl. Puri Kencana Blok K6 No.2M-2L, Kembangan, Jakarta Barat 11610
				</td>
				<td style="width: 5%;font: 9px;">
					Tel
				</td>
				<td style="width: 15%;font: 9px;">
					: +62 21 583 55599
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
					<b>Operational Office</b>
				</td>
				<td style="width: 68%;font: 9px;">
					: Gedung Inlingua Jl. Puri indah Kav. A2/3 No. 33-35, Puri Indah, Jakarta Barat 11610
				</td>
				<td style="width: 5%;font: 9px;">
					Fax
				</td>
				<td style="width: 15%;font: 9px;">
					: +62 21 583 55188
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
				</td>
				<td style="width: 68%;font: 9px;">
				</td>
				<td style="width: 5%;font: 9px;">
				</td>
				<td style="width: 15%;font: 9px;">
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
				</td>
				<td style="width: 68%;font: 9px;">
				</td>
				<td style="width: 5%;font: 9px;">
				</td>
				<td style="width: 15%;font: 9px;">
				</td>
			</tr>
			<tr>
				<td style="width: 10%;font: 10px;" colspan="2">
					<span style="color:white">.</span>
				</td>
				<td style="width: 5%;font: 9px;">
					Web
				</td>
				<td style="width: 15%;font: 9px;">
					: www.sinergy.co.id
				</td>
			</tr>
		</table>
	</footer>	
	<div class="bodyBAST">
		@php
		    use Carbon\Carbon;
		    Carbon::setLocale('id');
		    // Parse the date
		    $date = Carbon::parse($installed_date);

		    // Get day of the week, day number, month, and year
		    $dayOfWeek = $date->translatedFormat('l'); // e.g., "Selasa"
		    $day = $date->day; // 5
		    $month = $date->translatedFormat('F'); // e.g., "November"
		    $year = $date->year; // 2024

		    // Map numbers to words (up to 31 for days of the month)
		    $numberWords = [
		        1 => 'satu', 2 => 'dua', 3 => 'tiga', 4 => 'empat', 5 => 'lima',
		        6 => 'enam', 7 => 'tujuh', 8 => 'delapan', 9 => 'sembilan', 10 => 'sepuluh',
		        11 => 'sebelas', 12 => 'dua belas', 13 => 'tiga belas', 14 => 'empat belas', 15 => 'lima belas',
		        16 => 'enam belas', 17 => 'tujuh belas', 18 => 'delapan belas', 19 => 'sembilan belas', 20 => 'dua puluh',
		        21 => 'dua puluh satu', 22 => 'dua puluh dua', 23 => 'dua puluh tiga', 24 => 'dua puluh empat', 
		        25 => 'dua puluh lima', 26 => 'dua puluh enam', 27 => 'dua puluh tujuh', 28 => 'dua puluh delapan',
		        29 => 'dua puluh sembilan', 30 => 'tiga puluh', 31 => 'tiga puluh satu'
		    ];

		    // Convert day number to words
		    $dayInWords = $numberWords[$day] ?? $day; // Fallback to numeric if out of bounds
		@endphp

		<h3 style="text-align: center;"><b>BERITA SERAH TERIMA ASSET PERUSAHAAN <br> PT SINERGY INFORMASI PRATAMA</b></h3>

		<p>Kami yang bertanda tangan di bawah ini, pada hari {{ $dayOfWeek }} tanggal {{ $day }} ({{ $dayInWords }}) bulan {{ $month }} Tahun {{ $year }}</p>

		<table style="padding-left: 30px;">
			<tr>
				<td style="width:15%">Nama</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{$pihak_pertama->name}}</td>
			</tr>
			<tr>
				<td style="width:15%">NIK</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{$pihak_pertama->nik}}</td>
			</tr>
			<tr>
				<td style="width:15%">Position</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{$pihak_pertama->departement}}</td>
			</tr>
			<tr>
				<td style="width:15%">No HP</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{rtrim($pihak_pertama->phone, '_')}}</td>
			</tr>
			<tr>
				<td style="width:15%">Tanggal Masuk</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{$pihak_pertama->entry_date}}</td>
			</tr>
			<tr>
				<td style="width:15%">Atasan</td>
				<td style="padding-left: 20px;">:</td>
				<td>{{$atasan_pp->name}}</td>
			</tr>
		</table>

		<p>Selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></p>

		<table style="padding-left: 30px;">
			<tr>
				<td style="width:15%">Nama</td>
				<td style="padding-left: 60px;">:</td>
				<td>{{$pihak_kedua->name_pk}}</td>
			</tr>
			<tr>
				<td style="width:15%">NIK</td>
				<td style="padding-left: 60px;">:</td>
				<td>{{$pihak_kedua->nik}}</td>
			</tr>
			<tr>
				<td style="width:15%">Position</td>
				<td style="padding-left: 60px;">:</td>
				<td>{{$pihak_kedua->name}}</td>
			</tr>
			<tr>
				<td style="width:15%">No HP</td>
				<td style="padding-left: 60px;">:</td>
				<td>{{rtrim($pihak_kedua->phone, '_')}}</td>
			</tr>
			<tr>
				<td style="width:15%">Atasan</td>
				<td style="padding-left: 60px;">:</td>
				<td>{{$atasan_pk->name}}</td>
			</tr>
		</table>

		<p>Selanjutnya disebut sebagai <b>PIHAK KEDUA</b></p>

		<p style="text-align:justify;"><b>PIHAK PERTAMA</b> menyerahkan asset kepada <b>PIHAK KEDUA</b>, dan <b>PIHAK KEDUA</b> menyatakan telah menerima barang dari <b>PIHAK PERTAMA</b> berupa :</p>
		
		@php
        	$data = $list_asset_request->first();
    	@endphp
		
		@if($data->category === 'Vehicle')
			
			<table style="width: 100%;border-collapse: collapse;border: 1px solid;" class="table_asset">
				<thead>
					<tr>
						<th>No</th>
						<th>Kategori Asset</th>
						<th>Merk - Type <br> (Tahun)</th>
						<th>No. Polisi</th>
						<th>No. Rangka</th>
						<th>No. Mesin</th>
						<th>Kondisi Asset</th>
					</tr>
				</thead>
				<tbody>
					@foreach($list_asset_request as $key => $data)
						@php
							$nomor_rangka = '';
							$nomor_mesin = '';

							if(preg_match('/Nomor Rangka\s*:\s*([^\s]+)/', $data->spesifikasi, $matches)){$nomor_rangka = $matches[1];}
							if(preg_match('/Nomor Mesin\s*:\s*([^\s]+)/', $data->spesifikasi, $matches)){$nomor_mesin = $matches[1];}
						@endphp
					<tr>
						<td>{{++$key}}</td>
						<td>{{$data->category}}</td>
						<td>{{$data->merk}}</td>
						<td>{{$data->serial_number}}</td>
						<td>{{$nomor_rangka}}</td>
						<td>{{$nomor_mesin}}</td>
						<td>{!! nl2br(e($data->notes)) !!}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<p style="text-align:justify;">Demikian berita acara serah terima asset ini dibuat oleh kedua belah pihak. Sejak
			penandatanganan Berita Acara ini maka asset tersebut menjadi tanggung jawab <b>PIHAK KEDUA</b>
			untuk memelihara dan merawatnya dengan baik, serta di pergunakan untuk keperluan (tempat di mana asset tersebut itu dibutuhkan).</p>

		@else
			<table style="width: 100%;border-collapse: collapse;border: 1px solid;" class="table_asset">
					<thead>
						<tr>
							<th>No</th>
							<th>Kategori Asset</th>
							<th>Merk - Type</th>
							<th>Accessories</th>
							<th>Serial Number</th>
							<th>Jumlah</th>
							<th>Kondisi Asset</th>
						</tr>
					</thead>
					<tbody>
						@foreach($list_asset_request as $key => $data)
						<tr>
							<td>{{++$key}}</td>
							<td>{{$data->category}}</td>
							<td>{{$data->merk}}</td>
							<td>{{$data->accessoris}}</td>
							<td>{{$data->serial_number}}</td>
							<td>1</td>
							<td>{!! nl2br(e($data->notes)) !!}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			<p style="text-align:justify;">Demikian berita acara serah terima asset ini dibuat oleh kedua belah pihak. Sejak
			penandatanganan Berita Acara ini maka asset tersebut menjadi tanggung jawab <b>PIHAK KEDUA</b>
			untuk memelihara dan merawatnya dengan baik, serta di pergunakan untuk keperluan (tempat di mana asset tersebut itu dibutuhkan).</p>
		@endif

		<br><br>
		<div style="width: 100%; margin-bottom: 20px;">
		  <div style="float: left; text-align: center; width: 45%;">
		    <h4>PIHAK PERTAMA</h4>
		  </div>
		  <div style="float: right; text-align: center; width: 45%;">
		    <h4>PIHAK KEDUA</h4>
		  </div>
		</div>

		<div style="width: 100%; margin-top: 50px;">
		  <div style="float: left; text-align: center; width: 45%;">
		    <img src="{{$pihak_pertama->ttd}}" style="width: 100px; height: 100px;">
		    <h4 style="margin-top: 10px;">{{$pihak_pertama->name}}</h4>
		  </div>
		  <div style="float: right; text-align: center; width: 45%;">
		    <img src="{{$pihak_kedua->ttd}}" style="width: 100px; height: 100px;">
		    <h4 style="margin-top: 10px;">{{$pihak_kedua->name_pk}}</h4>
		  </div>
		</div>
	</div>
</body>
</html>