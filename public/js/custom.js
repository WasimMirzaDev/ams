	$(window).load(function() {
$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
});

// datatable scripts
/* COLUMN FILTER  */
	var otable = $('#datatable_fixed_column').DataTable(
		{"pageLength": 25}
);
$(".select2").select2();
$(".select2").css("width", "100%");
	// custom toolbar
	$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');

	// Apply the filter
	$("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {

			otable
					.column( $(this).parent().index()+':visible' )
					.search( this.value )
					.draw();

	});
	/* END COLUMN FILTER */

// end datatable scripts\

// datepicker function start
$('.datepicker').datepicker({
	dateFormat : 'dd-mm-yy',
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	onSelect : function(selectedDate) {
		$('#finishdate').datepicker('option', 'minDate', selectedDate);
	}
});

$('.mydatepicker').datepicker({
	dateFormat : 'dd-mm-yy',
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	onSelect : function(selectedDate) {
		$('#finishdate').datepicker('option', 'minDate', selectedDate);
	}
});



	$(".day-date-picker").datepicker({
		changeYear: false,
		changeMonth: false,
		dateFormat : 'dd',
		prevText : '<i class="fa fa-chevron-left"></i>',
		nextText : '<i class="fa fa-chevron-right"></i>',
		onSelect : function(selectedDate) {
			$('#finishdate').datepicker('option', 'minDate', selectedDate);
		}
	});

$('.month-day-picker').datepicker({
	changeYear: false,
	dateFormat : 'dd-MM',
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
	onSelect : function(selectedDate) {
		$('#finishdate').datepicker('option', 'minDate', selectedDate);
	}
});

// end datepicker

// supplier payments adjust function
function adjustsupplierpayments(){
	var sum = 0;
	if($("#paymentdate").val() == "" || $("#supplier_id").val() == 0){
		$.smallBox({
			title : "<b>Warning..!</b>",
			content : "<i class='fa fa-times fa-2x'></i><i>Please Select Supplier and Date both...</i>",
			color : "#D85454",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 5000
		});
		return 1;
	}
	$(".remaining_amount").each(function(){
		var x = $(this).prop('id');
	 var amountpaid = $("."+x).val();
	 if(amountpaid !== ""){
	 sum = parseInt(sum) + parseInt(amountpaid);
	 if(parseInt($(this).val()) < parseInt(amountpaid)){
		 $("."+x).css("border", "1px solid red");
		 $.smallBox({
			 title : "<b>Warning..!</b>",
			 content : "<i class='fa fa-times fa-2x'></i><i>Invalid Input... Payment amount cannot be greater than Remaining Amount</i>",
			 color : "#D85454",
			 iconSmall : "fa fa-times fa-2x fadeInRight animated",
			 timeout : 5000
		 });
		 return 1;
	 }else{
		 $("."+x).css("border","1px solid #ccc");
	 }
 }
	});

	if($("#totalamountpaid").val() == "" || $("#totalamountpaid").val() == 0){
		$("#totalamountpaid").css("border","1px solid red");
		$.smallBox({
			title : "<b>Warning..!</b>",
			content : "<i class='fa fa-times fa-2x'></i><i>Payment amount is Required...</i>",
			color : "#D85454",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 5000
		});
		return 1;
	}else{
		$("#totalamountpaid").css("border","1px solid #ccc");
	}
	if(parseInt($("#totalamountpaid").val()) !== parseInt(sum)){
		$.smallBox({
			title : "<b>Warning..!</b>",
			content : "<i class='fa fa-times fa-2x'></i><i>Paid amount is not equal to Adjust amount...  <br /> Total Paid = "+ parseInt($("#totalamountpaid").val()) + " Sum of Payments = " +parseInt(sum)+"</i>",
			color : "#D85454",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 5000
		});
			return 1;
	}

}

