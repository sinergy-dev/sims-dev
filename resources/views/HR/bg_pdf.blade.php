<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Guarantee Bank</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            h1,h2,h3,h4,p,span,div { 
                font-family: Tahoma; 
            }

            p {
                font-size: 6pt;
            }

            tfoot{
                padding-top: 5px;
                padding-bottom: 5px;
            }

            th{
                  border-right: 1px solid;
                  border-left: 1px solid;
                  border-top: 1px solid;
                  border-bottom: 1px solid;
                  border: 1px solid black;
            }
            .haha{
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <table style="font-size: 6pt; width: 100%;">
            <tbody>
                <tr>
                    <td>
                    </td>
                    <td style="text-align: center; padding-bottom: 5px; padding-left: 10px;">
                        <img src="/img/sip.png" style="width:160px;height:80px;"/><br>
                    </td>
                    <td> 
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%; border: 1px solid black;">
            <tbody>
            	<tr  style="border-bottom: 1px solid black; background-color: #000000; color: white;">
            		<td style="text-align: center; border-bottom: 1px" colspan="4">
            			FORM PEMBUATAN BANK GARANSI
            		</td>
            	</tr>
                <tr> 
                    <td style="width: 25%;padding-left:5px" height="2%">
                    	<br>
                        <b>Kode Proyek</b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                    	<br>
                        <b> : {{$datas->kode_proyek}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b>Nama Proyek</b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br><b> : {{$datas->nama_proyek}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> No Proyek </b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b> : {{$datas->no_proyek}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> Ditujukan kepada </b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b>  </b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> Perusahaan</b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b> : {{$datas->perusahaan}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> Dept/Div </b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b> : {{$datas->division}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> Alamat </b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b> : {{$datas->alamat}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr>
                    <td style="width: 25%;padding-left:5px;" height="2%">
                        <br>
                        <b> Kode Pos </b>
                    </td>
                    <td style="width: 50%; border-right: 1px solid black" colspan="3" height="2%">
                        <br>
                        <b>: {{$datas->kode_pos}}</b>
                    </td>
                    <!-- <td style="border-right: 1px solid black"></td> -->
                </tr>
                <tr style="border-top: 1px solid black">
                	<td width="35%"><b>Jenis</b></td>
                	<td><b>Penerbit</b></td>
                	<td><b>Jangka Waktu</b></td>
                	<td>({{$datas->jangka_waktu}} hari)</td>
                </tr>
                <tr>
                    <td>{{$datas->jenis}}</td>
                    <td>{{$datas->penerbit}}</td>
                    <td>Tgl mulai</td>
                    <td>: {{$tgl_mulai}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Tgl selesai</td>
                    <td>: {{$tgl_selesai}}</td>
                </tr>
                <tr>
                	<td>
                		<b>Dokumen Referensi <i>(copy terlampir)</i></b><br>
                		{{$datas->dok_ref}}<br><br>
                		<b>Valuta</b><br>
                		{{$datas->valuta}}<br><br>
                		<b>Terbilang</b>
                	</td>
                	<td colspan="3">
                		<br>
                		No / Tgl : {{$datas->no_dok}}<br><br>
                		<b>Nominal</b><br>
                		: {{number_format($datas->nominal, 2, ',', '.')}}<br><br>
                		: {{$kata}} rupiah
                	</td>
                </tr>
                <tr style="border-top: 1px solid black">
                	<td><br>
                		<b>Catatan </b><br>
                	</td>
                	<td colspan="3"><br>
                	: {{$datas->note}}<br>
                	</td>
                </tr>
                <tr class="haha" style="background-color: #8c8c8c;">
                	<td style="border-right: 1px solid black; padding-left: 10px;"></td>
                	<td style="border-right: 1px solid black; padding-left: 10px;">Nama</td>
                	<td style="border-right: 1px solid black; padding-left: 10px;">Paraf</td>
                	<td style="padding-left: 10px;">Tanggal</td>
                </tr>
                <tr>
                	<td style="border-right: 1px solid black; padding-left: 10px; background-color: #8c8c8c;">Diminta oleh</td>
                	<td style="border-right: 1px solid black; padding-left: 10px;">{{$datas->name}}</td>
                	<td style="border-right: 1px solid black; padding-left: 10px;"></td>
                	<td style="padding-left: 10px;">{{$created_at}}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <br><br><br>
    </body>

