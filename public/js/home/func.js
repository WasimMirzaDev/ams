function save_receiving_home()
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
         window.open('receivings/print/'+id, '_blank');
         $(".overlay").hide();
         get_receivables();
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
