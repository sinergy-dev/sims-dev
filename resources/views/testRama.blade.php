<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<h2>CSV Parser for Import Producet Info</h2>
	<p>You can get format of CSV from this <a href="{{url('draft_pr/Import_product_sample.csv')}}">link</a></p>
	<p>And make sure, the change of template only at row 2, any change on row 1 (header) will be reject</p>
	
			Select File To Import:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<br>
		<br>
		<button onclick="submitCSV()">Upload CSV</button>
	<h3>Result of Upload CSV will be show here</h3>
	<div>
		<table>
			<thead>
				<tr>
					<th>No</th>
					<th>Product</th>
					<th>Description</th>
					<th>Qty</th>
					<th>Type</th>
					<th>Price</th>
					<th>Total Price</th>
					<th>
						<a class="pull-right"><i class="fa fa-refresh"></i>&nbsp;</a>
					</th>
				</tr>
			</thead>
			<tbody id="tbodyProduct">
				
			</tbody>
		</table>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		})

		function submitCSV(){

			// var data = {
			// 	hahaha : "asdfads"
			// }

			var dataForm = new FormData();
			dataForm.append('csv_file',$('#fileToUpload').prop('files')[0]);

			$.ajax({
				processData: false,
				contentType: false,
				url : "{{url('testCSVUploadPost')}}",
				type : "POST",
				data : dataForm,
				// contenType: "multipart/form-data;",
				success : function(result){
					var append = ""

					$('#tbodyProduct').empty()

					$.each(result,function(key,value){
						append = append + '<tr>'
						append = append + '	<td>'
						append = append + '		<span style="font-size: 12px; important">' + value[0] + '</span>'
						append = append + '	</td>'
						append = append + '	<td width="20%">'
						append = append + '		<input data-value="" readonly="" style="font-size: 12px; important" class="form-control" type="" name="" value="' + value[1] + '">'
						append = append + '	</td>'
						append = append + '	<td width="30%">'
						// append = append + '		<textarea readonly="" data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">' + value[2] + '</textarea>'
						if(value[3] != "-" && value[4] != "-"){
							append = append + '		<textarea readonly="" data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">' + value[2] + '&#10;&#10;SN : ' + value[3] + '&#10;PN : ' + value[4] + '</textarea>'
						} else if (value[4] != "-"){
							append = append + '		<textarea readonly="" data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">' + value[2] + '&#10;&#10;PN : ' + value[4] + '</textarea>'
						} else if (value[3] != "-"){
							append = append + '		<textarea readonly="" data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">' + value[2] + '&#10;&#10;SN : ' + value[3] + '</textarea>'
						} else {
							append = append + '		<textarea readonly="" data-value="" style="font-size: 12px; important;resize:none;height:150px" class="form-control">' + value[2] + '</textarea>'
						}
						append = append + '	</td>'
						append = append + '	<td width="7%">'
						append = append + '		<input data-value="" readonly="" style="font-size: 12px; important;width:70px" class="form-control" name="" value="' + value[6] + '">'
						append = append + '	</td>'
						append = append + '	<td width="10%">'
						append = append + '		<input data-value="" readonly="" style="font-size: 12px; important;width:70px" class="form-control" name="" value="' + value[5] + '">'
						append = append + '	</td>'
						append = append + '	<td width="15%">'
						append = append + '		<input readonly="" data-value="" style="font-size: 12px; important" class="form-control" type="" name="" value="' + value[7] + '">'
						append = append + '	</td>'
						append = append + '	<td width="15%">'
						append = append + '		<input readonly="" data-value="" style="font-size: 12px; important" class="form-control inputTotalPriceEdit" type="" name="" value="' + (value[7]*value[5]) + '">'
						append = append + '	</td>'
						append = append + '	<td width="8%">'
						append = append + '		<button type="button" onclick="nextPrevUnFinished(-1,0)" id="btnEditProduk" data-id="0" data-value="1" class="btn btn-xs btn-warning fa fa-edit btnEditProduk" style="width:25px;height:25px;margin-right:5px"></button>'
						append = append + '	</td>'
						append = append + '</tr>'
					})

					$('#tbodyProduct').append(append)

				}
			})
		}
	</script>
</body>
</html>