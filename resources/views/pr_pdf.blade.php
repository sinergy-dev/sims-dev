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
<b style="font-family: Lucida Sans Unicode, sans-serif;">SUPPLIER</b>
<table class="table_supplier" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
	<tr style="vertical-align: top;">
		<td style="width:60%; border: solid black;">
			<table class="table_supplier_content" style="width: 100%;">
				<tr>
					<th style="text-align: left;">To</th>
					<th>:</th>
					<td>{{$data->to}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Address</th>
					<th>:</th>
					<td>
						{{$data->address}}
					</td>
				</tr>
				<tr>
					<th style="text-align: left;">Telp</th>
					<th>:</th>
					<td>{{$data->phone}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Fax</th>
					<th>:</th>
					<td>{{$data->fax}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Email</th>
					<th>:</th>
					<td>{{$data->email}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Attention</th>
					<th>:</th>
					<td>{{$data->attention}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">From</th>
					<th>:</th>
					<td>{{$data->name}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Subject</th>
					<th>:</th>
					<td>{{$data->title}}</td>
				</tr>
			</table>
		</td>
		<td style="width:40%; border: solid black;">
			<table class="table_supplier_content" style="width: 100%;">
				<tr>
					<th style="text-align: right;">Date</th>
					<th>:</th>
					<td>{!!date('d-M-y', strtotime($data->created_at))!!}</td>
				</tr>
			<!-- 	<tr>
					<th style="text-align: right;">Issued by</th>
					<th>:</th>
					<td></td>
				</tr> -->
				<tr>
					<th style="text-align: right;">PR No.</th>
					<th>:</th>
					<td>{{$data->no_pr}}</td>
				</tr>
				<tr>
					<th style="text-align: right;">Quo</th>
					<th>:</th>
					<td>{{$data->quote_number}}</td>
				</tr>
				<!-- <tr>
					<th style="text-align: right;">Kurs</th>
					<th>:</th>
					<td>-</td>
				</tr> -->
				<tr>
					<th style="text-align: right;">Method Request</th>
					<th>:</th>
					<td>{{$data->request_method}}</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table class="table_item_content" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
	<thead>
		<tr style="background-color:#c0c0c0">
			<th style="border-collapse: collapse;border: 1px solid;">No</th>
			<th style="border-collapse: collapse;border: 1px solid;">Product</th>
			@if($data->type_of_letter == 'EPR')
			<th style="border-collapse: collapse;border: 1px solid;">Part Number</th>
			<th style="border-collapse: collapse;border: 1px solid;">Serial Number</th>
			@endif
			<th style="border-collapse: collapse;border: 1px solid;">Description</th>
			<th style="border-collapse: collapse;border: 1px solid;">Qty</th>
			<th style="border-collapse: collapse;border: 1px solid;">Unit</th>
			<th style="border-collapse: collapse;border: 1px solid;">Price</th>
			<th style="border-collapse: collapse;border: 1px solid;">Total Price</th>
		</tr>
	</thead>
	<tbody>
		@foreach($product as $key => $eachProduct)
			<tr>
				<td style="text-align:center">{{++$key}}</td>
				<td>{{$eachProduct->name_product}}</td>
				@if($data->type_of_letter == 'EPR')
				<td style="text-align:center;">{{$eachProduct->part_number}}</td>
				<td style="text-align:center;">{{$eachProduct->serial_number}}</td>
				@endif
				<td>{!! nl2br($eachProduct->description) !!}
					<br><br>
	          		{{$eachProduct->for}}</td>
				<td style="text-align:center">{{$eachProduct->qty}}</td>
				<td style="text-align:center">{{$eachProduct->unit}}</td>
				@if($data->isRupiah == 'true')
				<td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($eachProduct->nominal_product,2)}}</td>
				<td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($eachProduct->grand_total,2)}}</td>
				@else
				<td style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($eachProduct->nominal_product,2)}}</td>
				<td style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($eachProduct->grand_total,2)}}</td>
				@endif
			</tr>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<th>-</th>
			<th></th>
			@if($data->type_of_letter == 'EPR')
			<th></th>
			<th></th>
			@endif
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		<tr>
			<th></th>
			<th></th>
			@if($data->type_of_letter == 'EPR')
			<th></th>
			<th></th>
			@endif
			<th style="text-align:right">Total</th>
			<th></th>
			<th></th>
			<th></th>
			@if($data->isRupiah == 'true')
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($sum_nominal,2)}}</th>
			@else
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($sum_nominal,2)}}</th>
			@endif
		</tr>
		@if($data->discount != 'false' && $data->discount != 0)
		<tr>
			<th></th>
			<th></th>
			@if($data->type_of_letter == 'EPR')
			<th></th>
			<th></th>
			@endif
			<th style="text-align:right">Discount {{$data->discount}}%</th>
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($amount_discount,2)}}</th>
		</tr>
		@endif
		<tr>
			<th></th>
			<th></th>
			@if($data->type_of_letter == 'EPR')
			<th></th>
			<th></th>
			@endif
			<th style="text-align:right">Tax Base Other</th>
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($tax_base_other,2)}}</th>
		</tr>
		@if($data->status_tax != 'False')
			<tr>
				<th></th>
				<th></th>
				@if($data->type_of_letter == 'EPR')
				<th></th>
				<th></th>
				@endif
				@if($data->status_tax == '11')
				<th style="text-align:right" 11%>VAT 11%</th>
				@elseif($data->status_tax == '1.1')
				<th style="text-align:right" 11%>VAT 1.1%</th>
				@else
				<th style="text-align:right" 11%></th>
				@endif
				<th></th>
				<th></th>
				<th></th>
				@if($data->isRupiah == 'true')
					@if($data->status_tax == '11' || $data->status_tax == '1.1' || $data->status_tax == '1.2' || $data->status_tax == '12')
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($amount_tax,2)}}</th>
					@else
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">0</th>
					@endif
				@else
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($sum_nominal+$amount_tax,2)}}</th>
				@endif
			</tr>
			@if($data->tax_pb != 'false' && $data->tax_pb != 0)
			<tr>
				<th></th>
				<th></th>
				<th style="text-align:right">PB1 {{$data->tax_pb}}%</th>
				<th></th>
				<th></th>
				<th></th>
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($amount_pb,2)}}</th>
			</tr>
			@endif
			@if($data->service_charge != 'false' && $data->service_charge != 0)
			<tr>
				<th></th>
				<th></th>
				<th style="text-align:right">Service Charge {{$data->service_charge}}%</th>
				<th></th>
				<th></th>
				<th></th>
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($amount_service_charge,2)}}</th>
			</tr>
			@endif
			<tr style="background-color:#c0c0c0">
				<th></th>
				<th></th>
				@if($data->type_of_letter == 'EPR')
				<th></th>
				<th></th>
				@endif
				<th style="text-align:right" >Grand Total</th>
				<th></th>
				<th></th>
				<th></th>
				@if($data->isRupiah == 'true')
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($grand_total,2)}}</th>
				@else
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($grand_total,2)}}</th>
				@endif
			</tr>
		@else
			<tr style="background-color:#c0c0c0">
				<th></th>
				<th></th>
				@if($data->type_of_letter == 'EPR')
				<th></th>
				<th></th>
				@endif
				<th style="text-align:right" >Grand Total</th>
				<th></th>
				<th></th>
				<th></th>
				@if($data->isRupiah == 'true')
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($grand_total,2)}}</th>
				@else
				<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($grand_total,2)}}</th>
				@endif
			</tr>
		@endif
	</tfoot>
