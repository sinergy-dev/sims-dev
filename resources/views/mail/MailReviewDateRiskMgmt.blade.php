<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 0px solid grey;
		  padding: 10px;
		  margin: 10px;    
		}

		table, th {
			padding-left: 15px;
		}

		#bg_ket {
			border-radius: 10px;
		}

		#txt_center {
			text-align: center;
		}

		.money:before{
			content:"Rp";
		}

		.button {
			border: none;
			color: white;
			/*padding: 15px 32px;*/
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			background-color: #7868e6;
			border-radius: 4px;
		}

		.button-action {
          display: flex;
          padding: 4px;
          border: 1px solid #ccc;
          background-color: #ED2939;
          border: 1px solid #ED2939;
          border-radius: 2px; 
          font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; 
          color: #ffffff;
          text-decoration: none;
          font-weight:500;
          display: inline-block;
          align-items: center;
      	}

      	svg {
          font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif; 
          font-weight: bold;
          fill: currentColor; /* Use the current text color as the fill color for the SVG */
      	}

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/
	</style>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
</head>
<body style="display:block;width:800px;margin-left:auto;margin-right:auto;">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sims_sangar_2.png')}}" style="width: 10%; height: 10%">
	</div>
	<div style="line-height: 1.5em">
		<center><img src="{{asset('image/risk_review.png')}}" href="https://app.sinergy.co.id/login" style="width: 50%; height: 50%" readonly></center>
	</div>
	<div style="line-height: 1.5em;padding: 10px;">
		<div style="color: #141414; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 24px">
				<b>Dear {{$data['to']}}!</b>
			</p>
			<p style="font-size: 16px">
				Berikut terdapat risk yang masih aktif, dengan detail sebagai berikut:
			</p>
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<table style="text-align: left;margin: 5px; font-size: 12px" class="tableLead">
					<thead>
	                  <tr>
	                  	<th width="50px">Action</th>
	                    <th>Project Id</th>
	                    <th>Risk Description</th>
	                    <th>Risk Owner</th>
	                    <th>Review Date</th>
	                  </tr>
	                </thead>
					<center><b></b></center>
					<tbody>
						@foreach($data['risk'] as $risk)
						<tr>
							<td width="50px">
					    	<a href="{{url('/PMO/project/detail',$risk->id_pmo)}}?project_type={{$risk->project_type}}&id_risk={{$risk->id_risk}}"><div class="button-action">
					            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/></svg>
					            <span style="font-size:10px">Risk</span>
					        </div></a></td>
							<td>{{$risk->project_id}}</td>
							<td>{{$risk->risk_description}}</td>
							<td>{{$risk->risk_owner}}</td>
							<td>{{$risk->review_date}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<p style="font-size: 16px">
				Please check again, if there are errors or questions please contact the Developer Team (Ext: 384) or email to development@sinergy.co.id.<br>
				Thank you.
			</p>
			<p style="font-size: 16px">
				Best Regard,
			</p><br>
			<p style="font-size: 16px">
				BCD - Dev
			</p>
		</div>
	</div>
</body>
<footer style="display:block;width:800px;margin-left:auto;margin-right:auto;">
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>