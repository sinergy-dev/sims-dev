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

        center > strong::before{
            content: "@";
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
    <img src="{{ asset('image/sims_sangar.png')}}" style="width: 30%; height: 30%">
</div>
<div style="line-height: 1.5em;padding-left: 13px;">
    <div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
        <p style="font-size: 20px">
            <b>Hi {{$receiver->name}}</b>
        </p>
        <p style="font-size: 16px">
            @if($status == 'NEW')
                    Berikut kami lampirkan untuk Quotation terbaru dengan detail sebagai berikut:
            @elseif($status == 'APPROVED')
                    Quotation anda sudah disetujui dan siap untuk dibuatkan dokumen pdf.
            @elseif($detail->status == 'REJECTED')
                    Quotation anda telah ditolak dan ada beberapa perbaikan yang perlu dilakukan sebelum peroses dapat dilanjutkan. Untuk perbaikan bisa dilihat pada note perbaikan dibawah ini.
            @elseif($detail->status == 'EDIT')
                    Berikut kami lampirkan untuk Quotation yang telah diupdate dengan detail sebagai berikut:
            @endif
        </p>
        @if($detail->status == 'REJECTED')
            <div id="bg_ket" style="background-color: #ececec; padding: 10px">
                <b><i>Rejected By {{Auth::User()->name}}</i></b>
                <b><i>Note Perbaikan</i></b>
                <table style="font-size: 12px" class="tableLead">
                    <tr>
                        <td>{!! str_replace("\n","<br>",$notes) !!}</td>
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
                    <td>{{$detail->from}}</td>
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
                <tr>
                    <th>Quotation Type</th>
                    <th> : </th>
                    <td>{{$config->project_type}}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th> : </th>
                    <td>Rp{{number_format((float)$config->nominal, 2, '.', ',')}}</td>
                </tr>
            </table>
        </div>
        <p style="font-size: 16px">
                <b>Untuk detail dari Quotation dapat dilihat dengan mengunjungi halaman dibawah ini</b>
        </p>
            <center><a href="{{url('/sales/quote/detail/'.$detail->id_quote)}}" target="_blank"><button class="button"> Detail Quotation </button></a></center>
        <p style="font-size: 16px">
            Mohon periksa kembali jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id
        </p>
        <p style="font-size: 16px">
            Best Regard,
        </p><br>
        <p style="font-size: 16px">
            Application Development
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