</table>
@if($data->type_of_letter == 'EPR')
<br>
<b style="font-family: Lucida Sans Unicode, sans-serif;">CUSTOMER</b>
<table class="table_supplier" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
	<tr style="vertical-align: top;">
		<td style="width:60%; border: solid black;">
			<table class="table_supplier_content" style="width: 100%;">
				<tr>
					<th style="text-align: left;">To</th>
					<th>:</th>
					<td>{{$data->to_customer}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Address</th>
					<th>:</th>
					<td>
						{{$data->office_building}}<br>
						{{$data->address_customer}}<br>
						{{$data->city}}<br>
						{{$data->province}}<br>
						{{$data->postal}}
					</td>
				</tr>
				<tr>
					<th style="text-align: left;">Telp</th>
					<th>:</th>
					<td>{{$data->phone}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Fax</th>
					<th>:</th>
					<td>-</td>
				</tr>
				<tr>
					<th style="text-align: left;">Email</th>
					<th>:</th>
					<td>-</td>
				</tr>
				<tr>
					<th style="text-align: left;">Attention</th>
					<th>:</th>
					<td>-</td>
				</tr>
				<tr>
					<th style="text-align: left;">From</th>
					<th>:</th>
					<td>{{$data->from}}</td>
				</tr>
				<tr>
					<th style="text-align: left;">Subject</th>
					<th>:</th>
					<td>{{$data->subject}}</td>
				</tr>
			</table>
		</td>
		<td style="width:40%; border: solid black;">
			<table class="table_supplier_content" style="width: 100%;">
				<tr>
					<th style="text-align: right;">Date</th>
					<th>:</th>
					<td>{!!date('d-M-y', strtotime($data->date_pid))!!} (Id Project)</td>
				</tr>
				<tr>
					<th style="text-align: right;">Project ID No. </th>
					<th>:</th>
					<td>{{$data->project_id}}</td>
				</tr>
				<tr>
					<th style="text-align: right;">PO No.</th>
					<th>:</th>
					<td>{{$data->no_po_customer}}</td>
				</tr>
				<tr>
					<th style="text-align: right;">Do No.</th>
					<th>:</th>
					<td>-</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br>
<table class="table_item_content" style="width:100%;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
	<thead>
		<tr style="background-color:#c0c0c0">
			<th>No</th>
			<th>Product</th>
			<th>Description</th>
			<th>Qty</th>
			<th>Unit</th>
			<th>Price</th>
			<th>Total Price</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="text-align:center">1.</td>
			<td>-</td>
			<td>{{$data->subject}}</td>
			<td style="text-align:center">1</td>
			<td style="text-align:center">Unit</td>
			<td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp.  {{number_format($data->amount_idr_before_tax,2)}}</td>
			<td style="text-align:right;font-family:Consolas, monaco, monospace;">Rp.  {{number_format($data->amount_idr_before_tax,2)}}</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th>-</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		<tr>
			<th></th>
			<th></th>
			<th style="text-align:right">Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($data->amount_idr_before_tax,2)}}</th>
		</tr>
		@if($data->date_pid >= "2025-01-01")
		<tr>
			<th></th>
			<th></th>
			<th style="text-align:right">Tax Base Other</th>
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($data->tax_base_other_customer,2)}}</th>
		</tr>
		@endif
		<tr>
			<th></th>
			<th></th>
			@if($data->date_pid >= "2025-01-01")
				<th style="text-align:right" 11%>VAT 12%</th>
			@elseif($data->date_pid >= "2022-04-01" && $data->date_pid <= "2024-12-31") 
				<th style="text-align: right" 11%>VAT 11%</th>
			@else
				<th style="text-align: right" 11%>VAT 10%</th>
			@endif
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($data->grand_total - $data->amount_idr_before_tax,2)}}</th>
		</tr>
		<tr style="background-color:#c0c0c0">
			<th></th>
			<th></th>
			<th style="text-align:right" >Grand Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($data->grand_total,2)}}</th>
		</tr>
	</tfoot>
