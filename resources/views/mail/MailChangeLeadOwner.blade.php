<div style='font-family: Arial, Helvetica, sans-serif;'>
<h1>Change Lead Register Ownership</h1>
This email is intended to inform you that there has been a change in Lead Register Ownership. <br>For more details, please see below.

<br>
<table style="width: 100%; text-align: left;border: 1px solid black; border-collapse: collapse;">
	<thead>
		<tr>
			<th style="border: 1px solid black;">Lead ID</th>
			<th style="border: 1px solid black;">Sales Before</th>
			<th style="border: 1px solid black;">Sales After</th>
			<th style="border: 1px solid black;">Change Date</th>
			<th style="border: 1px solid black;">Changer</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $value)
			<tr>
				<td style="border: 1px solid black;"><a href="https://app.sinergy.co.id/detail_project/{{$value['lead_id']}}">{{$value['lead_id']}}</a></td>
				<td style="border: 1px solid black;">{{$value['before_sales']}}</td>
				<td style="border: 1px solid black;">{{$value['after_sales']}}</td>
				<td style="border: 1px solid black;">{{$value['date_change']}}</td>
				<td style="border: 1px solid black;">{{$value['changer']}}</td>
			</tr>
		@endforeach
	</tbody>
</table>
<br>
That is what can be conveyed.
<p>
	Thanks<br>
	Best Regard,
</p>
<h5 style="color: #f39c12 !important;margin-top: 0px" class="text-yellow" ><i>Tech - Dev</i></h5>
<p>
	----------------------------------------<br>
	PT. Sinergy Informasi Pratama (SIP)<br>
	| Inlingua Building 2nd Floor |<br>
	| Jl. Puri Raya, Blok A 2/3 No. 33-35 | Puri Indah |<br>
	| Kembangan | Jakarta 11610 – Indonesia |<br>
	| Phone | 021 - 58355599 |<br>
	----------------------------------------<br>
</p>
</div>