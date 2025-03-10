<!DOCTYPE html>
<html>
<head>
	<title>Purchase Request</title>
	<style type="text/css">
		.bodyEmail {
			line-height: 1.1;
			font-size: 9px;
			font-family: Lucida Sans Unicode, sans-serif;
		}
		@page { 
			margin: 80px 40px 100px 40px;
		}

		header { 
			position: fixed; 
			top: -80px; 
			left: 0px; 
			right: 0px; 
			height: 50px; 
		}
		footer { 
			position: fixed; 
			bottom: -50px; 
			left: 0px; 
			right: 0px; 
			height: 50px; 
		}
		.table_cover_footer {
			background-image:url("https://eod-api.sifoma.id//storage/image/pdf_image/footer7.png");
			background-repeat: no-repeat;
		}.

		.table_supplier td, .table_supplier td * {
			align-content: left;
		}

		table {
			width: 100%;
      		border-collapse: collapse;
		}

		.table_item_content th, .table_item_content td {
			border-collapse: collapse;
			border: 1px solid;
		}

		/* Set the width of the pre element inside table cells */
	    td pre {
	        white-space: wrap; /* Prevent text wrapping */
	        overflow-wrap: break-word; /* Break long words */
	        text-align: left !important; /* Align text to the left */
	        font-family: Lucida Sans Unicode, sans-serif;
	    }
	</style>
