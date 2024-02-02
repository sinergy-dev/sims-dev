@extends('template.main')
@section('tittle')
PMO
@endsection
@section('head_css')
    <!-- Select2 -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <style type="text/css">
        .select2{
            width:100%!important;
        }
        .selectpicker{
            width:100%!important;
        }

        .chartjsTotalProject-table, th, td{
            border-collapse: collapse;
            border: 1px solid black;
            padding: 10px;
        }

        .chartjsTotalProject-thead{
            font-weight: bold;
            text-align: center;
        }

        .chartjsTotalProject-tbody{
            text-align: center;
        }

        .tableDiv{
            display: grid;
        }

        .chartjs-thead{
            font-weight: bold;
        }

        .table{
            border-top: solid 1px;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Project Manager</li>
        </ol>
    </section>

    <section class="content">
        <div class="row" id="BoxId">
            <!--box id-->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Total Project</h3>
                    </div>
                    <div class="box-body chartBoxTotalProject">
                        <canvas id="totalProjectCanvas" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
  <!--       <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Total Nilai Project</h3>
                    </div>
                    <div class="box-body chartBoxTotalNilaiProject">
                        <canvas id="totalNilaiProjectCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Health Status</h3>
                    </div>
                    <div class="box-body chartBoxProjectHealth">
                        <canvas id="ProjectHealthCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Handover to PMO</h3>
                    </div>
                    <div class="box-body chartBoxHandoverProject">
                    <canvas id="handoverCanvas" width="400" height="200"></canvas>  
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Project Status</h3>
                    </div>
                    <div class="box-body chartBoxProjectStatus">
                        <canvas id="projectStatusCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
                
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Project Phase</h3>
                    </div>
                    <div class="box-body chartBoxProjectPhase">
                    <canvas id="projectPhaseCanvas" width="400" height="200"></canvas>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Project Type</h3>
                    </div>
                    <div class="box-body chartBoxProjectType">
                        <canvas id="projectTypeCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Market Segment</h3>
                    </div>
                    <div class="box-body chartBoxMarketSegment">
                        <canvas id="marketSegmentCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Project Value</h3>
                        
                    </div>
                    <div class="box-body chartBoxProjectvalue">
                        <canvas id="projectValueCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Total Nilai Project</h3>
                    </div>
                    <div class="box-body chartBoxTotalNilaiProject">
                        <canvas id="totalNilaiProjectCanvas" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scriptImport')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            DashboardCount()
            createTableProjectType()
            createTableMarketSegment()
            createTableProjectValue()
            createTableTotalNilaiProject()
            createTableProjectPhase()
            createTableProjectStatus()
            createTableProjectValue()
            createTableProjectHealth()
            createTableTotalProject()
            createTableHandoverProject()
        })

        function DashboardCount(){
            console.log("wokee")
            // $("#BoxId").empty()
            var countPmo = []
            var i = 0
            var append = ""
            var colors = []

            $.ajax({
            type:"GET",
            url:"{{url('/PMO/getCountDashboard')}}",
            success: function(result){
                    console.log("beda server")
                    var ArrColors = [{
                        name: 'Initiating',style: 'color:white', color: 'bg-purple', icon: 'fa fa-hand-paper-o',status:"NA",index: 0,value: result.countInitiating
                    },
                    {
                        name: 'Planning',style: 'color:white', color: 'bg-aqua', icon: 'fa fa-tasks',status:"OG",index: 1,value: result.countPlanning
                    },
                    {
                        name: 'Executing',style: 'color:white', color: 'bg-red', icon: 'fa fa-calendar-times-o',status:"DO",index: 2,value: result.countExecuting
                    },
                    {
                        name: 'Closing',style: 'color:white', color: 'bg-primary', icon: 'fa fa-calendar-check-o',status:"ALL",index: 3,value: result.countClosing
                    },
                    {
                        name: 'Done',style: 'color:white', color: 'bg-green', icon: 'fa fa-check-square',status:"DO",index: 2,value: result.countDone
                    },
                    {
                        name: 'Ongoing',style: 'color:white', color: 'bg-orange', icon: 'fa fa-list-ul',status:"ALL",index: 3,value: result.countOnGoing
                    },
                  ]

                  colors.push(ArrColors)

                  $.each(colors[0], function(key, value){
                    var status = "'"+ value.status +"'"
                    append = append + '<div class="col-lg-2 col-xs-12">'
                        append = append + '<div class="small-box '+ value.color +'">'
                        append = append + '    <div class="inner">'
                        append = append + '        <h3>'+ value.value +'</h3>'
                        append = append + '        <p>'+ value.name +'</p>'
                        append = append + '    </div>'
                        append = append + '    <div class="icon">'
                        append = append + '        <i class="'+ value.icon +'" style="'+ value.style +';opacity:0.4"></i>'
                        append = append + '    </div>'
                        append = append + '</div>'
                    append = append + '</div>'
                  id = "count_pmo_"+value.index
                  countPmo.push(id)
                  })

                  $("#BoxId").append(append)
                }
            })
        }

        // var ctxTotalProjectCanvas = document.getElementById("totalProjectCanvas");
        // var ctxHealthStatusCanvas = document.getElementById("healthStatusCanvas");
        // var ctxHandoverCanvas = document.getElementById("handoverCanvas");
        // var ctxProjectStatusCanvas = document.getElementById("projectStatusCanvas");
        // var ctxProjectPhaseCanvas = document.getElementById("projectPhaseCanvas");
        // var ctxProjectValueCanvas = document.getElementById("projectValueCanvas");
        // var ctxTotalNilaiProjectCanvas = document.getElementById("totalNilaiProjectCanvas");


        function createDataDouble(label,datas,datasWIP,backgroundColor,borderColor,className,idCanvas){
            const data = {
            labels: label,
            datasets: [{
                label: 'Done',
                data: datas,
                backgroundColor: [
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565',
                    '#5eb565'
                ],
                borderColor: [
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540',
                    '#404540'                                                  
                ],
                borderWidth: 1,
                minBarLength: 2,
                barThickness: 30,
                },
                {
                    label: 'WIP',
                    data: datasWIP,
                    backgroundColor: [
                        '#3629a6',
                        '#3629a6',                                                
                        '#3629a6',
                        '#3629a6',
                        '#3629a6',
                        '#3629a6',
                        '#3629a6',
                        '#3629a6',
                        '#3629a6'                        
                    ],
                    borderColor: [
                        '#404540',
                        '#404540',                                                      
                        '#404540',
                        '#404540',
                        '#404540',
                        '#404540',
                        '#404540',
                        '#404540',
                        '#404540' 
                    ],
                    borderWidth: 1,
                    minBarLength: 2,
                    barThickness: 30,
                }],
            };

            const config = {
                type:'bar',
                data:data,
                options: {
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true

                        }
                    }
                }
            }

            var ctx_idCanvas = document.getElementById(idCanvas);
            var myChartidCanvas = new Chart(ctx_idCanvas, config);

            const chartBox = document.querySelector('.'+className);
            const tableDiv = document.createElement('DIV');
            tableDiv.setAttribute('class','tableDiv');
            
            const table = document.createElement('TABLE');
            table.classList.add('chartjsTotalProject-table');

            const thead = table.createTHead();
            thead.classList.add('chartjsTotalProject-thead');

            thead.insertRow(0);

            for(let i = 0;i < data.labels.length;i++){
                thead.rows[0].insertCell(i).innerText = data.labels[i];
            }
            thead.rows[0].insertCell(0).innerText = 'Label'

            const tbody = table.createTBody();
            tbody.classList.add('chartjsTotalProject-tbody')

            data.datasets.map((dataset,index) => {

                let value = index + 1;
                let color = ''
                tbody.insertRow(index);
                for (let i = 0; i < data.datasets[0].data.length; i++) {
                    tbody.rows[index].insertCell(i).innerText = dataset.data[i]   
                }

                if (dataset.label == 'WIP') {
                    color = "<div style='background-color:#3629a6;width:15px;height:15px;display:inline;float:left'></div>&nbsp"
                }else{
                    color = "<div style='background-color:#5eb565;width:15px;height:15px;display:inline;float:left'></div>&nbsp"
                }

                tbody.rows[index].insertCell(0).innerHTML = color + '<span style="display:inline">'+ dataset.label +'</span>'
            })
            chartBox.appendChild(tableDiv);
            tableDiv.appendChild(table);
        }

        function createDataSingle(label,datas,backgroundColors,borderColors,labelData,className,idCanvas){
            var formatter = new Intl.NumberFormat(['ban', 'id']);

            var dataSingle_className = {
                labels: label,
                datasets: [{
                    label: labelData,
                    data: datas,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    minBarLength: 2,
                    barThickness: 30,
                }],
            };

            const config = {
              type: 'bar',
              data: dataSingle_className,
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              },
            };

            var ctx_idCanvas = document.getElementById(idCanvas);
            var myChartidCanvas = new Chart(ctx_idCanvas, config);

            const chartBox = document.querySelector('.'+className);
            const tableDiv = document.createElement('DIV');

            tableDiv.setAttribute('class','table-responsive');
            
            const table = document.createElement('TABLE');
            table.setAttribute('class','table')
            table.classList.add('chartjs-table');

            const thead = table.createTHead();
            thead.classList.add('chartjs-thead');

            thead.insertRow(0);

            for(let i = 0;i < dataSingle_className.labels.length;i++){
                thead.rows[0].insertCell(i).innerText = dataSingle_className.labels[i];
            }
            thead.rows[0].insertCell(0).innerText = 'Label'

            const tbody = table.createTBody();
            tbody.classList.add('chartjs-tbody')

            dataSingle_className.datasets.map((dataset,index) => {

                let value = index + 1;
                let color = ''
                tbody.insertRow(index);

                if (className == "chartBoxTotalNilaiProject") {
                    for (let i = 0; i < dataSingle_className.datasets[0].data.length; i++) {
                        tbody.rows[index].insertCell(i).innerText = formatter.format(dataset.data[i])   
                    }
                }else{
                    for (let i = 0; i < dataSingle_className.datasets[0].data.length; i++) {
                        tbody.rows[index].insertCell(i).innerText = dataset.data[i]   
                    }
                }

                color = "<div style='background-color:#3629a6;width:15px;height:15px;display:inline;float:left'></div>&nbsp"

                tbody.rows[index].insertCell(0).innerHTML = color + '<span style="display:inline">'+ dataset.label +'</span>'
            })
            chartBox.appendChild(tableDiv);
            tableDiv.appendChild(table);

            tableDiv.style.paddingTop = "20px"
        }

        // function createTableTotalProject(){
        //     const chartBox = document.querySelector('.chartBoxTotalProject');
        //     const tableDiv = document.createElement('DIV');
        //     tableDiv.setAttribute('class','tableDiv');
            
        //     const table = document.createElement('TABLE');
        //     table.classList.add('chartjsTotalProject-table');

        //     const thead = table.createTHead();
        //     thead.classList.add('chartjsTotalProject-thead');

        //     thead.insertRow(0);

        //     for(let i = 0;i < data.labels.length;i++){
        //         thead.rows[0].insertCell(i).innerText = data.labels[i];
        //     }
        //     thead.rows[0].insertCell(0).innerText = 'Label'

        //     const tbody = table.createTBody();
        //     tbody.classList.add('chartjsTotalProject-tbody')

        //     data.datasets.map((dataset,index) => {

        //         let value = index + 1;
        //         let color = ''
        //         tbody.insertRow(index);
        //         for (let i = 0; i < data.datasets[0].data.length; i++) {
        //             tbody.rows[index].insertCell(i).innerText = dataset.data[i]   
        //         }

        //         if (dataset.label == 'WIP') {
        //             color = "<div style='background-color:#3629a6;width:15px;height:15px;display:inline;float:left'></div>&nbsp"
        //         }else{
        //             color = "<div style='background-color:#5eb565;width:15px;height:15px;display:inline;float:left'></div>&nbsp"
        //         }

        //         tbody.rows[index].insertCell(0).innerHTML = color + '<span style="display:inline">'+ dataset.label +'</span>'
        //     })
        //     chartBox.appendChild(tableDiv);
        //     tableDiv.appendChild(table);
        // }

        function createTableProjectType(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getTotalProjectType')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.project_type))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxProjectType",idCanvas="projectTypeCanvas")
                }
            })
        }

        function createTableMarketSegment(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getMarketSegment')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.market_segment))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxMarketSegment",idCanvas="marketSegmentCanvas")
                }
            })
        }

        function createTableTotalNilaiProject(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getNominalByPeople')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.name))
                    result.data.map(x => datas.push(x.amount))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Total Nilai Project",className="chartBoxTotalNilaiProject",idCanvas="totalNilaiProjectCanvas")
                }
            })
        }

        function createTableProjectPhase(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getProjectPhase')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.label))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxProjectPhase",idCanvas="projectPhaseCanvas")
                }
            })
        }

        function createTableProjectStatus(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getProjectStatus')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.label))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxProjectStatus",idCanvas="projectStatusCanvas")
                }
            })
        }

        function createTableProjectValue(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getProjectValue')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.label))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxProjectvalue",idCanvas="projectValueCanvas")
                }
            })
        }

        function createTableTotalProject(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getTotalProject')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datasDone = []
                    let datasWIP = []
                    let backgroundColor = []
                    let borderColor = []

                    result.map(x => label.push(x.name))
                    result.map(x => datasDone.push(x.finished))
                    result.map(x => datasWIP.push(x.on_going))                    
                    result.map(x => backgroundColor.push("#3629a6"))
                    result.map(x => borderColor.push("#404540"))

                    createDataDouble(label,datasDone,datasWIP,backgroundColor,borderColor,className="chartBoxTotalProject",idCanvas="totalProjectCanvas")
                }
            })
        }

        function createTableProjectHealth(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getProjectHealth')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.label))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxProjectHealth",idCanvas="ProjectHealthCanvas")
                }
            })
        }

        function createTableHandoverProject(){
            $.ajax({
                type:"GET",
                url:"{{url('/PMO/getHandoverProject')}}",
                success:function(result){
                    console.log(result)
                    let label = []
                    let datas = []
                    let backgroundColor = []
                    let borderColor = []

                    result.data.map(x => label.push(x.label))
                    result.data.map(x => datas.push(x.count))
                    result.data.map(x => backgroundColor.push("#3629a6"))
                    result.data.map(x => borderColor.push("#404540"))

                    createDataSingle(label,datas,backgroundColor,borderColor,labelData="Qty",className="chartBoxHandoverProject",idCanvas="handoverCanvas")
                }
            })
        }



    </script>
@endsection