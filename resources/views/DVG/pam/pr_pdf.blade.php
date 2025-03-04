<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Purchase Request</title>
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
        <table style="font-size: 6pt; width: 100%; border-bottom: 1px solid">
            <tbody>
                <tr>
                    <td>
                        PT. Sinergy Informasi Pratama<br>
                        Gedung Inlingua Lt. 2<br>
                        Jl. Puri Raya Blok A2/3 No.33-35<br>
                        Puri Indah-Kembangan <br>
                        Jakarta 11610 - Indonesia
                    </td>
                    <td style="text-align: center">
                        <img src="img/sip.png" style="width:160px;height:80px;"/>
                    </td>
                    <td style="text-align: center;">
                        <br>
                        Phone   :    62 21 583 555 99<br>
                        Fax     :    62 21 583 55 188<br>
                        E-mail  :    <a>info@sinergy.co.id</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 style="text-align: center;"><b>PURCHASE REQUEST</b></h5>
        <h5><u>Supplier:</u></h5>
        <table class="" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <tbody>
                <tr>
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        To      <br>
                        Addr    <br>
                        Telp    <br>
                        Fax     <br>
                        Email   <br>
                        No.Rek  <br>
                        Attn    <br>
                        From    <br>
                        Subj    
                    </td>
                    <td style="font-size: 6pt; width: 50%; border-right: 1px solid;padding-left:5px;">
                        : {{$datas->to_agen}}<br />
                        : <br />
                        : <br />
                        : <br />
                        : <br />
                        : <br>
                        : <br />
                        : <br>
                        : {{$datas->subject}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        Date  <br><br>
                        Que   <br>
                        Ref   <br>
                        PO    <br>
                        DO    <br><br><br>
                        Pages 
                    </td>
                    <td style="font-size: 6pt; width: 40%; padding-left:5px;">
                        : {{date('l, F d, Y', strtotime($datas->date))}}<br><br>
                        : <br>
                        : {{$datas->no_pr}} <br>
                        : <br>
                        : <br><br><br>
                        : 1 (one) Page
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%; font-size: 6pt; border-collapse: collapse; border: 1px solid black;">
            <thead style="background-color: #e2e2e2">
                <tr>
                    <th style="width: 5%; text-align: center">No</th>
                    <th style="width: 20%; text-align: center">Product</th>
                    <th style="width: 30%; text-align: center">Descrpition</th>
                    <th style="width: 5%; text-align: center">Qty</th>
                    <th style="width: 5%; text-align: center">Unit</th>
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
                    <td style="text-align: center; border : 1px solid black;">Unit</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->nominal,2,',','.')}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->total_nominal,2,',','.')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th style="padding-left:5px;">TOTAL</th>
                    <th></th>
                    <th style="padding-left:5px;">GRAND TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">IDR {{$total_amount}}</th>
                </tr>
            </tfoot>
        </table>
        <h5><u>Customer:</u></h5>
        <table class="" style="width: 100%; border-collapse: collapse; border: 1px solid black; ">
            <tbody>
                <tr>
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        To      <br>
                        Addr    <br>
                        Telp    <br>
                        Fax     <br>
                        Email   <br>
                        No.Rek  <br>
                        Attn    <br>
                        From    <br>
                        Subj    
                    </td>
                    <td style="font-size: 6pt; width: 50%; border-right: 1px solid; padding-left:5px;">
                        : <b>INTERNAL USE</b><br />
                        : <br />
                        : <br />
                        : <br />
                        : <br />
                        : <br>
                        : <br />
                        : <br>
                        : {{$datas->subject}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%; padding-left:5px;">
                        Date  <br><br>
                        Que   <br>
                        Ref   <br>
                        PO    <br>
                        DO    <br><br><br>
                        Pages 
                    </td>
                    <td style="font-size: 6pt; width: 40%; padding-left:5px;">
                        : {{date('l, F d, Y', strtotime($datas->date))}}<br><br>
                        : <br>
                        : {{$datas->no_pr}} <br>
                        : <br>
                        : <br><br><br>
                        : 1 (one) Page
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%; font-size: 6pt; border-collapse: collapse; border: 1px solid black;">
            <thead style="background-color: #e2e2e2">
                <tr style="text-align: center">
                    <th style="width: 5%; text-align: center">No</th>
                    <th style="width: 20%; text-align: center">Product</th>
                    <th style="width: 30%; text-align: center">Descrpition</th>
                    <th style="width: 5%; text-align: center">Qty</th>
                    <th style="width: 5%; text-align: center">Unit</th>
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
                    <td style="text-align: center; border : 1px solid black;">Unit</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->nominal,2,',','.')}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->total_nominal,2,',','.')}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th style="padding-left:5px;">TOTAL</th>
                    <th></th>
                    <th style="padding-left:5px;">GRAND TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">IDR {{$total_amount}}</th>
                </tr>
            </tfoot>
        </table>
        <br>
        <p><b>Terms & Condition:</b></p><br>
        <p>If you have any further inquires, please do not hesitate to contact us. We thank you for your kind attention.</p><br><br>
        <table style="font-size: 6pt; width: 100%">
            <tbody>
                <tr>
                    <td style="width: 25%;">
                        Issued By : <br><br><br><br><br>
                        <u>{{$datas->name}}</u><br><br>
                    </td>
                    <td style="width: 35%;">
                        Acknowledge : <br><br><br><br><br>
                        <u>M. Nabil</u><br>
                        <i>Technical Division Head</i>
                    </td>
                    <td style="width: 25%;">
                        Approved By : <br><br><br><br><br>
                        <u>Rony Cahyadi</u><br>
                        <i>Chief Executive Officer</i>
                    </td>
                    <td style="width: 15%;">
                        Received By : <br><br><br><br><br>
                        <u>Yuliane Fatmasari</u><br>
                        <i>Finance/Accounting</i>
                    </td>
                </tr>
            </tbody>
        </table> 
        <br><br><br>
        <p>NOTE : In case you do not receive this document clearly and completely, please notify us immediately.</p>
    </body>