</head>
<body class="bodyEmail">
	<header>
		<table style="width: 100%;" >
			<tr>
				<td style="width:40%;">
					<p style="font-family:Consolas, monaco, monospace; color: grey;">
						DOC : SIP-FI-F009<br>
						REV : 01.00<br>
						DATE : 12/04/2021
					</p>
					@if($data->type_of_letter == "EPR")
						<h2>Formulir External Purchase Request</h2>
					@else
						<h2>Formulir Internal Purchase Request</h2>
					@endif

				</td>
				<td style="width:20%;"></td>
				<td style="width:30%; text-align: right;">
					<img src="img/logosip.png" style="width:100px">
				</td>
			</tr>
		</table>
	</header>
	<footer>
		<table style="width: 100%" class="table_cover_footer">
			<tr>
				<td colspan="4" style="font: 11px;">
					<b style="color: #7f0f7e">PT. Sinergy Informasi Pratama</b>
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
					<b>Central Office</b>
				</td>
				<td style="width: 68%;font: 9px;">
					: Jl. Puri Kencana Blok K6 No.2M-2L, Kembangan, Jakarta Barat 11610
				</td>
				<td style="width: 5%;font: 9px;">
					Tel
				</td>
				<td style="width: 15%;font: 9px;">
					: +62 21 583 55599
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
					<b>Operational Office</b>
				</td>
				<td style="width: 68%;font: 9px;">
					: Gedung Inlingua Jl. Puri indah Kav. A2/3 No. 33-35, Puri Indah, Jakarta Barat 11610
				</td>
				<td style="width: 5%;font: 9px;">
					Fax
				</td>
				<td style="width: 15%;font: 9px;">
					: +62 21 583 55188
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
				</td>
				<td style="width: 68%;font: 9px;">
				</td>
				<td style="width: 5%;font: 9px;">
				</td>
				<td style="width: 15%;font: 9px;">
				</td>
			</tr>
			<tr>
				<td style="width: 12%;font: 9px;">
				</td>
				<td style="width: 68%;font: 9px;">
				</td>
				<td style="width: 5%;font: 9px;">
				</td>
				<td style="width: 15%;font: 9px;">
				</td>
			</tr>
			<!-- <tr>
				<td style="width: 12%;font: 9px;">
					<b>Branch Office</b>
				</td>
				<td style="width: 68%;font: 9px;">
					: Gedung Bank Sumsel Babel Lt. 1, Jl. Gubernur H.A Bastari No.7, Jakabaring, Palembang
				</td>
				<td style="width: 5%;font: 9px;">
					Email
				</td>
				<td style="width: 15%;font: 9px;">
					: info@sinergy.co.id
				</td>
			</tr> -->
			<tr>
				<td style="width: 10%;font: 10px;" colspan="2">
					<span style="color:white">.</span>
				</td>
				<td style="width: 5%;font: 9px;">
					Web
				</td>
				<td style="width: 15%;font: 9px;">
					: www.sinergy.co.id
				</td>
			</tr>
		</table>
	</footer>	
	<div>
		<b style="font-size:small;">SUPPLIER</b>
		<table class="table_supplier" style="width:100%;">
			<tr style="vertical-align: top;">
				<td style="width:60%; border: solid black;">
					<table style="width: 100%;vertical-align: top;">
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
							<td>{{$data->phone_pr}}</td>
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
					<table style="width: 100%;vertical-align: top;">
						<tr>
							<th style="text-align: right;">Date</th>
							<th>:</th>
							<td>{!!date('d-M-y', strtotime($data->created_at))!!}</td>
						</tr>
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
		<table class="table_item_content" style="width:100%;border-spacing: 0">
			<thead>
				<tr style="background-color:#c0c0c0;">
					<th>No</th>
					<th>Product</th>
					@if($data->type_of_letter == 'EPR')
					<th>Part Number</th>
					<th>Serial Number</th>
					@endif
					<th>Description</th>
					<th>Qty</th>
					<th>Unit</th>
					<th>Price</th>
					<th>Total Price</th>
				</tr>
			</thead>
			<tbody>
				@foreach($product as $key => $eachProduct)
					<tr>
						<td style="text-align:center;vertical-align:top;width: 1%">{{++$key}}</td>
						<td style="width: 2%;vertical-align:top;">{{$eachProduct->name_product}}</td>
						@if($data->type_of_letter == 'EPR')
						<td style="width: 2%;vertical-align:top;">{{$eachProduct->part_number}}</td>
						<td style="width: 2%;vertical-align:top;">{{$eachProduct->serial_number}}</td>
						@endif
						<td style="width:10%">
							{!!$eachProduct->description!!}
          				</td>
						<td style="text-align:center;width: 1%">{{$eachProduct->qty}}</td>
						<td style="text-align:center;width: 1%">{{$eachProduct->unit}}</td>
						@if($data->isRupiah == 'true')
						<td style="text-align:right;font-family:Consolas, monaco, monospace;width: 5%">Rp. {{number_format($eachProduct->nominal_product,2)}}</td>
						<td style="text-align:right;font-family:Consolas, monaco, monospace;width: 5%">Rp. {{number_format($eachProduct->grand_total,2)}}</td>
						@else
						<td style="text-align:right;font-family:Consolas, monaco, monospace;width: 5%">USD {{number_format($eachProduct->nominal_product,2)}}</td>
						<td style="text-align:right;font-family:Consolas, monaco, monospace;width: 5%">USD {{number_format($eachProduct->grand_total,2)}}</td>
						@endif
					</tr>
				@endforeach
			</tbody>
			<tfoot style="">
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
					<th style="text-align:right">Discount {{number_format($data->discount,2)}}%</th>
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
					<th style="text-align:right" 11%>VAT 12%</th>
					@elseif($data->status_tax == '1.1')
					<th style="text-align:right" 11%>VAT 1.2%</th>
					@else
					<th style="text-align:right" 11%></th>
					@endif
					<th></th>
					<th></th>
					<th></th>
					@if($data->isRupiah == 'true')
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($amount_tax,2)}}</th>
					@else
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($amount_tax,2)}}</th>
					@endif
				</tr>
				@if($data->tax_pb != 'false' && $data->tax_pb != 0)
				<tr>
					<th></th>
					<th></th>
					@if($data->type_of_letter == 'EPR')
					<th></th>
					<th></th>
					@endif
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
					@if($data->type_of_letter == 'EPR')
					<th></th>
					<th></th>
					@endif
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
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">Rp. {{number_format($sum_nominal,2)}}</th>
					@else
					<th style="text-align:right;font-family:Consolas, monaco, monospace;">USD {{number_format($sum_nominal,2)}}</th>
					@endif
				</tr> 
				@endif
			</tfoot>
		</table>
	</div>
	@if($data->type_of_letter == "EPR")
	<hr>
	<div>
		<b style="font-size:small;">CUSTOMER</b>
		<table class="table_supplier" style="width:100%;page-break-inside: avoid;">
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
							<td>{!!date('d-M-y', strtotime($data->tgl_pid))!!} (Id Project)</td>
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
		<table class="table_item_content" style="width:100%;page-break-inside: avoid;">
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
	</div>
	@endif
	<br>
	<div style="width: 100%; text-align: justify;page-break-inside: avoid;">
		<div>
			<b>Terms & Condition :</b>
			<br>
			{!! $data->term_payment !!}
		</div>
	</div>
	<div style="page-break-inside: avoid;">
		<p>If you have any further inqueries, please do not hesitate to contact us. We thank you for your kind attention.</p>
		<table style="width: 100%;text-align: center;">
			<tr>
				@foreach($sign as $key => $eachSign)
					@if($eachSign["position"] != "VP Internal Chain Management")
						@if(count($sign) == 4)
						<td style="width:33%;">
						@else
						<td style="width:50%;">
						@endif
							@if($eachSign["signed"] == 'true')
							<div>
								@if(count($sign) == 2)
									Acknowledge By:
								@else
									@if($key == 1)
									Request By:
									@elseif($key == 3)
									Approve By:
									@else
									Acknowledge By:
									@endif
								@endif
							</div>
							<div style="margin-top:5px;margin-bottom: 5px;">
								<img src="{{ $eachSign['ttd'] }}" style="height:100px;">
							</div>
							<div>
								<small>{{ $eachSign['date_sign'] }}</small>
							</div>
							@else
							<div>
								@if(count($sign) == 2)
									Acknowledge By:
								@else
									@if($key == 1)
									Request By:
									@elseif($key == 3)
									Approve By:
									@else
									Acknowledge By:
									@endif
								@endif
							</div>
							<div style="margin-top:5px;margin-bottom: 5px;">
								<img src="image/placeholder-sign-3.png" style="height:50px;">
							</div>
							<div>
								<small style="color:#a6a6a6;">(Date Sign)</small>
							</div>
							@endif
							<u>{{ $eachSign['name'] }}</u>
							<br>
							<i>
								<b>{{ $eachSign['position'] }}</b>
							</i>
							
						</td>
					@else
						@if($cek_role->group == 'Internal Chain Management' && $data->type_of_letter == 'IPR')
							<td style="width: 50%">
								@if($eachSign["signed"] == 'true')
									<div>
										@if($eachSign["position"] == "VP Internal Chain Management")
										Request By
										@endif
									</div>
									<div style="margin-top:5px;margin-bottom: 5px;">
										<img src="{{ $eachSign['ttd'] }}" style="height:100px;">
									</div>
									<div>
										<small>{{ $eachSign['date_sign'] }}</small>
									</div>
								@else
									<div>
										@if($eachSign["position"] == "VP Internal Chain Management")
										Request By
										@endif
									</div>
									<div style="margin-top:5px;margin-bottom: 5px;">
										<img src="image/placeholder-sign-3.png" style="height:50px;">
									</div>
									<div>
										<small style="color:#a6a6a6;">(Date Sign)</small>
									</div>
								@endif
								<u>{{ $eachSign['name'] }}</u>
								<br>
								<i>
									<b>{{ $eachSign['position'] }}</b>
								</i>
							</td>
						@endif
					@endif
				@endforeach
			</tr>
		</table>
	</div>
</body>
</html>