function validatesaleform(){
	if($("#sale_date").val() == "" || $("#customer_id").val() == 0){
		$.smallBox({
			title : "<b>Warning..!</b>",
			content : "<i class='fa fa-times fa-2x'></i><i>Please Select Customer and Date both...</i>",
			color : "#D85454",
			iconSmall : "fa fa-times fa-2x fadeInRight animated",
			timeout : 5000
		});
		return 1;
	}

	var id = "";
	var rownumber = "";
	// alert('ysss');
	// 	$(".product").each(function(){
	// 	alert($(this).val());
	// });
	$(".product").each(function(){
		if(parseInt($(this).val()) !== 0){
			 id = $(this).prop('id');
			 alert(id);
			 rownumber = $(".row"+id).val();
			if($("#sale_type"+rownumber).val() == 0){
				alert(rownumber);
				// alert($("#sale_type"+rownumber).val());
				// $.smallBox({
				// 	title : "<b>Warning..!</b>",
				// 	content : "<i class='fa fa-times fa-2x'></i><i>Please Select Sale type...</i>",
				// 	color : "#D85454",
				// 	iconSmall : "fa fa-times fa-2x fadeInRight animated",
				// 	timeout : 5000
				// });
				// $("#sale_type"+rownumber).css("border","1px solid red");
				return 1;
			}else{
      $("#sale_type"+rownumber).css("border","1px solid #ccc");
			return 0;
			}
			if($("#sale_price"+rownumber).val() == ""){
				$.smallBox({
					title : "<b>Warning..!</b>",
					content : "<i class='fa fa-times fa-2x'></i><i>Product Sale Price are required...</i>",
					color : "#D85454",
					iconSmall : "fa fa-times fa-2x fadeInRight animated",
					timeout : 5000
				});
			return 1;
		}else{
			return 0;
		}
	}else{
		return 0;
	}
	});
}


});



