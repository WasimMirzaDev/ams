@extends('layouts.app')
@section('content')
<style media="screen">
   #dashboard_menu{
   color:white !important;
   }
   .demo{
   top:10px !important;
   }
   /* tr:hover {
             background-color: #FFFFFF !important;
           } */

 #myInput
 {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}
</style>
<link rel="stylesheet" href="{{asset('css/dashboard-tiles.css')}}">
<div class="container-fluid">
   <div id="tenant_detail">
     <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search Property / Resident" title="Type in a name">
     <table id="myTable" width="100%" class="table-striped">
       <tbody>
         <tr style="background-color:#fed8b1;">
           <th>Property/Address</th>
           <th>Unit/Apartment</th>
           <th>Residential</th>
           <th>Head</th>
           <th style="width:100px;">Amount</th>
           <th>E-Bill <br> Last Entry </th>
         </tr>
     @if(!empty($rd))
       @foreach($rd as $r)
         <tr>
           <td>{{$r->building_name}}</td>
           <td>{{$r->unit_name}}</td>
           <td>{{$r->tenant_name}}</td>
           <td>
             @if(!empty($fee_head))
             <select class="" name="" id="fh_id_{{$r->tu_id}}">
               @foreach($fee_head as $fh)
               <option value="{{$fh->fh_id}}" {{$fh->is_electricbill == 1 ? 'selected' : ''}}>{{$fh->fh_name}}</option>
               @endforeach
             </select>
             @endif
           </td>
           <td style="width:100px;"><input style="width:100px;" type="number" step="any" name="" value="" onblur="save_ebill({{$r->tu_id}}, this.value)"> </td>
           <td id="last_entry_{{$r->tu_id}}" style="color:green;">{{!empty($r->last_entry) ? date('m-d-Y', strtotime($r->last_entry)) : ''}}</td>
         </tr>
       @endforeach

     @endif
     </tbody>
     </table>
   </div>
</div>

<script type="text/javascript">
function save_ebill(tu_id, amt)
{
  if(amt <= 0 || amt == '')
  {
    _error('Amount Must be greater than 0');
    return;
  }
  var fh_id = $('#fh_id_'+tu_id+ ' option:selected').val();
  $(".overlay").show();
  $.ajax({
    url: '/multipay/save',
    method: "post",
    data: {tu_id:tu_id, amt:amt, fh_id:fh_id},
    success: function(response)
    {
      response = JSON.parse(response);
      if(response['success'] == 1)
      {
        _success(response['msg']);
        $("#last_entry_"+tu_id).text(response['last_entry']);
      }
      else
      {
        _error(response['msg']);
      }
      // $("#tab_content").html(response.html);
      $(".overlay").hide();
    }
  });
}

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td2 = tr[i].getElementsByTagName("td")[2];
    if (td || td2) {
      txtValue  = td.textContent || td.innerText;
      txtValue2 = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1
      					||
      txtValue2.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


</script>
@endsection
