<!DOCTYPE html>
<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
	<style type="text/css">
		table {
		  border-collapse: collapse;
		}

		table, th, td {
		  border: 0px solid black;
		  padding-top: 14px;
		}

		table, th {
			padding-left: 15px;
		}

		.table-bordered, .table-bordered th, .table-bordered td {
	      /*border: 1px solid black;
	      width: 100%;*/
	      border: 1px solid black;
		  padding-top: 14px;
	    }

	    .table-bordered-child, .table-bordered-child th, .table-bordered td {
	      border: 1px solid black;
	      width: 75%;
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
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
			margin: 4px 2px;
			cursor: pointer;
			background-color: #7868e6;
			border-radius: 4px;
		}

		/*.centered{
			position: absolute;
		  	top: 50%;
		  	left: 50%;
		  	transform: translate(-50%, -40%);
		}*/
	</style>
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{{asset('js/jquery.mask.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/jquery.mask.js')}}"></script>
	<script type="text/javascript">
		console.log( $("#amounts").text())
	    $('.money').mask('000,000,000,000,000', {reverse: true});
	</script>
</head>
<body style="display: block; width: 600px; margin-left: auto; margin-right: auto; color: #000000">
	<div style="line-height: 1.5em">
		<img src="{{ asset('image/sbe_notif.png')}}" style="width: 50%; height: 50%">
	</div>
	<div style="line-height: 1.5em;padding-left: 13px;">
		<div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
			<p style="font-size: 20px">
				Dear {{$data['to']}},
			</p>
			<p style="font-size: 16px">
				@if($data['status'] == "Review SBE")
				Please Review this Temporary SBE:<br>
				@else
				You're temporary SBE has notes, please check and submit the revision properly:
				@endif
			</p>
			@if($data['status'] == "Review SBE")
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<?php $i = 0 ?>
				@foreach($data['data'] as $keys => $data_get_function)
				<table style="text-align: left;margin: 5px; font-size: 16px">
			        <tr>
			          <td width="20%">
			          	@if($keys == "Implementation")
			              <div style="width: 150px;height: 50px;background-color: #789de5;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @elseif($keys == "Maintenance")
			              <div style="width: 150px;height: 50px;background-color: #ea3323;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @else
			              <div style="width: 150px;height: 50px;background-color: #f19e38;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @endif
			          </td>
			          <td width="80%">
			            @foreach($data_get_function as $get_function)
				            <table style="border: 1px solid black;width: 100%;">
				              <thead style="border: 1px solid black;">
				                <tr>
				                  <th style="border: 1px solid black;text-align: center;">No</th>
				                  <th style="border: 1px solid black;text-align: center;">Function</th>
				                  <th style="border: 1px solid black;text-align: center;">Total</th>
				                </tr>
				              </thead>  
				              <tbody style="border: 1px solid black;">
				              		@foreach($get_function['get_function'] as $datas)
						                <tr>
						                  <td style="text-align: center;border: 1px solid black;">{{++$i}}</td>
						                  <td style="text-align: left;border: 1px solid black;">{{$datas['item']}}</td>
						                  <td style="text-align: right;border: 1px solid black;">IDR {{number_format($datas['total_nominal'])}}</td>
						                </tr>
				                	@endforeach
				              </tbody>
				              <tfoot style="border: 1px solid black;">
				                <tr>
				                  <th colspan="2" style="text-align:right;border: 1px solid black;">Grand Total Cost</th>
				                  <th style="border: 1px solid black;text-align: right;">IDR {{number_format($get_function['nominal'])}}</th>
				                </tr>
				              </tfoot>  
				            </table>
			            @endforeach
			          </td>
			        </tr>
			    </table>
			    @endforeach
			</div>
			@elseif($data['status'] == "Add Notes")
			<div id="bg_ket" style="background-color: #ececec; padding: 10px">
				<?php $i = 0 ?>
				@foreach($data['data'] as $keys => $data_get_function)
				<table style="text-align: left;margin: 5px; font-size: 16px">
			        <tr>
			          <td width="20%">
			          	@if($keys == "Implementation")
			              <div style="width: 150px;height: 50px;background-color: #789de5;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @elseif($keys == "Maintenance")
			              <div style="width: 150px;height: 50px;background-color: #ea3323;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @else
			              <div style="width: 150px;height: 50px;background-color: #f19e38;color: white;text-align: center;margin: 10px;padding-top: 25px;">
			                <b style="text-align: center;">
			                    {{$keys}}
			                </b>
			              </div>
			            @endif
			          </td>
			          <td width="80%">
			            @foreach($data_get_function as $get_function)
				            <table style="border: 1px solid black;width: 100%;">
				              <thead style="border: 1px solid black;">
				                <tr>
				                  <th style="border: 1px solid black;text-align: center;">No</th>
				                  <th style="border: 1px solid black;text-align: center;">Function</th>
				                  <th style="border: 1px solid black;text-align: center;">Total</th>
				                </tr>
				              </thead>  
				              <tbody style="border: 1px solid black;">
				              		@foreach($get_function['get_function'] as $datas)
						                <tr>
						                  <td style="text-align: center;border: 1px solid black;">{{++$i}}</td>
						                  <td style="text-align: left;border: 1px solid black;">{{$datas['item']}}</td>
						                  <td style="text-align: right;border: 1px solid black;">IDR {{number_format($datas['total_nominal'])}}</td>
						                </tr>
				                	@endforeach
				              </tbody>
				              <tfoot style="border: 1px solid black;">
				                <tr>
				                  <th colspan="2" style="text-align:right;border: 1px solid black;">Grand Total Cost</th>
				                  <th style="border: 1px solid black;text-align: right;">IDR {{number_format($get_function['nominal'])}}</th>
				                </tr>
				              </tfoot>  
				            </table>
			            @endforeach
			          </td>
			        </tr>
			    </table>
			    @endforeach
			</div>
			<div id="bg_ket" style="background-color:#ececec; padding: 10px;margin-top: 10px;">
				<p>Notes : {{$data['notes']}}</p> 
			</div>
			@endif
			<p style="font-size: 16px">
				Click the following link button to review temporary SBE.
			</p>

			<center><a href="{{url('sbe_detail',$data['id'])}}?{{$data['lead_id']}}" target="_blank"><button class="button"> Temporary SBE </button></a></center>

			<p style="font-size: 16px">
				Please check again, if there are errors or questions please contact the Developer Team (Ext: 384) or email to bcd@sinergy.co.id.<br>
				Thank you.
			</p>
			<p style="font-size: 16px">
				Best Regard,
			</p><br>
			<p style="font-size: 16px">
				Application Development
			</p>
		</div>
	</div>
</body>
<footer style="display:block;width:600px;margin-left:auto;margin-right:auto;">
	<div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
		<p>
			<center>PT. Sinergy Informasi Pratama</center>
		</p>
		<p>
			<center>Jl. Puri Raya, Blok A 2/3 No. 33-35 Puri Indah, Kembangan, Jakarta, Indonesia 11610</center>
		</p>
		<p>
			<center><i class="fa fa-phone"></i>021 - 58355599</center>
		</p>
	</div>
</footer>
</html>