function save()
{
	$("#dataForm").on('submit',(function(e){
	  let formAction = $('#dataForm').attr('action');
		var route_prefix = $("#route_prefix").val();
		var edit_id = $("input[name=id]").val();
		$(".overlay").show();
		$("button.save").prop("disabled",true);
	   e.preventDefault();
			$.ajax({
				url: formAction, // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(response)   // A function to be called if request succeeds
				{
					$( "#dataForm" ).unbind( "submit");
					if(response['success']==1){
						_success(response['msg']);
						$("button.save").prop("disabled",false);
						var data = response['data'];
						var id = data['id'];
						var edit_button = '<button id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'"     class="btn btn-primary btn-xs" onclick="edit('+id+')" ><i class="fa fa-edit"></i></button>';
						var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';
						var table = $('#datatable_fixed_column').DataTable();
						if(edit_id>0)
						{
							table.row( $("#row_"+id) ).remove().draw();
						}
						var rowNode = table.row.add( [ data['name'] , data['address'], data['units'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
						clear_form();
						$(".overlay").hide();
					}
					else
					{
						var error = "";
						for(var a = 0; a<response['msg'].length; a++)
						{
							error += response['msg'][a] + "<br/>";
						}
						_error(error);
						$("button.save").prop("disabled",false);
						$(".overlay").hide();
					}
				}
			});
	 }));
}


function save1()
{
	$("#dataForm1").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	var route_prefix = $("#route_prefix").val();
		var edit_id = $("input[name=id]").val();
  	$(".overlay").show();
  	$("button.save").prop("disabled",true);
     e.preventDefault();
  		$.ajax({
  			url: formAction, // Url to which the request is send
  			type: "POST",             // Type of request to be send, called as method
  			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  			contentType: false,       // The content type used when sending data to the server.
  			cache: false,             // To unable request pages to be cached
  			processData:false,        // To send DOMDocument or non processed data file it is set to false
  			success: function(response)   // A function to be called if request succeeds
  			{
  				$( "#dataForm1" ).unbind( "submit");
  				if(response['success']==1)
 				{
  					_success(response['msg']);
  					$("button.save").prop("disabled",false);
  					var data = response['data'];
  					var id = data['id'];
  					var edit_button = '<button id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'"     class="btn btn-primary btn-xs" onclick="edit1('+id+')" ><i class="fa fa-edit"></i></button>';
  					var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';
  					var table = $('#datatable_fixed_column').DataTable();
  					if(edit_id>0)
  					{
  						table.row( $("#row_"+id) ).remove().draw();
  					}
  					var rowNode = table.row.add( [ data['number'] , data['name'] , data['building_name'] , data['weekly_rent'] , data['monthly_rent'], data['yearly_rent'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
  					clear_form();
  					$(".overlay").hide();
  				}
  				else
  				{
  					var error = "";
  					for(var a = 0; a<response['msg'].length; a++)
  					{
  						error += response['msg'][a] + "<br/>";
  					}
  					_error(error);
  					$("button.save").prop("disabled",false);
  					$(".overlay").hide();
  				}
  			}
  		});
   }));
}


function save2()
{
	$("#dataForm2").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	var route_prefix = $("#route_prefix").val();
  	$(".overlay").show();
  	$("button.save").prop("disabled",true);
		var edit_id = $("input[name=id]").val();
     e.preventDefault();
  		$.ajax({
  			url: formAction, // Url to which the request is send
  			type: "POST",             // Type of request to be send, called as method
  			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  			contentType: false,       // The content type used when sending data to the server.
  			cache: false,             // To unable request pages to be cached
  			processData:false,        // To send DOMDocument or non processed data file it is set to false
  			success: function(response)   // A function to be called if request succeeds
  			{
  				$( "#dataForm2" ).unbind( "submit");
  				if(response['success']==1)
 					{
  					_success(response['msg']);
  					$("button.save").prop("disabled",false);
  					var data = response['data'];
  					var id = data['id'];
  					var edit_button = '<a id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>';
  					var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';
  					var table = $('#datatable_fixed_column').DataTable();
  					if(edit_id>0)
  					{
  						table.row( $("#row_"+id) ).remove().draw();
  					}
						data['gender'] = data['gender'] == 'm' ? 'Male' : 'Female';
  					var rowNode = table.row.add( [ data['number'], data['name'], data['identity'], data['cell'] , data['country'] , data['city'], data['gender'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
						$("#blah").attr("src", "../app_images/default.jpg");
						$("#male").attr('checked', 'checked');
  					clear_form();

  					$(".overlay").hide();
  				}
  				else
  				{
  					var error = "";
  					for(var a = 0; a<response['msg'].length; a++)
  					{
  						error += response['msg'][a] + "<br/>";
  					}
  					_error(error);
  					$("button.save").prop("disabled",false);
  					$(".overlay").hide();
  				}
  			}
  		});
   }));
}


function save_voucher()
{
	$("#voucher_form").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	var route_prefix = $("#route_prefix").val();
  	$(".overlay").show();
  	$("button.save").prop("disabled",true);
		var edit_id = $("input[name=id]").val();
     e.preventDefault();
  		$.ajax({
  			url: formAction, // Url to which the request is send
  			type: "POST",             // Type of request to be send, called as method
  			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  			contentType: false,       // The content type used when sending data to the server.
  			cache: false,             // To unable request pages to be cached
  			processData:false,        // To send DOMDocument or non processed data file it is set to false
  			success: function(response)   // A function to be called if request succeeds
  			{
  				$( "#voucher_form" ).unbind( "submit");
  				if(response['success']==1)
 					{
  					_success(response['msg']);
						$("#my_modal").modal('hide');
  					$("button.save").prop("disabled",false);
  					var data = response['data'];
  					var id = data['id'];
  					var edit_button = '<button id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'" onclick="get_voucher('+id+')" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></button>';
  					var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';
  					var table = $('#datatable_fixed_column').DataTable();
  					if(edit_id>0)
  					{
  						table.row( $("#row_"+id) ).remove().draw();
  					}
  					var rowNode = table.row.add( [ data['tenant_name'], data['address_name'], data['unit_name'], data['id'], data['date'], data['challan_total'] , data['i_date'] , data['l_date'], data['remarks'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
  					clear_form();
  					$(".overlay").hide();
  				}
  				else
  				{
  					var error = "";
  					for(var a = 0; a<response['msg'].length; a++)
  					{
  						error += response['msg'][a] + "<br/>";
  					}
  					_error(error);
  					$("button.save").prop("disabled",false);
  					$(".overlay").hide();
  				}
  			}
  		});
   }));
}

function check_pending_amount()
{
	$("#credit_customer_error").html("");
	var pending_amt = $("input[name=pending_amt]").val();
	var rct_amt = $("input[name=amount]").val();
	if(parseFloat(rct_amt) > parseFloat(pending_amt))
	{
		$("#credit_customer_error").html("Receiving amount is greater than debit amount. Customer will be credited..");
	}
}

function save_receiving()
{
	$("#receiving_form").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	var route_prefix = $("#route_prefix").val();
  	$(".overlay").show();
  	$("button.save").prop("disabled",true);
		var edit_id = $("input[name=id]").val();
     e.preventDefault();
  		$.ajax({
  			url: formAction, // Url to which the request is send
  			type: "POST",             // Type of request to be send, called as method
  			data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
  			contentType: false,       // The content type used when sending data to the server.
  			cache: false,             // To unable request pages to be cached
  			processData:false,        // To send DOMDocument or non processed data file it is set to false
  			success: function(response)   // A function to be called if request succeeds
  			{
  				$( "#receiving_form" ).unbind( "submit");
  				if(response['success']==1)
 					{
  					_success(response['msg']);
						$("#my_modal").modal('hide');
  					$("button.save").prop("disabled",false);
  					var data = response['data'];
  					var id = data['id'];
						var print_button = '<a target="blank" id="print_'+id+'" href="'+route_prefix+'/print/'+id+'" class="btn btn-success btn-xs" ><i class="fa fa-print"></i></a>';
						var edit_button = print_button +  ' <br/> <button id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'" onclick="get_voucher('+id+')" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></button>';
  					var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';


  					var table = $('#datatable_fixed_column').DataTable();
  					if(edit_id>0)
  					{
  						table.row( $("#row_"+id) ).remove().draw();
  					}
  					var rowNode = table.row.add( [data['tenant_name'], data['address_name'], data['unit_name'], data['vch_id'], data['id'], data['date'], data['amount'] , data['pm_name'], data['remarks'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
						get_receivables();

						$(".input input").val("");
						$("input.form-control").val("");
						$("textarea").val("");
						$("input[name=id]").val(0);

  					$(".overlay").hide();
  				}
  				else
  				{
  					var error = "";
  					for(var a = 0; a<response['msg'].length; a++)
  					{
  						error += response['msg'][a] + "<br/>";
  					}
  					_error(error);
  					$("button.save").prop("disabled",false);
  					$(".overlay").hide();
  				}
  			}
  		});
   }));
}


function save_receivingv2()
{
	$("#receiving_formv2").on('submit',(function(e){
    let formAction = $(this).attr('action');
  	var route_prefix = $("#route_prefix").val();
  	$("button.save").prop("disabled",true);
		var edit_id = $("input[name=id]").val();
     e.preventDefault();
		// return;
		$(".overlay").show();
 	 $.ajax({
 		 url: formAction, // Url to which the request is send
 		 type: "POST",             // Type of request to be send, called as method
 		 data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
 		 contentType: false,       // The content type used when sending data to the server.
 		 cache: false,             // To unable request pages to be cached
 		 processData:false,        // To send DOMDocument or non processed data file it is set to false
 		 success: function(response)   // A function to be called if request succeeds
 		 {
 			 $( "#receiving_formv2" ).unbind( "submit");
 			 if(response['success']==1)
 			 {
 				 _success(response['msg']);
 				 $("#my_modal").modal('hide');
 				 $("button.save").prop("disabled",false);
 				 var data = response['data'];
 				 var id = data['id'];
 				 var print_button = '<a target="blank" id="print_'+id+'" href="'+route_prefix+'/print/'+id+'" class="btn btn-success btn-xs" ><i class="fa fa-print"></i></a>';
 				 var edit_button = print_button +  ' <br/> <button id="edit_'+id+'" href="'+route_prefix+'/edit/'+id+'" onclick="get_voucher('+id+')" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></button>';
 				 var delete_button = '<button id="delete_'+id+'" href="'+route_prefix+'/delete/'+id+'" class="btn btn-danger btn-xs" onclick="del('+id+')"  >X</button>';


 				 var table = $('#datatable_fixed_column').DataTable();
 				 if(edit_id>0)
 				 {
 					 table.row( $("#row_"+id) ).remove().draw();
 				 }
 				 var rowNode = table.row.add( [data['tenant_name'], data['address_name'], data['unit_name'], data['vch_id'], data['id'], data['date'], data['amount'] , data['pm_name'], data['remarks'], edit_button, delete_button ] ).draw().node().id = "row_"+data['id'];
 				 get_receivables();

 				 $(".input input").val("");
 				 $("input.form-control").val("");
 				 $("textarea").val("");
 				 $("input[name=id]").val(0);

 				 $(".overlay").hide();
 			 }
 			 else
 			 {
 				 var error = "";
 				 for(var a = 0; a<response['msg'].length; a++)
 				 {
 					 error += response['msg'][a] + "<br/>";
 				 }
 				 _error(error);
 				 $("button.save").prop("disabled",false);
 				 $(".overlay").hide();
 			 }
 		 }
 	 });
   }));
}


function _error(msg)
{
	$.smallBox({
		title : "<i>"+ msg +"</i>",
		content : "",
		color : "#D85454",
		iconSmall : "fa fa-times fa-2x fadeInRight animated",
		timeout : 8000
	});
}
function _success(msg)
{
	$.smallBox({
		title : "<i>"+ msg +"</i>",
		content : "",
		color : "#659265",
		iconSmall : "fa fa-times fa-2x fadeInRight animated",
		timeout : 8000
	});
}

function edit(id)
{
	$(".overlay").show();
	var formAction = $("#edit_"+id).attr("href");
	$.get(formAction,function(data){
		$("#addnew").click();
		$("input[name=id]").val(data['id']);
		$("input[name=name]").val(data['name']);
		$("textarea[name=address]").text(data['address']);
		$("input[name=units]").val(data['units']);
		$(".overlay").hide();
	});
}
function edit1(id)
{
	$(".overlay").show();
	var formAction = $("#edit_"+id).attr("href");
	$.get(formAction,function(data){
		$("#addnew").click();
		$("input[name=id]").val(data['id']);
		$("input[name=name]").val(data['name']);
		$("input[name=number]").val(data['number']);
		$("input[name=weekly_rent]").val(data['weekly_rent']);
		$("input[name=monthly_rent]").val(data['monthly_rent']);
		$("input[name=yearly_rent]").val(data['yearly_rent']);
		$("textarea[name=description]").text(data['description']);
		$('select[name=building_id]').val(data['building_id']);
		$(".overlay").hide();
	});
}


function edit2(id)
{
	$(".overlay").show();
	var formAction = $("#edit_"+id).attr("href");
	$.get(formAction,function(data){
		$("#addnew").click();
		$("input[name=id]").val(data['id']);
		$("input[name=name]").val(data['name']);
		$("input[name=number]").val(data['number']);
		$("input[name=cell]").val(data['cell']);
		$("input[name=email]").val(data['email']);
		$("input[name=country]").val(data['country']);
		$("input[name=city]").val(data['city']);
		$("input[name=identity]").val(data['identity']);
		$("textarea[name=additional_info]").text(data['additional_info']);
		if(data['gender'] == "f")
		{
			$("#female").attr('checked', 'checked');
		}
		else
		{
			$("#male").attr('checked', 'checked');
		}
		$("#blah").attr("src", data["file"]);

		// $.get(data['dboy_image']).done(function() {
		// 	$("#dboy_image").attr("src",data['dboy_image']);
		// }).fail(function() {
		// 	$("#dboy_image").attr("src","images/upload.png");
		// });


		$(".overlay").hide();
	});
}


function del(id)
{
    $("#row_"+id).css('background-color', ' #FFA8B5');
	$.SmartMessageBox({
		title : "Warning!",
		content : "Are you sure ? Do you want to delete this?",
		buttons : '[No][Yes]'
	}, function(ButtonPressed) {
		if (ButtonPressed === "Yes") {
			$(".overlay").show();
			var formAction = $("#delete_"+id).attr("href");
		 $.get(formAction,function(data){
			 if(data['success'] == 1)
			 {
				 $("#row_"+id).remove();
				 _success(data['msg']);
			 }
			 else
			 {
				 _error(data['msg']);
			 }
			 $(".overlay").hide();
		 });
		}
		if (ButtonPressed === "No")
		{
		  $("#row_"+id).css('background-color', 'white');
		 _error('You pressed No');
		}
	});
}

function clear_form()
{
	$(".overlay").show();
	$(".input input").val("");
	$("input.form-control").val("");
	$("textarea").val("");
	$("input[name=id]").val(0);
	$("select.select2").select2("val","");
	 $("#img").attr("src","images/upload.png");
	$(".overlay").hide();
}


 // start file upload scripts
 function readURL(input) {
 						if (input.files && input.files[0]) {
 								var reader = new FileReader();

 								reader.onload = function (e) {
 										$('#blah')
 												.attr('src', e.target.result)
 												.width(235)
 												.height(200);
 												$("#browse_img").val(e.target.result);
 								};

 								reader.readAsDataURL(input.files[0]);
 						}
 				}

 				// end file upload scripts


				// show brands of category start

				function showBrands(id){
					$.get('showBrands',{id:id},
	      function (data) {
	      $("#brands").html(data);
	    });
		}


		// counttotalprice function start
		function counttotalprice(){
			var totalprice = 0;
			$(".productviseprice").each(function(){
				var eachprice = $(this).val();
				totalprice = parseInt(totalprice) + parseInt(eachprice);
			});
					$(".totalprice").html(totalprice);
					$(".totalprice").val(totalprice);
					//this function is called inside productvisecountprice function
		}

// productvise price count function

		function productvisecountprice(productnum){
				let qty   = $("#qty_"+productnum).val();
				let price = $("#price_"+productnum).val();
				if(qty == ""){
					qty = 0;
				}
				if(price == ""){
					price = 0;
				}
				let productviseprice = parseInt(qty) * parseInt(price);
				$("#productviseprice_"+productnum).val(productviseprice);
				if(productviseprice > 0){
					$("#total_"+productnum).html(productviseprice);
				}else{
					$("#total_"+productnum).html("");
				}
				counttotalprice();
		}


		// products detail engine number and chases number inputs generating dynamic function start
			function addproductdetail(qty, productnum){
				productvisecountprice(productnum);
				let qtyval = $("#qty_"+productnum).val();
				if(qtyval < 0){
					$("#qty_"+productnum).val("");
				}
				let totalquantity = 0;
				$(".purchasequantity").each(function(){
					eachquantity = $(this).val();
					if(eachquantity == ""){
						eachquantity = 0;
					}
					totalquantity = parseInt(totalquantity) + parseInt(eachquantity);
				});
		$("#totalquantity").html(totalquantity);
				// alert(qtyval);
				if((qty == "") || (qtyval == "0")){
						$("#product_details_"+productnum).html("");
				}
				var qty = parseInt(qty);
				if(Number.isInteger(qty) && qtyval > 0){
					var numofinputs = 1*qty;
					let inputs = "<tr><th>Engine No</th><th>Chases No</th></tr>";
					for (var i = 0; i < numofinputs; i++) {
						inputs += "<tr><td><input type='text' class='form-control' style='text-align:right;' name='engine_no[]'></td><td><input type='text' class='form-control' style='text-align:right;' name='chases_no[]'></td></tr>";
					}
					$('#product_details_'+productnum).html(inputs);
				}
			}
			// product detail function end



			// adding dynamic input fields for purchase more products
			let productnum = 5;
			function addmoreproducts(){
				var space = "                 ";
				productnum = parseInt(productnum) + 1;
			let products = $("#product_1").html();
			var moreproducts = "<tr id='row_"+productnum+"' class='additionalrow'><td><input type='hidden' value='0' id='productviseprice_"+productnum+"' class='productviseprice'><span class='additionalnum'>"+productnum+"</span>&nbsp;&nbsp;&nbsp;&nbsp;<button class='btn btn-sm btn-danger' onclick='removerow("+productnum+",6)'>X</button></td><td><select class='form-control'>"+products+"</select></td>"+
			"<td><input type='number' class='form-control purchaseprice' style='text-align:right;' min=1 name='purchase_price' id='price_"+productnum+"' onkeyup='productvisecountprice("+productnum+")' onmouseup='productvisecountprice("+productnum+")'></td>"+
			"<td><input type='number' class='form-control purchasequantity' style='text-align:right' min=1 max=999 name='product_quantity[]' id='qty_"+productnum+"' onkeypress='if(this.value.length == 3) return false;'  onkeyup='addproductdetail(this.value, "+productnum+")' onmouseup='addproductdetail(this.value, "+productnum+")'></td>"+
			"<td id='total_"+productnum+"'></td><td id='product_details_"+productnum+"'></td></tr>";
			$("#moreproducts").append(moreproducts);
			removerow(0,6);
			}
			// END dynamic input fields for purchase more products

			// dynamic rows remove function start
			function removerow(rownum, x){
				$("#row_"+rownum).remove();
				$(".additionalrow").each(function(){
					$(this).children().children('.additionalnum').html(x);
					x = parseInt(x) + 1;
				});
				CalculateOrderTotal(rownum);
				counttotalprice();
			}

		// dynamic adding expenses and incomes rows
		let expensenum = $("#total_expenses");
		var runfirsttimeexp = 1;
		function addmoreexpenses(){
			if(runfirsttimeexp == 1){
				expensenum = $("#total_expenses").val();
			}
			  var total_expenses = $("#total_expenses").val();
				expensenum = parseInt(expensenum) + 1;
				let expensetype = $("#expensetype_1").html();
				var moreexpenses = "<tr id='row_"+expensenum+"' class='additionalrow'>"+
														"<td><span class='additionalnum' style='display:inline-block;width:15px; margin-right:12px;'>"+expensenum+"</span>  <button class='btn btn-sm btn-danger' onclick='removerow("+expensenum+","+total_expenses+")'>X</button></td>"+
														"<td> <select class='form-control' name='expensetype_id[]' id='exptype_"+expensenum+"'>"+expensetype+"</select></td>"+
														"<td> <input type='number' name='expense_amount[]' min='1' style='text-align:right;' class='form-control'> </td>"+
														"<td> <input type='text'   name='expense_description[]' class='form-control'></td>"+
														"</tr>";
				$("#moreexpenses").append(moreexpenses);
				$("#exptype_"+expensenum+ " option:first").prop('selected', true);

				removerow(0,$("#total_expenses").val());
				runfirsttimeexp = parseInt(runfirsttimeexp) + 1;
		}


		// dynamic adding incomes and incomes rows

		// let incomenum = 10;
		let incomenum = $("#total_incomes").val();
		var runfirsttimeinc = 1;
		function addmoreincomes(){
				if(runfirsttimeinc == 1){
					incomenum = $("#total_incomes").val();
				}
				var total_incomes = $("#total_incomes").val();
				incomenum = parseInt(incomenum) + 1;
				let incometype = $("#incometype_1").html();
				var moreincomes = "<tr id='row_"+incomenum+"' class='additionalrow'>"+
														"<td><span class='additionalnum' style='display:inline-block;width:15px; margin-right:12px;'>"+incomenum+"</span>  <button class='btn btn-sm btn-danger' onclick='removerow("+incomenum+","+total_incomes+")'>X</button></td>"+
														"<td> <select class='form-control' name='incometype_id[]' id='incometype_"+incomenum+"'>"+incometype+"</select></td>"+
														"<td> <input type='number' name='income_amount[]' min='1' style='text-align:right;' class='form-control'> </td>"+
														"<td> <input type='text'   name='income_description[]' class='form-control'></td>"+
														"</tr>";
				$("#moreincomes").append(moreincomes);
				$("#incometype_"+incomenum+ " option:first").prop('selected', true);
				removerow(0,$("#total_incomes").val());
				runfirsttimeinc = parseInt(runfirsttimeinc) + 1;
		}

		// resetting rows function
		function resetrow(row){
			$(".input_"+row).val("");
			$(".select_"+row+ " option:first").prop('selected',true);
		}

		// paysupplier function

		function paysupplier(id){
			// alert(id);
			// return;
			var request = $("#requestpage").val();
				$.post(request+"showdetail",{supplier_id:id},function(html){
					$("#paymentdetail").html(html);
				}
			)
		}

		function showsupplieraccount(){
			let request = $("#requestpage").val();
			let supplier_id = $("#supplier_id").val();
			let supplier_name = $("#supplier_id option:selected").html();
			// $("#suppliername").html(supplier_name);
			$.post(request+"showsupplieraccount",{supplier_id:supplier_id},function(feedback){
				$("#summery").html(feedback);
			});
		}

		function showcustomeraccount(){
			let request = $("#requestpage").val();
			let customer_id = $("#customer_id").val();
			let customer_name = $("#customer_id option:selected").html();
			$.post(request+"showcustomeraccount",{customer_id:customer_id},function(feedback){
				$("#summery").html(feedback);
			});
		}

		function showdailybalancesheet(){
			let request = $("#requestpage").val();
			let date = $("#balancesheetdate").val();
			let openingbalance = $("#openingbalance").val();
			$.post(request+"show",{date:date, openingbalance:openingbalance},function(feedback){
				$("#dailybalancesheet").html(feedback);
			});
		}

		// showing engine numbers against products
		function showengno(product_id, row_num, id){
			var request = $("#requestpage").val();
			$.get(request+'showengno', {product_id:product_id}, function(options){
				// var id = $("input[name=id]").val();
				$("#engineno_"+row_num).html("");
				$("#engineno_"+row_num).html(options);
			});
			return true;
		}



	// showing packages for sale installment
	function showpackages(sale_type, row_num){
		var request = $("#requestpage").val();
		if(sale_type == 'inst'){
			$.get(request+"showpackages", {show_package:true}, function(packages){
				$("#package_id"+row_num).html(packages);
				$("#package_id"+row_num).html(packages);
			});
		}else{
			$("#package_id"+row_num).html("<option selected disabled>Select</option>");
		}
	}

	// adding more dynamic sale products
	let saleproductnum = $("#total_products").val();
	var runfirsttimepro = 1;
	function addmoresaleproducts(){
		if(runfirsttimepro == 1){
			saleproductnum = $("#total_products").val();
		}
			var total_products = $("#total_products").val();
			saleproductnum = parseInt(saleproductnum) + 1;
			let products = $("#product_1").html();
			var moreproducts = "<tr id='row_"+saleproductnum+"' class='additionalrow'>"+
													"<td><span class='additionalnum' style='display:inline-block;width:15px; margin-right:12px;'>"+saleproductnum+"</span>  <button class='btn btn-sm btn-danger' onclick='removerow("+saleproductnum+","+total_products+")'>X</button></td>"+
													"<td> <input type='hidden' class='rowproduct_"+saleproductnum+"' value='"+saleproductnum+"'> <select class='form-control product' name='product_id[]' onchange='showengno(this.value, "+saleproductnum+")' id='product_"+saleproductnum+"'>"+products+"</select></td>"+
													"<td><select class='form-control' name='engine_no[]' id='engineno_"+saleproductnum+"'><option value='0'>Select</option></select></td>"+
													"<td><select class='form-control' name='sale_type[]' id='sale_type"+saleproductnum+"' onchange='showpackages(this.value, "+saleproductnum+")'><option value='0'>Select</option><option value='cash'>Cash</option><option value='inst'>Installment</option></select></td>"+
													"<td><select class='form-control' name='package_id[]' id='package_id"+saleproductnum+"'><option value='0'>Select</option></select></td>"+
													"<td> <input type='number' name='sale_price[]' min='1' id='sale_price"+saleproductnum+"' style='text-align:right;' onmouseup='CalculateOrderTotal("+saleproductnum+")' onkeyup='CalculateOrderTotal("+saleproductnum+")' class='form-control saleprice'> </td>"+
													"<td> <input type='number' name='sale_advance[]' min='1' style='text-align:right;' class='form-control'> </td>"+
													"<td></td>"+
													"</tr>";
			$("#moreproducts").append(moreproducts);
			$("#product_"+saleproductnum+ " option:first").prop('selected', true);

			removerow(0,$("#total_products").val());
			runfirsttimepro = parseInt(runfirsttimepro) + 1;
	}


// showing customer detail for recipients
	function showcustomerdetail(customerid){
		var request = $("#requestpage").val();
			$.post(request+"showdetail",{customerid:customerid},function(html){
				$("#paymentdetail").html(html);
			}
		)
	}

// calculating total for sale or order
function CalculateOrderTotal(rownum){
	var orderTotal = 0;
	$(".saleprice").each(function(){
		orderTotal = orderTotal+parseInt($(this).val() || 0);
	});
	$("input[name=sale_total]").val(orderTotal);
}

// toggle reports

function toggleThis(id,x){
	$("#"+id).slideToggle();
	if($(x).html() == "+"){
		$(x).html("-");
		return;
	}else{
		$(x).html("+");
		return;
	}
}

// profit Loss
 function showProfitLoss(){
	 let request = $("#requestpage").val();
	 let from_date = $("input[name=from_date]").val();
	 let to_date =   $("input[name=to_date]").val();
	 if(from_date !== "" && to_date !== ""){
		 $.get(request+"showProfitLoss", {from_date:from_date, to_date:to_date, showProfitLoss:true}, function(profitLossReport){
			 $("#profitLoss").html(profitLossReport);
		 });
	 }
 }
 function isCash()
 {
   var is_cash = $("#pm_id option:selected").attr('is_cash');
   if(is_cash == 1)
   {
     $("#cheque_no").hide();
   }
   else
   {
     $("#cheque_no").show();
   }
 }
