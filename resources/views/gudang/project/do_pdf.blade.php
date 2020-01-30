<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Delivery Order</title>
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
                    <td style="padding-left: 5px;">
                        PT. Multi Solusindo Perkasa<br>
                        Gedung Inlingua Lt. 2<br>
                        Jl. Puri Raya Blok A2/3 No.33-35<br>
                        Puri Indah-Kembangan <br>
                        Jakarta 11610 - Indonesia<br>
                    </td>
                    <td style="text-align: center; padding-bottom: 5px; padding-left: 10px;">
                        <img src="/img/msp.png" style="width:160px;height:80px;"/><br>
                    </td>
                    <td style="text-align: center;">
                        Phone   :    62-21- 583 578449<br>
                        Fax     :    62-21- 583 55188<br>
                        E-mail  :    <a>info@sinergy.co.id</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <h5 style="text-align: center;"><b>DELIVERY ORDER</b></h5>
        <!-- <h5><u>Supplier:</u></h5> -->
        <table class="" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
            <tbody>
                <tr>
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        To      <br>
                        Addr    <br>
                        Telp    <br>
                        Fax     <br>
                        Attn    <br>
                        From    <br>
                        Subj    
                    </td>
                    <td style="font-size: 6pt; width: 50%; border-right: 1px solid;padding-left:5px;">
                        : {{$datas->to_agen}}<br />
                        : {{$datas->address}}<br />
                        : {{$datas->telp}}<br />
                        : {{$datas->fax}}<br />
                        : {{$datas->attn}}<br />
                        : {{$datas->from}}<br>
                        : {{$datas->subject}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        Date  <br><br>
                        Ref   <br><br>
                        ID    <br>
                    </td>
                    <td style="font-size: 6pt; width: 40%; padding-left:5px;">
                        : {{date('l, F d, Y', strtotime($datas->date))}}<br><br>
                        : {{$datas->no_do}} <br><br>
                        : {{$datas->id_project}}<br>
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
                    <th style="width: 30%; text-align: center">Descrpitions</th>
                    <th style="width: 5%; text-align: center">Qty</th>
                    <th style="width: 5%; text-align: center">Kg</th>
                    <th style="width: 5%; text-align: center">Vol</th>
                    <th style="width: 5%; text-align: center">Unit</th>
                </tr>
            </thead>
            <tbody class="a">
                <?php $no = 1; ?>
                @foreach($produks as $produk)
                <tr>
                    <td style="text-align: center; border : 1px solid black;">{{$no++}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->nama}}</td>
                    <td style="border : 1px solid black; padding-left:5px;">{{$produk->description}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->qty}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->kg}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->vol}}</td>
                    <td style="text-align: center; border : 1px solid black;">{{$produk->unit}}</td>
                </tr>
                @endforeach
            </tbody>
        </table><br>
        <table style="font-size: 6pt; width: 100%;">
            <tbody>
                <tr style="text-align: center;">
                    <td style="width: 22%;">
                        <p>Issuer : </p><br><br><br><br><br><br>
                        <div style="border-top: 0.05px solid;width: 100px;margin-left: 30px">PT. Multi Solusindo Perkasa</div>
                    </td>
                    <td style="width: 30%;">
                        <p>Warehouse : </p><br><br><br><br><br>
                        <div style="border-bottom: 0.05px;width: 100px;margin-left: 60px">Indra/Dudun</div>
                        <div style="border-top: 0.05px solid;width: 100px;margin-left: 60px">PT. Multi Solusindo Perkasa</div>
                    </td>
                    <td style="width: 20%;">
                        <p>Delivery : </p><br><br><br><br><br><br>
                        <div style="border-top: 0.05px solid;width: 100px;margin-left: 30px">PT. Multi Solusindo Perkasa</div>
                    </td>
                    <td style="width: 30%;">
                        <p style="margin-left: 60px">Receiver : </p><br><br><br><br><br><br>
                        <div style="border-top: 0.05px solid;width: 150px;margin-left: 60px">{{$datas->to_agen}}</div>
                    </td>
                </tr>
            </tbody>
        </table> 
        <br><br><br>
        <p>NOTE : Please check and verify that all the goods received in appropriate conditions prior endorsing this document.<br>
          In case you do not receive this form clearly and/or completely, please notify us through the above fax/phone numbers immediately.</p>
    </body>