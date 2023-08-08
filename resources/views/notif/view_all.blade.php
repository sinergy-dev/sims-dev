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

    .callout {
        margin: 0px!important;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('css/pagination-custom.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('content')
<section class="content">
	<div class="row">
        <div class="col-md-9 col-xs-12">
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
                    addListUnRead(snapshot_dump[keys[i]],keys[i])
                }
                else if(snapshot_dump[keys[i]].status == "read" && Math.round(moment().diff(moment.unix(snapshot_dump[keys[i]].date_time).format("YYYY-MM-DD"), 'month', true)) < 3){
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
        cssStyle: "compact-theme",

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
        if (data.date_time == null) {
            date_time = ""
        }else{
            date_time = moment(data.date_time,"X").fromNow()
        }


        if (!data.opty_name == false) {
            lead_id = data.lead_id + ' - '

            lead_id = lead_id + ' - '
            if (data.opty_name.length > 30) {
                opty_name = data.opty_name.substring(0, 25) + '...'
            }else{
                opty_name = data.opty_name
            }
        }else{
            opty_name = data.title
            lead_id = ""
        }

        if (data.result != 'DRAFT') {
            append = append + '<tr class="MyClass" data-value="'+ data.status +'" data-value2="'+ data.company+'" data-value4="'+ data.module +'" data-value3="'+ lead_id+'" data-href="'+ "{{url('project/detailSales')}}/" + lead_id +'" value-id="'+index+'" onclick="notifView('+ "'" + index +  "'" + ')">'
            append = append + '<td class="cursor-pointer" style="color:grey">'
            append = append + '<div class="callout callout-default">'
            append = append + '<div class="user-block">'
            append = append + '    <img class="img-circle" src="{{asset("img/logopng.png")}}" alt="User Image">'
            append = append + '    <span class="username">'+ lead_id + opty_name 
            append = append + '<span class="label pull-right" style="background-color:'+ data.heximal +'">'+ data.result +'</span>'
            append = append + '     </span>'

            append = append + '    <span class="description">'+ date_time +'</span>'
            append = append + '</div>'
            append = append + '</div>'
            append = append + '</td>'
            append = append + '</tr>'   
        }
        
        $("#list-content").append(append)

        // $(".MyClass[data-id='"+ index +"']").click(function(){
        //     if ($(this).data("value4") == "draft") {
        //         if (data.result == "DRAFT") {
        //             window.location.href = "{{url('admin/draftPR')}}"
        //         }else{
        //             window.location.href = "{{url('admin/detail/draftPR')}}/"+data.id_pr
        //         }
        //     }else{
        //         if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
        //             if ($(this).data("value") == 'INITIAL') { 
        //                 window.location.href = "{{url('project')}}/"

        //                 localStorage.setItem("lead_id",$(this).data("value2"))
        //                 localStorage.setItem("status","unread")
        //             }else{
        //                 localStorage.setItem("status","read")
     
        //                 window.location.href = $(this).data("href")
        //             }                        
        //         }else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
        //             if ($(this).data("value") == 'read') {
        //                 window.location.href = "{{url('salesproject')}}"
        //                 localStorage.setItem("lead_id",$(this).data("value2"))
        //                 localStorage.setItem("status","read")
        //             }else{
        //                  window.location.href = "{{url('salesproject')}}#submitIdProject/"+$(this).data("value3")
        //             }
        //         }else{
        //             window.location.href = $(this).data("href")
        //         }
                
        //     }

        //      // window.location = $(this).data("href");
        //      readNotification($(this).data("id"))
        // })

    }

    function addListUnRead(data,index){
        
        var datas = data.module

        var append = ""
        if (data.date_time == null) {
            date_time = ""
        }else{
            date_time = moment(data.date_time,"X").fromNow()
        }

        if (!data.opty_name == false) {
            lead_id = data.lead_id + ' - '
            if (data.opty_name.length > 30) {
                opty_name = data.opty_name.substring(0, 25) + '...'
            }else{
                opty_name = data.opty_name
            }
        }else{
            opty_name = data.title
            lead_id = ""
        }

        append = append + '<tr class="MyClass" data-value="'+ data.status +'" data-value2="'+ data.company+'" data-value4="'+ data.module +'" data-value3="'+ lead_id+'" data-href="'+ "{{url('project/detailSales')}}/" + lead_id +'" value-id="'+index+'" onclick="notifView('+ "'" + index +  "'" + ')">'
        append = append + '<td class="cursor-pointer" style="color:grey">'
        append = append + '<div class="callout callout-info" style="background-color: #7dc6e3!important;color:white">'
        append = append + '<div class="user-block">'
        append = append + '    <img class="img-circle" src="{{asset("img/logopng.png")}}" alt="User Image">'
        append = append + '    <span class="username">'+ lead_id + opty_name 
        append = append + '<span class="label pull-right" style="background-color:'+ data.heximal +'">'+ data.result +'</span>'
        append = append + '     </span>'

        append = append + '    <span class="description" style="color:white!important">'+ date_time +'</span>'
        append = append + '</div>'
        append = append + '</div>'
        append = append + '</td>'
        append = append + '</tr>'

        $("#list-content").append(append)

        // $(".MyClass[data-id='"+ index +"']").click(function(){
            
        // })

    } 

    function notifView(index){
        var data = snapshot_dump[index]
        if (data.module == "draft") {
            if (data.result == "DRAFT") {
                if ("{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Procurement")->exists()}}" || "{{App\RoleUser::where("user_id",Auth::User()->nik)->join("roles","roles.id","=","role_user.role_id")->where('roles.name',"BCD Manager")->exists()}}") {
                    var url = "{{url('admin/draftPR')}}?status=draft&no_pr="+data.id_pr
                }else{
                    var url = "{{url('admin/draftPR')}}"

                }
                window.location.href = url
            }else{
                window.location.href = "{{url('admin/detail/draftPR')}}/"+data.id_pr
            }
        }else{
            if ("{{Auth::User()->id_division}}" == 'TECHNICAL PRESALES') {
                if ($(this).data("value") == 'INITIAL') { 
                    window.location.href = "{{url('project')}}/"

                    localStorage.setItem("lead_id",data.lead_id)
                    localStorage.setItem("status","unread")
                }else{
                    localStorage.setItem("status","read")
 
                    window.location.href = "{{url('project/detailSales')}}/"+ data.lead_id
                }                        
            }else if ("{{Auth::User()->id_division}}" == 'FINANCE') {
                if ($(this).data("value") == 'read') {
                    window.location.href = "{{url('salesproject')}}"
                    localStorage.setItem("lead_id",data.lead_id)
                    localStorage.setItem("status","read")
                }else{
                     window.location.href = "{{url('salesproject')}}#submitIdProject/"+data.id_pid
                }
            }else{
                window.location.href = "{{url('project/detailSales')}}/"+ data.lead_id
            }
        }
        

        // window.location = $(this).data("href");
        readNotification(index)
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

            if (!data.module == false) {
                firebase.database().ref('notif/web-notif/' + index).set({
                    to: data.to,
                    id_pr: data.id_pr,
                    title: data.title,
                    heximal: data.heximal,
                    status: "read",
                    result : data.result,
                    showed : "true",
                    date_time : data.date_time,
                    module:"draft"
                });
            }else{

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
            }
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
                  // console.log(key)
                var data = childSnapshot.val();
                if(data.status == "unread"){
                    firebase.database().ref('notif/web-notif/' + key).once('value').then(function(snapshot) {
                        // 
                        var data = snapshot.val()

                        if (!data.module == false) {
                            firebase.database().ref('notif/web-notif/' + key).set({
                                to: data.to,
                                id_pr: data.id_pr,
                                title: data.title,
                                heximal: data.heximal,
                                status: "read",
                                result : data.result,
                                showed : "true",
                                date_time : data.date_time,
                                module:"draft"
                            });
                        }else{
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
                        }

                        if (data.id_pid == null || data.date_time == null) {
                            id_pid = ""
                            date_time = ""

                        }else{
                            id_pid = data.id_pid 
                            date_time = data.date_time

                        }
                    })
                }               
            });
        });

        window.location = "{{url('/notif_view_all')}}"
             
    }

</script>
@endsection
