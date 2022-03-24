<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!-- CSS only -->
<style type="text/css">
  #header {
	  position: fixed;
	  width: 160px;
	  height: 80px;
	  top: 0;
	  left: 0;
	  right: 0;
	  padding-left: 570px;
	}

	#footer {
	  position: fixed;
	  bottom: 10px; 
	  width: 775px; 
	  height: 130px;
	  left: 0;
	  right: 0;
	}

	table {
    border-spacing: 0;
    width: 100%;
  }

  th {
    background: #ffc107;
    background: linear-gradient(#ffc107, #ffc107);
    border-left: 1px solid rgba(0, 0, 0, 0.2);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 12px;
    color: #fff;
    padding: 8px;
    text-align: left;
    text-transform: uppercase;
  }

  th:first-child {
    border-top-left-radius: 4px;
    border-left: 0;
  }
  
  th:last-child {
    border-top-right-radius: 4px;
    border-right: 0;
  }
  
  td {
    border-right: 1px solid #c6c9cc;
    border-bottom: 1px solid #c6c9cc;
    padding: 8px;
    font-size: 11px;
  }
  
  td:first-child {
    border-left: 1px solid #c6c9cc;
  }
  
  tr:first-child td {
    border-top: 0;
  }
  
  tr:nth-child(even) td {
    background: #e8eae9;
  }
  
  tr:last-child td:first-child {
    border-bottom-left-radius: 4px;
  }
  
  tr:last-child td:last-child {
    border-bottom-right-radius: 4px;
  }
  
  .header_lead {
  	border-radius: 100%;
    position: running(header);
    width:160px;
    height:80px;
    padding-left: 570px;"
  }
  
  .custom-page-start {
   		margin-top: 100px;
	}
  
  .center {
    	text-align: center;
  }

  .bg-blue {
    background-color: #0073b7 !important;
  }

  .bg-green {
    background-color: #00a65a !important;
  }

  .badge {
    display: inline-block;
    min-width: 10px;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: #777;
    border-radius: 10px;
}
</style>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="">
<title>Report Product Tag</title>
</head>
<body>
  <img src="img/sippng.png" class="header_lead" />
  <br><br>
<h3 class="center">REPORT PRODUCT TAGGING</h3>
<table id="table-report" class="page" class="custom-page-start">
  <thead>
    <tr>
      <th>
        Lead ID
      </th>
      <th>
        Customer 
      </th>
      <th>
        Opty Name
      </th>
      <th>
        Persona
      </th>
      <th>
        Product/Technology
      </th>
      <th>
        Price
      </th>
      <th>
        Nominal (Deal Price)
      </th>
    </tr>
  </thead>
  <tbody>
    <td>ANLA210701</td>
    <td>Lintasarta</td>
    <td>Test Flow Lintas Arta</td>
    <td>Rizki Rahmawan,Johan Ardi Wibisono</td>
    <td>
      <span class="badge bg-blue">Cisco</span>
      <span class="badge bg-blue">F5</span>
      <span class="badge bg-green">IPAM</span>
      <span class="badge bg-green">ADC</span>
    </td>
    <td>
      <span>Rp.4,343,434</span><br>
      <span>Rp.9,500,000</span>
    </td>
    <td>Rp.10,500,000</td>
  </tbody>
  <img src="img/footer.PNG" style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 10px; width: 775px; height: 130px"/>
</table>
</body>
<script type="text/javascript">
</script>
</html>
