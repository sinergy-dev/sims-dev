<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 0px solid grey;
		  padding-top: 14px;
		}

		table, th {
			padding-left: 15px;
		}

		#bg_ket {
			border-radius: 10px;
		}

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
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

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/
	</style>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
	<div style="line-height: 1.5em">
		@if($detail->status == 'DRAFT' || $detail->status == 'COMPARING')
			<center><img src="{{ asset('image/draft_pr.png')}}" style="width: 50%; height: 50%"></center>
		@elseif($detail->status == 'CIRCULAR')
			<center><img src="{{ asset('image/sirkulasi_pr.png')}}" style="width: 50%; height: 50%"></center>
		@elseif($detail->status == 'VERIFIED')
			<center><img src="{{ asset('image/verified_pr.png')}}" style="width: 50%; height: 50%"></center>
		@elseif($detail->status == 'REJECT')
			<center><img src="{{ asset('image/rejected_pr.png')}}" style="width: 50%; height: 50%"></center>
		@elseif($detail->status == 'UNAPPROVED')
			<center><img src="{{ asset('image/unapproved_pr.png')}}" style="width: 50%; height: 50%"></center>
		@elseif($detail->status == 'FINALIZED')
			<center><img src="{{ asset('image/finalized_pr.png')}}" style="width: 50%; height: 50%"></center>
		@endif
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				@if($detail->status == 'DRAFT' || $detail->status == 'UNAPPROVED')
					<b>Hi {{$kirim_user->name_receiver}}</b>
				@elseif($detail->status == 'VERIFIED' || $detail->status == 'FINALIZED' || $detail->status == 'REJECT')
					<b>Hi {{$detail->name_issuance}}</b>
				@elseif($detail->status == 'CIRCULAR')
					<b>Hi Team</b>
				@endif
			</p>
			<p style="font-size: 16px">
				@if($detail->status == 'DRAFT' || $detail->status == 'COMPARING')
					Berikut kami lampirkan untuk Draft PR terbaru dengan detail sebagai berikut:
				@elseif($detail->status == 'VERIFIED')
					Untuk Draft PR anda sudah terverifikasi dan siap untuk dibuatkan pembanding kemudian disirkulasikan kepada masing-masing Approver
				@elseif($detail->status == 'REJECT')
					Untuk Draft PR anda sudah terverifikasi dan ada beberapa perbaikan yang perlu dilakukan sebelum peroses dapat dilanjutkan. Untuk perbaikan bisa dilihat pada note perbaikan dibawah ini.
				@elseif($detail->status == 'CIRCULAR')
					PR sudah mulai disirkulasi untuk diperiksa dan disetujui. Kepada masing-masing approver dimohon untuk segera melakukan pemeriksaan dan penyetujuan dari PR yang diajukan.
				@elseif($detail->status == 'FINALIZED')
					Proses sirkulasi PR sudah selesai dilakukan, kemudian PR sudah siap untuk di Finalisasi oleh BCD Procurement.
				@elseif($detail->status == 'UNAPPROVED')
					Untuk Draft PR anda sudah terverifikasi dan ada beberapa perbaikan yang perlu dilakukan sebelum peroses dapat dilanjutkan. Untuk perbaikan bisa dilihat pada note perbaikan dibawah ini.
				@endif
			</p>
			@if($detail->status == 'CIRCULAR')
			<div id="bg_ket" style="background-color: #ffffff; padding: 10px">
				<center><b><i>List Approver</i></b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					@foreach($detail_approver as $data)
					<tr>
						<th>{{$data->position}}</th>
						<th></th>
						<td>{{$data->name}}</td>
					</tr>
					@endforeach
				</table>
			</div>
			<br>
			@endif
			@if($detail->status == 'REJECT')
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<b><i>Note Perbaikan</i></b>
				<table style="font-size: 12px" class="tableLead">
					<tr>
						<td>{!! str_replace("\n","<br>",$detail->activity) !!}</td>
					</tr>
				</table>
			</div>
			<br>
			@endif
			@if($detail->status == 'UNAPPROVED')
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<b><i>Rejected By {{Auth::User()->name}}</i></b>
				<table style="font-size: 12px" class="tableLead">
					<tr>
						<td>{{$detail->activity}}</td>
					</tr>
				</table>
			</div>
			<br>
			@endif
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<center><b></b></center>
				<table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
					<tr>
						<th class="tb_ket">From</th>
						<th> : </th>
						<td>{{$detail->name_issuance}}</td>
					</tr>
					<tr>
						<th>To</th>
						<th> : </th>
						<td>{{$detail->to}}</td>
					</tr>
					<tr>
						<th>Attention</th>
						<th> : </th>
						<td>{{$detail->attention}}</td>
					</tr>
					<tr>
						<th>Subject</th>
						<th> : </th>
						<td>{{$detail->title}}</td>
					</tr>
					@if($detail->status == 'CIRCULAR')
					<tr>
						<th>Next Approver</th>
						<th> : </th>
						<td>{{$next_approver}}</td>
					</tr>
					@else
					<tr>
						<th>Grand Total</th>
						<th> : </th>
						<td>Rp{{number_format($detail->nominal, 2, ',', '.')}}</td>
					</tr>
					@endif
				</table>
			</div>
			<p style="font-size: 16px">
				@if($detail->status == 'DRAFT')
					<b>Untuk detail dari Draft PR dapat dilihat dengan mengunjungi halaman dibawah ini</b>
				@elseif($detail->status == 'VERIFIED')
					<b>Harap menunggu email pemberitahuan selanjutnya untuk proses pembandingan dan sirkulasi PR.</b>
				@elseif($detail->status == 'CIRCULAR')
					<b>Dimohon untuk Approver selanjutnya untuk memeriksa serta memastikan PR sudah sesuai. Jika dirasa tidak ada kesalahan dimohon untuk segera Approve PR tersebut.</b>
				@elseif($detail->status == 'FINALIZED')
					Dimohon untuk menantikan email finalisasi yang dikirim oleh BCD Procruement kepada Finance dengan lampiran yang sudah disiapkan.
				@endif
				
			</p>
			@if($detail->status == 'DRAFT')
				<center><a href="{{url('/admin/draftPR',$detail->id)}}?status=saved" target="_blank"><button class="button"> Detail Draft PR </button></a></center>
			@elseif($detail->status == 'REJECT')
				<center><a href="{{url('/admin/draftPR',$detail->id)}}?status=reject" target="_blank"><button class="button"> Detail Draft PR </button></a></center>
			@elseif($detail->status == 'UNAPPROVED')
				<center><a href="{{url('/admin/draftPR',$detail->id)}}?status=revision" target="_blank"><button class="button"> Detail Draft PR </button></a></center>
			@elseif($detail->status == 'CIRCULAR')
				<center><a href="{{url('/admin/detail/draftPR',$detail->id)}}" target="_blank"><button class="button"> Detail Draft PR </button></a></center>
			@endif
			<p style="font-size: 16px">
				Mohon periksa kembali jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id
			</p>
			<p style="font-size: 16px">
				Best Regard,
			</p><br>
			<p style="font-size: 16px">
				BCD - Dev
			</p>
		</div>
	</div>
</body>
<footer style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center><i class="fa fa-phone"></i>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>