</table>
@endif
<br>
<div style="width: 100%; text-align: justify;page-break-inside: avoid;line-height: 1.1;font-size: small;font-family: Lucida Sans Unicode, sans-serif;">
	<b style="font-family: Lucida Sans Unicode, sans-serif;">Terms & Condition :</b>
	<br>
	<div style="font-family: Lucida Sans Unicode, sans-serif;">
		{!!$data->term_payment!!}
	</div>
</div>
<br>
<br>
<b style="font-family: Lucida Sans Unicode, sans-serif;">For complementary documents used in the process of using PR, you can see the following google drive link:</b><br><br>
<a href="{{$linkDrive['link']}}" style="cursor:pointer;font-family:Source Sans Pro,Helvetica Neue,Helvetica,Arial,sans-serif;" target="_blank">{{$linkDrive['folder']}}</a>
<br>
<br>
<br>
<p style="font-family: Lucida Sans Unicode, sans-serif;">If you have any further inqueries, please do not hesitate to contact us. We thank you for your kind attention.</p>
<br>
<br>
<br>
<br>
<div>
	<span style="font-family: Lucida Sans Unicode, sans-serif;">Best Regards,</span><br><br>
	<span style="font-family: Lucida Sans Unicode, sans-serif;">Procurement</span>
</div>
<br>
<br>
<br>
<br>
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> -->
