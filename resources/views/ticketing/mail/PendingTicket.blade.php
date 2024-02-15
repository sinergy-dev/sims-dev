<div style="color: #555;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;">
	<p id="emailPendingHeader" style="margin: 0 0 10px;box-sizing: border-box;font-size: 14px;line-height: 1.42857143;color: #555;font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight: 400;">
		
	</p>
	<table style="width: 401px;">
		<tr style="text-align: right;">
			<td style=" width:120px">
				<h3 style="font-size: 24px;"><b><i>Pending Ticket</i></b></h3>
			</td>
			<td >
				<img src="{{asset('/image/header-ticketing.jpg')}}">
			</td>
		</tr>
	</table>
	<br>
	<table class="table table2" style="width: 400px;max-width: 100%;margin-bottom: 20px;border-spacing: 0;border-collapse: collapse;line-height: 1.42857143;vertical-align: top;">
		<tr >
			<th style=" border:1px solid #fff; padding: 3px; width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Ticket ID</th>
			<td style=" border:1px solid; padding: 3px;" class="holderPendingID"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff; padding: 3px; width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Refrence</th>
			<td style=" border:1px solid; padding: 3px;" class="holderPendingRefrence"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Customer</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingCustomer"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">PIC</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingPIC"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Contact</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingContact"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Problem</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingProblem"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Location</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingLocation"></td>
		</tr>
		<tr style="display: none" class="holderPendingIDATM2">
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">ID ATM</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingIDATM"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Engineer</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingEngineer"></td>
		</tr>
		<tr style="display: none" class="holderNumberTicket2">
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Ticket Wincor</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderNumberTicket"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Serial number</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingSerial"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Severity</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingSeverity"></td>
		</tr>
		<!-- <tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;"" class="bg-primary">Counter Measure</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingCounter"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;"" class="bg-primary">Root Cause</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingRoot"></td>
		</tr> -->
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Open At</th>
			<td  style=" border:1px solid; padding: 3px;" class="holderPendingDate"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff;  padding: 3px;width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-warning">Status</th>
			<td rowspan=2 style=" border:1px solid; padding: 3px; text-align: center; background-color: #f39c12;border-bottom: none;color:#FFFFFF;" class="holderPendingStatus text-center bg-yellow"></td>
		</tr>
		<tr>
			<th style=" border:1px solid #fff; padding: 3px; width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-warning">Waktu</th>
			<!-- <td style=" border:1px solid; padding: 3px; text-align: center; background-color: #f39c12; border-top: none; color:#FFFFFF;" class="holderPendingWaktu text-center bg-yellow" ></td> -->
		</tr>
		<tr>
			<th style=" border:1px solid #fff; padding: 3px; width:120px;color: #fff;background-color: #337ab7;text-align: left;" class="bg-primary">Note</th>
			<td style=" border:1px solid; padding: 3px;" class="holderPendingNote"></td>
		</tr>
	</table>
	<br>
	<p>
		Thanks<br>
		Best Regard,
	</p>
	<h4 style="color: #3c8dbc !important;margin-bottom: 0px" class="text-light-blue" >{{Auth::user()->name}}</h4>
	<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Helpdesk</i></h5>
	<p>
		----------------------------------------<br>
		PT. Sinergy Informasi Pratama (SIP)<br>
		| Inlingua Building 2nd Floor |<br>
		| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
		| Kembangan | Jakarta 11610 – Indonesia |<br>
		| Mobile | {{Auth::user()->phone}} |<br>
		| Phone | 021 - 58355599 |<br>
		----------------------------------------<br>
	</p>
</div>
