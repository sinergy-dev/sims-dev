<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Guarantee Bank</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style type="text/css" media="print">
            div.page
            {
                page-break-after: always;
                page-break-inside: avoid;
            }

            p {
                font-family: "Times New Roman";
                font-size: 11pt;
            }

            table {
                font-family: "Times New Roman";
                font-size: 11pt;
            }
        </style>
    </head>
    <body>
        <div class="page">
            <img class="pull-right" src="/img/sip.png" style="width:170px;height:70px;"/><br><br><br><br>
            <p>
                Tanggal &nbsp&nbsp&nbsp&nbsp: {{$created_at}}<br>
                Surat No. &nbsp&nbsp: {{$datas->no_letter}}<br><br>
                Kepada Yth:<br>
                PT. Bank Mandiri (Persero) TBK<br>
                Cabang Puri Indah<br>
                Up. Ibu Endang Setiasih<br><br>
                Perihal : Surat Permintaan {{$datas->jenis}}<br><br>
                Dengan Hormat, <br><br>
                Bersama ini kami mohon agar dapat dibuatkan surat Garansi Bank {{$datas->jenis}} sbb: <br>
            </p>
            <table>
                <tr>
                    <td width="25%">
                        Nama Perusahaan Pemohon
                    </td>
                    <td width="75%">
                        : {{$datas->perusahaan}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Alamat
                    </td>
                    <td width="75%">
                        : {{$datas->alamat}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Pejabat yang berurusan
                    </td>
                    <td width="75%">
                        : Rony Cahyadi
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Jenis Jaminan 
                    </td>
                    <td width="75%">
                        : {{$datas->jenis}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Nilai Jaminan 
                    </td>
                    <td width="75%">
                        : {{number_format($datas->nominal, 2, ',', '.')}}-
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Jangka Waktu Jaminan
                    </td>
                    <td width="75%">
                        : {{$tgl_mulai}} sampai {{$tgl_selesai}} ({{$datas->jangka_waktu}} hari)
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Jenis Proyek
                    </td>
                    <td width="75%">
                        : {{$datas->nama_proyek}}
                    </td>
                </tr>
            </table><br>
            <p>
                {{$datas->jenis}} ini kami perlukan untuk melengkapi {{$datas->nama_proyek}}<br><br>
                Demikian surat permohonan ini kami sampaikan, atas perhatian dan bantuannya kami ucapkan terima kasih.<br><br>
                Hormat kami,<br>
                PT. Sinergy Informasi Pratama<br><br><br><br><br>
                <u><b>Rony Cahyadi</b></u><br>
                Direktur Utama
            </p>
            <img style="padding-top: 55px;" src="/img/footer2.PNG"/>
        </div>
        <div class="page">
            <img class="pull-right" src="/img/sip.png" style="width:170px;height:70px; "/><br><br><br><br>
            <h4 style="text-align: center;font-family: Times New Roman"><b>SURAT KUASA</b></h4><br>
            <table>
                <tr>
                    <td width="75%">
                        No : {{$datas->no_letter}}
                    </td>
                    <td width="50%">
                        Tanggal : {{$created_at}}
                    </td>
                </tr>
            </table><br>
            <p>
                Yang bertanda tangan di bawah ini :<br>
            </p>
                
            <table>
               <tr>
                    <td width="25%">
                        Nama
                    </td>
                    <td width="75%">
                        : Rony Cahyadi
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Jabatan
                    </td>
                    <td width="75%">
                        : Direktur Utama
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Nama Perusahaan
                    </td>
                    <td width="75%">
                        : PT. Sinergy Informasi Pratama
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        NPWP
                    </td>
                    <td width="75%">
                        : 02.555.882.6-038.000
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Alamat
                    </td>
                    <td width="100%">
                        : Jl. Puri Kencana Blok K6, No. 2M-2L Kembangan - Jakarta Barat, 11610
                    </td>
                </tr> 
            </table><br>
            <p>
                Bertindak untuk atas nama PT. Sinergy Informasi Pratama sesuai jabatannya, selanjutnya dalam surat kuasa disebut sebagai Pemberi Kuasa, dengan ini memberi kuasa kepada :<br><br>
                <!-- Nama Perusahaan &nbsp&nbsp: PT. Bank Mandiri (Persero) TBK. Cabang Puri Indah<br>
                Alamat &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp : Jl. Puri Indah Raya Ruko Blok I No. 1 Jakarta Barat 11610<br><br> -->
            </p>
            <table>
            	<tr>
                    <td width="25%">
                        Nama Perusahaan
                    </td>
                    <td width="75%">
                        : PT. Bank Mandiri (Persero) TBK. Cabang Puri Indah
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        Alamat
                    </td>
                    <td width="100%">
                        : Jl. Puri Indah Raya Ruko Blok I No. 1 Jakarta Barat 11610
                    </td>
                </tr>
            </table>
            <p>
                Selanjutnya dalam surat kuasa ini disebut sebagai penerima Kuasa, untuk:<br>
                - Cover Blokir Giro/Tabungan*) :<br>
                1. &nbsp&nbsp&nbsp Meblokir rekening Giro/Tabungan *) No. 118 000 633 0889 Atas Nama PT. Sinergy Informasi Pratama Nominal Rp {{number_format($datas->nominal, 2, ',', '.')}}- ({{$kata}}) Mendebit sewaktu-waktu rekening Giro/Tabungan*) No. 118 000 633 0889 atas nama PT. Sinergy Informasi Pratama Nominal Rp {{number_format($datas->nominal, 2, ',', '.')}}- ({{$kata}}) Guna menyelesaikan kewajiban pembayaran Garansi/LC/SKBDN/SLC*) sesuai form aplikasi No.<br>
                2. &nbsp&nbsp&nbsp Sp.JPI/&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp/BG/2019 tanggal &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp 2019<br><br>
                Agar Surat Kuasa ini dipergunakan sebagaimana mestinya.<br><br>
            </p>
            <table>
                <tr>
                    <td width="55%">
                        Pemberi Kuasa <br>
                        PT. Sinergy Informasi Pratama<br><br><br><br><br><br><br><br>
                        Rony Cahyadi<br>
                        Direktur Utama
                    </td>
                    <td width="50%">
                        Penerima Kuasa <br>
                        PT. Bank Mandiri (Persero) Tbk. Jakarta Puri Indah<br><br><br><br><br><br><br><br>
                        Endang Setiasih<br>
                        Branch Manager
                    </td>
                </tr>
            </table>

            <img src="/img/footer2.PNG" style="padding-top: 55px;"/>
        </div>
    </body>

