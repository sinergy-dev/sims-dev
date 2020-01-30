<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Purchase Order</title>
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
            }
        </style>
    </head>
    <body>
        <table style="font-size: 6pt; width: 100%; border-bottom: 2px solid">
            <tbody>
                <tr>
                    <td>
                        PT. Sinergy Informasi Pratama<br>
                        Gedung Inlingua Lt. 2<br>
                        Jl. Puri Raya Blok A2/3 No.33-35<br>
                        Puri Indah-Kembangan <br>
                        Jakarta 11610 - Indonesia
                    </td>
                    <td style="text-align: center; padding-bottom: 5px; padding-left: 5px;">
                        <img src="/img/sip.png" style="width:180px;height:80px;"/>
                    </td>
                    <td style="text-align: center;">
                        Phone   :    62 21 583 555 99<br>
                        Fax     :    62 21 583 55 188<br>
                        E-mail  :    <a>info@sinergy.co.id</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 style="text-align: center;"><b>PURCHASE ORDER</b></h5>
        <table class="" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <tbody>
                <tr>
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        To      <br>
                        Addr    <br>
                        Telp    <br>
                        Fax     <br>
                        Email   <br>
                        Attn    <br>
                        Subject
                    </td>
                    <td style="font-size: 6pt; width: 50%; border-right: 1px solid;padding-left:5px;">
                        : {{$datas->to_agen}}<br>
                        : {{$datas->address}}<br>
                        : {{$datas->telp}}<br>
                        : {{$datas->fax}}<br>
                        : {{$datas->email}}<br>
                        : {{$datas->attention}}<br>
                        : {{$datas->subject}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        Date  <br><br>
                        PO   <br>
                        PR    <br>
                        PID   <br>
                    </td>
                    <td style="font-size: 6pt; width: 40%; padding-left:5px;">
                        : {{date('l, F d, Y', strtotime($datas->date))}}<br><br>
                        : {{$datas->no_po}} <br>
                        : {{$datas->no_pr}}<br>
                        : {{$datas->project_id}}<br>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%; font-size: 6pt; border-collapse: collapse; border: 1px solid black;">
            <thead style="background-color: #e2e2e2">
                <tr>
                    <th style="width: 5%; text-align: center">No</th>
                    <th style="width: 15%; text-align: center">Product</th>
                    <th style="width: 30%; text-align: center">Descrpition</th>
                    <th style="width: 5%; text-align: center">Qty</th>
                    <th style="width: 10%; text-align: center">Price</th>
                    <th style="width: 10%; text-align: center">Total Price</th>
                </tr>
            </thead>
            <tbody class="a">
                <?php $no = 1; ?>
                @foreach($produks as $produk)
                <tr>
                    <td style="text-align: center; border : 1px solid black;">{{$no++}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->name_product}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->description}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->qty}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->nominal, 2, ',', '.')}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->total_nominal, 2, ',', '.')}}</td>
                </tr>
                @endforeach
            </tbody>
            @if($datas->ppn == 'YA')
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">Sub Total</th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$total_amount}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">PPn 10%</th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$ppn}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">TOTAL</th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$grand_total2}}</th>
                </tr>
            </tfoot>
            @elseif($datas->ppn == 'TIDAK')
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">Sub Total</th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$total_amount}}</th>
                </tr>
            </tfoot>
            @endif
        </table> <br><br>
        <p><b>Terms & Condition:</b></p>
        <p><b>{!! nl2br($datas->term) !!}</b></p>
        <table style="width: 95%; font-size: 6pt; border-collapse: collapse; border: 1.5px solid black;">
            <tbody>
                <tr>
                    <td style="text-align: center; width: 8%;"><b>Note:</b></td>
                    <td style="width: 87%;  border-right: 1px solid;padding-left:5px;">I<b>nvoice hanya diterima oleh Bp. Franki Asido, Jika Invoice tidak diterima oleh Bp. Franki Asido, kami anggap belum menerima Invoice.<br>
                        The Invoice is only accepted by Mr. Franki Asido, If invoice isn't received Mr. Franki Asido, we assume have not received the invoice.</b>
                    </td>
                </tr>
            </tbody>
        </table><br>
        <p> <b>
            Dokumen yang harus dilampirkan saat Penagihan :<br>
            1. Invoice asli + Copy Invoice ( 2 Lembar )<br>
            2. Faktur Pajak (jika menggunakan PPN) asli + Copy Faktur Pajak ( 2 Lembar )<br>
            3. Enofa (Jika ada Faktur Pajak) - ( 2 Lembar )<br>
            4. Delivery Order / Berita Acara serah terima (Asli) + Copy ( 1 Lembar )<br>
            5. Copy PO SIP + ( 2 Lembar Copy PO MSP)<br>
            6. 3 hari kerja dari tanggal invoice, paling lambat diterima GA MSP di bulan yang sama<br>
            7. Pastikan bulan tanggal invoice dan bulan tanggal GA menerima invoice, ada dibulan yang sama<br>
            8. Description di Invoice & Faktur sama dengan PO, jika tidak sama Invoice tidak akan diterima<br>
        </b></p>
        <p>If you have any further inquires, please do not hesitate to contact us. We thank you for your kind attention.</p><br><br>
        <table style="font-size: 6pt; width: 100%">
            <tbody>
                <tr>
                    <td style="width: 15%;">
                        Best Regards, <br><br><br><br><br><br>
                        <b><u>Yuliane</u><br>
                        <p>Finance Manager</p></b>
                    </td>
                </tr>
            </tbody>
        </table> 
        <br><br><br>
        <p>NOTE : In case you do not receive this document clearly and completely, please notify us immediately.</p>
    </body>