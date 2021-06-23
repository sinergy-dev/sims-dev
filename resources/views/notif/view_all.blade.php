@extends('template.main')
@section('head_css')
<style type="text/css">
	.cursor-pointer{
		cursor: pointer;
	}

	th.list-item:hover{
		background-color: #cedeeb!important;
		color: black!important;
	}

	a {
    	text-decoration: none !important;
        color: none!important;
	}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('css/pagination-custom.css')}}">
@endsection
@section('content')
<section class="content">
	<div class="row">
        <div class="col-md-6 col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Notification <i class="fa fa-bell" aria-hidden="true"></i></h3>
              <a href="#" onclick="btnReadAll()"><span class="pull-right">read all</span></a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
				<table class="table table-hover">
		            <tbody id="list-content">
		            </tbody>
		        </table>
            </div>
            <div id="paginationList">
            </div>
          </div>
        </div>
      </div>
</section>
@endsection
@section('scriptImport')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://bilalakil.github.io/bin/simplepagination/js/jquery.simplePagination.js"></script>
<!-- <script src="https://pagination.js.org/dist/2.1.5/pagination.min.js"></script>
<script type="text/javascript" src="https://pagination.js.org/dist/2.1.5/pagination.js"></script> -->
@endsection
@section('script')
<script type="text/javascript">
	firebase.database().ref('notif/web-notif').orderByChild("date_time").on('value',function(snapshot){

		snapshot_dump = snapshot.val()
  	 	var count = 0

  	 	var keys = Object.keys(snapshot_dump)
  	 	keys = keys.reverse()

  	 	for (var i = 0; i < keys.length; i++) {
  	 		if (snapshot_dump[keys[i]].to == "{{Auth::user()->email}}"){
                 const oneday = 60 * 60 * 24 * 1000

                 var dates = moment(snapshot_dump[keys[i]].date_time)

	 			if(snapshot_dump[keys[i]].status == "unread"){
                    console.log(dates.diff(moment()))
                    addListUnRead(snapshot_dump[keys[i]],keys[i])
                }else if(snapshot_dump[keys[i]].status == "read"){
                	addListRead(snapshot_dump[keys[i]],keys[i])
                }

  	 		}
  	 	}

        var rows= $('table #list-content tr.MyClass');
        var items = rows.length

        var perPage = 10

        rows.slice(perPage).hide()

        $("#paginationList").pagination({
        items: items,
        itemsOnPage: perPage,
        cssStyle: "light-theme",

        // This is the actual page changing functionality.
        onPageClick: function(pageNumber) {
            // We need to show and hide `tr`s appropriately.
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
                // if (pageNumber == 1) {
                //     $(".title-pagination").html("Recent Notification <i class='fa fa-bell' aria-hidden='true'></i>")
                // }else{
                //     $(".title-pagination").html("Earlier Notification <i class='fa fa-bell' style='color:grey' aria-hidden='true'></i>")
                // }

                // We'll first hide everything...
                rows.hide()                 // ... and then only show the appropriate rows.
                     .slice(showFrom, showTo).show();
            }
        });
	})

	function addListRead(data,index){
        var append = ""
        append = append + '<tr class="MyClass" data-value="'+ data.status +'" data-value2="'+ data.company+'" data-value3="'+ data.lead_id+'" data-href="'+ "{{url('detail_project')}}/" + data.lead_id +'" value-id="'+index+'">'
		append = append + '<td class="cursor-pointer" style="color:grey">'
		append = append +  '<i class="fa fa-envelope" aria-hidden="true"></i> ' + data.lead_id + ' - ' +data.opty_name + '<span style="font-size: 12px;text-align: center;align-content: center;float:right">'+moment(data.date_time,"X").fromNow()+'</span>'
		append = append + '</td>'
		append = append + '</tr>'
	   
        $("#list-content").append(append)

        $(".MyClass").click(function(){
            if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
                if ($(this).data("value") == 'INITIAL') { 
                    window.location.href = "{{url('project')}}/"

                    localStorage.setItem("lead_id",$(this).data("value2"))
                    localStorage.setItem("status","unread")
                }else{
                    localStorage.setItem("status","read")
 
                    // window.location.href = $(this).data("href")
                }                        
            }else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
                window.location.href = "{{url('salesproject')}}"
                localStorage.setItem("lead_id",$(this).data("value2"))
                localStorage.setItem("status","read")
            }else{
                window.location.href = $(this).data("href")
       
            }

             // window.location = $(this).data("href");
             readNotification($(this).data("id"))
        })

    }

    function addListUnRead(data,index){
        var append = ""
        append = append + '<tr class="MyClass" data-value3="'+ data.id_pid+'" data-value2="'+data.lead_id+'" data-value="'+ data.result +'" data-id="'+ index +'" data-href="'+ "{{url('detail_project')}}/" + data.lead_id +'">'
		append = append + '<th class="list-item cursor-pointer" style="background-color: #3490dc;color:white">'
        append = append + '<i class="fa fa-envelope" aria-hidden="true"></i> ' + data.lead_id + ' - ' + data.opty_name + '<span style="font-size: 12px;text-align: center;align-content: center;float:right">'+moment(data.date_time,"X").fromNow()+'</span>'
        append = append + '</th>'
		append = append + '</tr>'
        $("#list-content").append(append)

        $(".MyClass").click(function(){
            if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
                if ($(this).data("value") == 'INITIAL') { 
                    window.location.href = "{{url('project')}}/"

                    localStorage.setItem("lead_id",$(this).data("value2"))
                    localStorage.setItem("status","unread")
                }else{
                    localStorage.setItem("status","read")
 
                    window.location.href = $(this).data("href")
                }                        
            }else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
                if ($(this).data("value") == 'read') {
                    window.location.href = "{{url('salesproject')}}"
                    localStorage.setItem("lead_id",$(this).data("value2"))
                    localStorage.setItem("status","read")
                }else{
                     window.location.href = "{{url('salesproject')}}#submitIdProject/"+$(this).data("value3")
                }
            }else{
                window.location.href = $(this).data("href")
       
            }

             // window.location = $(this).data("href");
             readNotification($(this).data("id"))
        })

    } 

    function readNotification(index){

        firebase.database().ref('notif/web-notif/' + index).once('value').then(function(snapshot) {
            var data = snapshot.val()

            if (data.id_pid == null || data.date_time == null || data.company == null) {
                id_pid = ""
                date_time = ""
                company = ""
            }else{
                id_pid = data.id_pid 
                date_time = data.date_time
                company = data.company

            }

            firebase.database().ref('notif/web-notif/' + index).set({
                date_time:date_time,
                to: data.to,
                lead_id: data.lead_id,
                opty_name: data.opty_name,
                heximal: data.heximal,
                status: "read",
                result : data.result,
                showed : "true",
                id_pid : id_pid,
                company : company
            });
        })
    
    }

    function btnReadAll(){
        var query = firebase.database().ref("notif/web-notif").orderByChild('to').equalTo('{{Auth::User()->email}}');
            query.once("value")
              .then(function(snapshot) {
                snapshot.forEach(function(childSnapshot) {
                  // key will be "ada" the first time and "alan" the second time
                var key = childSnapshot.key;
                  // childData will be the actual contents of the child
                var data = childSnapshot.val();
                if(data.status == "unread"){
                    firebase.database().ref('notif/web-notif/' + key).once('value').then(function(snapshot) {
                        // console.log(snapshot.val())
                        var data = snapshot.val()
                        if (data.id_pid == null || data.date_time == null) {
                            id_pid = ""
                            date_time = ""

                        }else{
                            id_pid = data.id_pid 
                            date_time = data.date_time

                        }

                        firebase.database().ref('notif/web-notif/' + key).set({
                            date_time:date_time,
                            to: data.to,
                            lead_id: data.lead_id,
                            opty_name: data.opty_name,
                            heximal: data.heximal,
                            status: "read",
                            result : data.result,
                            showed : "true",
                            id_pid : id_pid
                        });
                    })
                }               
            });
        });

        window.location = "{{url('/notif_view_all')}}"
             
    }

</script>
@endsection
