//add lead register

/*var url = "http://localhost/sales/trunk/public/salesAddLead"*/

  $('#btn_add_sales').click(function () {
        $('#btn-save').val("add");
        $('#modalSalesLead').trigger("reset");
        $('#modal_lead').modal('show');
    });
  
//create and update
  $("#btn-save").click(function (e) {
       /* $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })*/
        e.preventDefault();
        var formData = {
            lead_id: $('#lead_id').val(),
            contact: $('#contact').val(),
            opp_name: $('#opp_name').val(),
            closing_date : $('#closing_date').val(),
            amount : $('#amount').val(),
            owner : $('#owner').val(),
        }
        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btn-save').val();
        var type = "POST"; //for creating new resource
        var id = $('#lead_id').val();
        var my_url = url;
        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + id;
        }
        console.log(formData);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var product = '<tr id="id' + data.id + '"><td>' + data.id + '</td><td>' + data.contact + '</td><td>' + data.opp_name + '</td><td>' + data.closing_date + '</td><td>' + data.amount + '</td><td>' + data.owner + '</td>';
                product += '<td><button class="btn btn-warning btn-detail open_modal" value="' + data.id + '">Edit</button>';
                product += ' <button class="btn btn-danger btn-delete delete_product" value="' + data.id + '">Delete</button></td></tr>';
                if (state == "add"){ //if user added a new record
                    $('#products-list').append(product);
                }else{ //if user updated an existing record
                    $("#lead_id" + id).replaceWith( product );
                }
                $('#modalSalesLead').trigger("reset");
                $('#modal_lead').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });


/*  var url = "http://localhost/sales/trunk/public/addSolutiondesign"


    $.get(url + '/' + lead_id, function (data) {
            //success data
            console.log(data);
            $('#assesment').val(data.lead_id);
            $('#pov').val(data.pov);
            $('#propossed_design').val(data.pd);
            $('#project_management').val(data.pm);
            $('#maintenance').val(data.ms);
            $('#priority').val(data.priority);
            $('#proyek_size').val(data.project_size);
        })


   */

   function assign(lead_id){
        $('#coba_lead').val(lead_id);
   }