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
        <table style="font-size: 6pt; width: 100%; border-bottom: 2px solid">
            <tbody>
                <tr>
                    <td>
                        PT. Multi Solusindo Perkasa<br>
                        Gedung Inlingua Lt. 2<br>
                        Jl. Puri Kencana Blok K6/ 2L-2M<br>
                        Puri Indah-Kembangan <br>
                        Jakarta 11610 - Indonesia<br>
                    </td>
                    <td style="text-align: center; padding-bottom: 5px; padding-left: 75px;">
                        <img src="/img/msp.png" style="width:180px;height:80px;"/><br>
                    </td>
                    <td style="text-align: center;">
                        Phone   :    62-21- 583 578449<br>
                        Fax     :    62-21- 583 55188<br>
                        E-mail  :    <a>ferry.hartono@sinergy.co.id</a><br>
                                    <a>yuliane@sinergy.co.id</a>
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
                        Attn    <br>
                        Email   <br>
                        From    <br>
                        Subj    
                    </td>
                    <td style="font-size: 6pt; width: 50%; border-right: 1px solid;padding-left:5px;">
                        : {{$datas->to_agen}}<br />
                        : {{$datas->address}}<br />
                        : {{$datas->telp}}<br />
                        : {{$datas->fax}}<br />
                        : {{$datas->attention}}<br />
                        : {{$datas->email}}<br>
                        : {{$datas->name}}<br>
                        : {{$datas->subject}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        Date  <br><br>
                        Ref   <br>
                        PO    <br>
                        DO    <br><br><br>
                        Pages 
                    </td>
                    <td style="font-size: 6pt; width: 40%; padding-left:5px;">
                        : {{date('l, F d, Y', strtotime($datas->date))}}<br><br>
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
                    <th style="width: 5%; text-align: center">MSP Code</th>
                    <th style="width: 15%; text-align: center">Product</th>
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
                    <td style="text-align: center; border : 1px solid black;">{{$produk->msp_code}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->name_product}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->description}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->qty}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->unit}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->nominal, 2, ',', '.')}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->total_nominal, 2, ',', '.')}}</td>
                </tr>
                @endforeach
            </tbody>
            <!-- <tbody class="a">
                <tr>
                    <td style="text-align: center; border : 1px solid black;"></td>
                    <td style="text-align: center; border : 1px solid black;"></td>
                    <td style="border : 1px solid black; padding-left:5px;"></td>
                    <td style="border : 1px solid black; padding-left:5px;"></td>
                    <td style="text-align: center; border : 1px solid black;"></td>
                    <td style="text-align: center; border : 1px solid black;"></td>
                    <td style="text-align: center; border : 1px solid black;"></td>
                    <td style="text-align: center; border : 1px solid black;"></td>
                </tr>
            </tbody> -->
            @if($datas->ppn == 'YA' || $datas->pph == NULL)
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">Sub Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$total_amount}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">PPn 10%</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$ppn}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$grand_total2}}</th>
                </tr>
            </tfoot>
            @elseif($datas->ppn == 'TIDAK' || $datas->pph == NULL)
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">Sub Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$total_amount}}</th>
                </tr>
            </tfoot>
            <!-- @elseif($datas->pph != NULL)
            <tfoot style="background-color: #e2e2e2">
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">Sub Total</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$total_amount}}</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">PPn 10%</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$pph3}}{{$data->pph}}%</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="border : 1px solid black; padding-left:5px;">TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: center">{{$grand_total2}}</th>
                </tr>
            </tfoot> -->
            @endif
        </table> <br><br>
        <!-- <h5><u>Customer:</u></h5>
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
                        : {{date('l, F d, Y', strtotime($datas->date_handover))}}<br><br>
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
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->nominal, 2, ',', '.')}}</td>
                    <td style="text-align: center; border : 1px solid black;">IDR {{number_format($produk->total_nominal, 2, ',', '.')}}</td>
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
        <br> -->
        <p><b>Terms & Condition:</b></p>
        <p>{!! nl2br($datas->terms) !!}</p><br>
        <p>If you have any further inquires, please do not hesitate to contact us. We thank you for your kind attention.</p><br><br>
        <table style="font-size: 6pt; width: 100%">
            <tbody>
                <tr>
                    <td style="width: 15%;">
                        Requested By : <br><br><br><br><br>
                        <u>PMO Division</u><br><br>
                    </td>
                    <td style="width: 35%;">
                        Acknowledge By: <br><br><br><br><br>
                        <u>Dudun</u><br>
                        <i>Admin Gudang</i>
                    </td>
                    <td style="width: 25%;">
                        Approved By : <br><br><br><br><br>
                        <u>Ferry Hartono</u><br>
                        <i>Director</i>
                    </td>
                </tr>
            </tbody>
        </table> 
        <br><br><br>
        <p>NOTE : In case you do not receive this document clearly and completely, please notify us immediately.</p>
    </body>