<style type="text/css">
    .table_cover_footer {
        background-image:url("https://eod-api.sifoma.id//storage/image/pdf_image/footer7.png");
        background-repeat: no-repeat;
    }.

     .table_supplier td, .table_supplier td * {
         vertical-align: top;
         align-content: left;
     }

    table {
        border-collapse: collapse;
    }

    .table_supplier_content th, .table_supplier_content td {
        /*border-collapse: collapse;
        border: 1px solid;*/
        vertical-align: top;
    }

    .table_item_content th, .table_item_content td {
        border-collapse: collapse;border: 1px solid;
    }

</style>
<link rel="stylesheet" href="{{asset('vendor2/font-awesome/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor2/font-awesome/css/font-awesome.css')}}">
<!-- <link rel="stylesheet" href="{{asset('template2/bower_components/font-awesome/css/font-awesome.min.css')}}"> -->
<span style='font-family: Lucida Sans Unicode, sans-serif;'>Dear <b>{{$config->attention}}</b></span>
<br><br>
<span style='font-family: Lucida Sans Unicode, sans-serif;'>Berikut Terlampir Quotation, </span>
<br><br>
<table class="table_supplier" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
    <tr style="vertical-align: top;">
        <td style="width:60%; border: solid black;">
            <table class="table_supplier_content" style="width: 100%;">
                <tr>
                    <th style="text-align: left;">To</th>
                    <th>:</th>
                    <td>{{$config->to}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Email</th>
                    <th>:</th>
                    <td>{{$config->email}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Phone</th>
                    <th>:</th>
                    <td>{{$config->no_telp}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Attention</th>
                    <th>:</th>
                    <td>{{$config->attention}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">From</th>
                    <th>:</th>
                    <td>{{$config->from}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Subject</th>
                    <th>:</th>
                    <td>{{$config->title}}</td>
                </tr>
                <tr>
                    <th style="text-align: left;">Address</th>
                    <th>:</th>
                    <td>
                        {!! $config->address !!}
                    </td>
                </tr>
            </table>
        </td>
        <td style="width:40%; border: solid black;">
            <table class="table_supplier_content" style="width: 100%;">
                <tr>
                    <th>Quote Number</th>
                    <th>:</th>
                    <td>{{$config->quote_number}}</td>
                </tr>
                <tr>
                    <th>Quotation Type</th>
                    <th>:</th>
                    <td>{{$config->project_type}}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>
<table class="table_item_content" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
    @php
        $isPriceList = false;

        foreach ($product as $p){
            if ($p->price_list > 0 || $p->price_list != null){
                $isPriceList = true;
                break;
            }
        }
    @endphp
    <thead>
    <tr style="background-color:#c0c0c0">
        <th style="border-collapse: collapse;border: 1px solid;">No</th>
        <th style="border-collapse: collapse;border: 1px solid;">Product</th>
        <th style="border-collapse: collapse;border: 1px solid;">Description</th>
        <th style="border-collapse: collapse;border: 1px solid;">Qty</th>
        <th style="border-collapse: collapse;border: 1px solid;">Unit</th>
        @if($isPriceList)
            <th style="border-collapse: collapse;border: 1px solid;">Pricelist</th>
            <th style="border-collapse: collapse;border: 1px solid;">Total Pricelist</th>
        @endif
        <th style="border-collapse: collapse;border: 1px solid;">Price</th>
        <th style="border-collapse: collapse;border: 1px solid;">Total Price</th>

    </tr>
    </thead>
    <tbody>
    @php
        $nominalTotal = 0;
    @endphp
    @foreach($product as $key => $eachProduct)
        @php
            $nominalFinal = str_replace(',', '.', $eachProduct->nominal);
            $nominalFinal = floatval($nominalFinal);

            $grandTotalFinal = str_replace(',', '.', $eachProduct->grand_total);
            $grandTotalFinal = floatval($grandTotalFinal);

            $nominalPriceList = str_replace(',', '.', $eachProduct->price_list);
            $nominalPriceList = floatval($nominalPriceList);

            $totalPriceList = str_replace(',', '.', $eachProduct->total_price_list);
            $totalPriceList = floatval($totalPriceList);
        @endphp
        <tr>
            <td style="text-align:center">{{++$key}}</td>
            <td>{{$eachProduct->name}}</td>
            <td>{!! nl2br($eachProduct->description) !!}
            </td>
            <td style="text-align:center">{{$eachProduct->qty}}</td>
            <td style="text-align:center">{{$eachProduct->unit}}</td>
            @if($isPriceList)
                <td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($nominalPriceList,2)}}</td>
                <td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($totalPriceList,2)}}</td>
            @endif
            <td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($nominalFinal,2)}}</td>
            <td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($grandTotalFinal,2)}}</td>
            @php
                $nominalTotal += $eachProduct->grand_total;
            @endphp
        </tr>
    @endforeach
    <tr>
        <th><span style="color: white">-</span></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        @if($isPriceList)
            <th></th>
            <th></th>
        @endif
        <th></th>
        <th></th>
    </tr>
    </tbody>
    <tfoot>

    <tr>
        @php
            $nominalTotalFinal =  str_replace(',', '.', $nominalTotal);
            $nominalTotalFinal = floatval($nominalTotalFinal);

            $nominalGrandTotalFinal = str_replace(',', '.', $config->nominal);
            $nominalGrandTotalFinal = floatval($nominalGrandTotalFinal);
            $dpp = $nominalTotalFinal * 11/12;
            $ppn = $dpp * $config->tax_vat / 100;
        @endphp
        @if($isPriceList)
            <th colspan="8" style="text-align:right">Total</th>
        @else
            <th colspan="6" style="text-align:right">Total</th>
        @endif
        <th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($nominalTotalFinal,2)}}</th>
    </tr>
    @if($config->tax_vat != null)
        <tr>
            @if($config->tax_vat != null)
                <th colspan="8" style="text-align:right">DPP Nilai Lainnya</th>
            @else
                <th colspan="8" style="text-align:right"></th>
            @endif
            @if($config->tax_vat == 11 || $config->tax_vat == 12)
                <th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($dpp,2)}}</th>
            @else
                <th style="text-align:right;font-family:Consolas, monaco, monospace;">0</th>
            @endif
        </tr>
        <tr>
            @if($config->tax_vat != null)
                <th colspan="8" style="text-align:right">PPN {{$config->tax_vat}}%</th>
            @else
                <th colspan="8" style="text-align:right"></th>
            @endif
            @if($config->tax_vat == 11 || $config->tax_vat == 12)
                <th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($ppn,2)}}</th>
            @else
                <th style="text-align:right;font-family:Consolas, monaco, monospace;">0</th>
            @endif
        </tr>
        <tr>
            @if($isPriceList)
                <th colspan="8" style="text-align:right">Grand Total</th>
            @else
                <th colspan="6" style="text-align:right">Grand Total</th>
            @endif
            <th style="text-align:right;font-family:Consolas, monaco, monospace; background-color: yellow">Rp. {{number_format($nominalGrandTotalFinal,2)}}</th>
        </tr>
    @else
        <tr>
            @if($isPriceList)
                <th colspan="8" style="text-align:right">Grand Total</th>
            @else
                <th colspan="6" style="text-align:right">Grand Total</th>
            @endif
            <th style="text-align:right;font-family:Consolas, monaco, monospace; background-color: yellow">Rp. {{number_format($nominalGrandTotalFinal,2)}}</th>
        </tr>
    @endif
    </tfoot>
</table>
<br>
<div style="width: 100%; text-align: justify;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
    <b style="font-family: Lucida Sans Unicode, sans-serif;">Terms & Condition :</b>
    <br>
    <div style="font-family: Lucida Sans Unicode, sans-serif;">
        {!!$config->term_payment!!}
    </div>
</div>
<br>
<br>
<p style="font-family: Lucida Sans Unicode, sans-serif;">If you have any further inqueries, please do not hesitate to contact us. We thank you for your kind attention.</p>
<br>
<br>
<div>
    <span style="font-family: Lucida Sans Unicode, sans-serif;">Best Regards,</span><br>
    <span style="font-family: Lucida Sans Unicode, sans-serif;">{{$config->from}}</span>
</div>
<br>
<br>
<br>
<br>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
