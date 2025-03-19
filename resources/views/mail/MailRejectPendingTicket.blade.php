<html>
<head>
    <title>Request Pending - Rejected</title>
</head>
<body>
    <b>Dear, {{ $data['name'] }}</b>

    <p>Request pending yang anda ajukan telah ditolak oleh leader. Berikut detail ticket terkait</p>

    <b>Detail Ticket:</b>
    <ul>
        <li><strong>ID Ticket:</strong> {{ $data['id_ticket'] }}</li>
        <li><strong>Reason:</strong>{{$data['reason']}}</li>
    </ul>

    <p>Kini status tiket adalah <b>ON PROGRESS</b></p>

    <p>
		Thanks<br>
		Best Regard,
	</p>
	<h4 style="color: #3c8dbc !important;margin-bottom: 0px" class="text-light-blue" >SLM APP</h4>
	
</body>

</html>