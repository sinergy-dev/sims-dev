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
                        PT. Sinergy Informasi Pratama<br>
                        Gedung Inlingua<br>
                        Jl. Puri Kencana Blok K6 No.2M-2L<br>
                        Puri Indah-Kembangan <br>
                        Jakarta Barat 11610<br>
                    </td>
                    <td style="text-align: center; padding-bottom: 5px; padding-right: 40px;">
                        <img src="/img/sippng.png" style="width:160px;height:80px;"/><br>
                    </td>
                    <td style="text-align: center;">
                        Phone   :    62-21- 583 555 99<br>
                        Fax     :    62-21- 583 551 88<br>
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
                        : {{$datas->to}}<br />
                        : {{$datas->address}}<br />
                        : {{$datas->telp}}<br />
                        : {{$datas->fax}}<br />
                        : {{$datas->att}}<br />
                        : {{$datas->from}}<br>
                        : {{$datas->subj}}
                    </td> 
                    <td style="font-size: 6pt; width: 5%;padding-left:5px;">
                        Date  <br><br>
                        Ref   <br><br>
                        IDP   <br>
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
                    <th style="width: 5%; text-align: center">Serial Number</th>
                </tr>
            </thead>
            <tbody class="a">
                <?php $no = 1;?>
                @foreach($produk as $key => $data)
                <tr>
                    <td style="text-align: center; border : 1px solid black;">@if($key == 0 || $produk[$key]->nama != $produk[$key -1]->nama){{$no++}}@endif</td>
                    <td style="border : 1px solid black; padding-left:5px;">@if($key == 0 || $produk[$key]->nama != $produk[$key -1]->nama){{$data->nama}}@endif</td>
                    <td style="border : 1px solid black; padding-left:5px;">@if($key == 0 || $produk[$key]->ket != $produk[$key -1]->ket){{$data->ket}}@endif</td>
                    <td style="text-align: center; border : 1px solid black;">@if($key == 0 || $produk[$key]->qty != $produk[$key -1]->qty){{$data->qty}}@endif</td>
                    <td style="text-align: center; border : 1px solid black;">@if($key == 0 || $produk[$key]->kg != $produk[$key -1]->kg){{$data->kg}}@endif</td>
                    <td style="text-align: center; border : 1px solid black;">@if($key == 0 || $produk[$key]->vol != $produk[$key -1]->vol){{$data->vol}}@endif</td>
                    <td style="text-align: center; border : 1px solid black;">{{$data->serial_number}}</td>
                </tr>
                
                @endforeach
            </tbody>
        </table><br>
        <table style="font-size: 6pt; width: 100%;">
            <tbody>
                <tr style="text-align: center;">
                    <td style="width: 30%;">
                        Sender : <br><br><br><br><br>
                        <i>Angga Setiawan</i><br>
                        <u style="text-decoration: overline;">PT. Sinergy Informasi Pratama</u>
                    </td>
                    <td style="width: 30%;">
                        Warehouse : <br><br><br><br><br>
                        <i>Rizki Nugroho</i><br>
                        <u style="text-decoration: overline;">PT. Sinergy Informasi Pratama</u>
                    </td>
                    <td style="width: 30%;">
                        Receiver : <br><br><br><br><br><br>
                        <u style="text-decoration: overline;">{{$datas->to}}</u>
                    </td>
                    <!-- 
                    <td style="width: 30%;">
                        <p style="margin-left: -20px">Sender : </p><br><br><br><br><br><br>
                        <div style="border-bottom: 0.05px solid;width: 100px;margin-left: 60px">Angga Setiawan</div>
                        <div style="border-top: 0.05px;width: 100%;margin-left: 60px">PT. Sinergy Informasi Pratama</div>
                    </td>
                    <td style="width: 30%;">
                        <p style="margin-left:-20px">Warehouse : </p><br><br><br><br><br><br>
                        <div style="border-bottom: 0.05px;width: 100px;margin-left: 60px">Rizki Nugroho</div>
                        <div style="border-top: 0.05px solid;width: 100%;margin-left: 60px">PT. Sinergy Informasi Pratama</div>
                    </td>
                    <td style="width: 30%;">
                        <p style="margin-left:-20px">Receiver : </p><br><br><br><br><br><br>
                        <div style="border-bottom: 0.05px;width: 100px;margin-left: 60px"></div>
                        <div style="border-top: 0.05px solid;width: 100%;margin-left: 60px">{{$datas->to}}</div>
                    </td> -->
                    <!-- <td style="width: 30%;">
                        <p style="margin-left: 30px;">Receiver : </p><br><br><br><br><br><br>
                        <div style="border-top: 0.05px solid;width: 150px;margin-left: 60px;margin-top:20px">{{$datas->to}}</div>
                    </td> -->
                </tr>
            </tbody>
        </table> 
        <br><br><br>
        <p>NOTE : Please check and verify that all the goods received in appropriate conditions prior endorsing this document.<br>
          In case you do not receive this form clearly and/or completely, please notify us through the above fax/phone numbers immediately.</p>
    </body>