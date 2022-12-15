@extends('layouts.app')
@section('content')
<style media="screen">
   #dashboard_menu{
   color:white !important;
   }
   .demo{
   top:10px !important;
   }
</style>
<link rel="stylesheet" href="{{asset('css/dashboard-tiles.css')}}">
<div class="container-fluid">
  <div class="container page-heading">
     <h1>Dashboard - Apartment Management System</h1>
  </div>
  <br>
  <div class="container" style="width:60%; margin:0 auto;">
    <div class="row">
      <div class="col col-md-6">
        <select class="select2" name="tenant_type">
          <option value="">All</option>
          <option value="AND rem_amt>0">Debitor</option>
          <option value="AND rem_amt<0">Creditor</option>
          <option value="AND rem_amt = 0">0 Balance</option>
          <option value="AND t.name is null">Vacant</option>
        </select>
      </div>
      <div class="col col-md-6">
        <input type="text" name="tenant_name" class="form-control" value="" placeholder='Search Tenant Name' style="border:1px solid grey;">
      </div>
    </div>
    <div class="row" style="margin-top:10px;">
      <div class="col col-md-12">
        <button type="button" onclick="filter_tenant()" class="btn btn-block btn-primary" name="button">Filter <i class="fa fa-search fa-lg"></i> </button>
      </div>
    </div>
  </div>
  <br/>
   <div class="row" style="display:none;">
      <div class="col-md-3">
         <a class="info-tiles tiles-green has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Total Receiveables</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">$0</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>
      <div class="col-md-3">
         <a class="info-tiles tiles-blue has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Total Payable</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">$0</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>
      <div class="col-md-3">
         <a class="info-tiles tiles-midnightblue has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Total Tenants</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">{{$total_tenants}}</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>
      <div class="col-md-3">
         <a class="info-tiles tiles-danger has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Complaints</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">0</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>
      <div class="col-md-3">
         <a class="info-tiles tiles-info has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Total Addresses</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">{{$total_buildings}}</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>
      <div class="col-md-3">
         <a class="info-tiles tiles-warning has-footer" href="#">
            <div class="tiles-heading">
               <div class="text-left">Total Units</div>
            </div>
            <div class="tiles-body">
               <div class="text-center">0/{{$total_units}}</div>
            </div>
            <div class="tiles-footer">
               <div class=""></div>
            </div>
         </a>
      </div>

   </div>


   <!-- The modal -->
   <div class="modal" id="action-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabelLarge" aria-hidden="true">
   <div class="modal-dialog modal-lg" >
   <div class="modal-content" style="height:90vh;">

   <div class="modal-header" style="background-color:#2196F3; color:white;">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
   <span aria-hidden="true" style="color:red; font-size:35px;">X</span>
   </button>
   <h2 class="modal-title" id="tenant_name"></h2>
   </div>

   <div class="modal-body">
     <section class="container">
       <button type="button" name="button" class="btn btn-success" onclick="get_receivables()">New Payment</button>
       <button type="button" name="button" class="btn btn-warning" onclick="get_payment_history()">Payment History</button>
       <button type="button" name="button" class="btn btn-primary" onclick="goto_recurring()">Recurring Charges</button>
       <button type="button" name="button" class="btn btn-danger">Lease</button>
       <button type="button" name="button" class="btn btn-danger" onclick="move_out()">Move Out</button>
     </section>

     <section id="tab_content" class="container" style="margin-top:20px;">

     </section>
     </div>

   </div>
   </div>
   </div>
   <input type="hidden" name="" id="my_tenant" value="">
   <div id="tenant_detail">
     <table width="100%" >
       <tbody>
     @php
     $total_rent = 0;
     $color = "black;";
     $prev_building="...";
     @endphp
     @if(!empty($rd))
       @foreach($rd as $r)

       @if(!empty($r->rent))
         @php
           $total_rent+= $r->rent;
         @endphp
       @endif

       @if($r->rent > 0)
       @php
        $color = "red;"
        @endphp
       @endif

       @if($r->rent < 0)
       @php
        $color = "blue;"
        @endphp
       @endif

       @if($r->rent == 0)
       @php
        $color = "black;"
        @endphp
       @endif




       @if($r->building_name != $prev_building)
       <tr style="background-color:#fed8b1;">
         <td>Sr.</td>
         <td>Property/Address</td>
         <td>Unit/Apartment</td>
         <td>Residential</td>
         <td>Amount Due</td>
       </tr>
       @endif

         <tr
         style="
         @if($loop->iteration%2 == 0)
         /* background-color:gainsboro; */
         @endif
         "
         @if($r->tenant_name != 'Vacant')
         onclick="get_receivables({{$r->tenant_id}})"
         @endif

         @if($r->tenant_name == 'Vacant')
         onclick="add_to_unit({{$r->unit_id}})"
         @endif

          >
           <td>{{$loop->iteration}}</td>
           <td>
             <?php
             if($r->building_name != $prev_building)
             {
               echo $r->building_name == 'None' ? '' : $r->building_name;
             }
             $prev_building = $r->building_name;
             ?>
           </td>
           <td>{{$r->unit_name}}</td>
           <td style="background-color: {{$r->tenant_name == 'Vacant' ? 'lightgreen;' : ';'}} color:{{$color}}">{{$r->tenant_name}}</td>
           <td style="color:{{$color}}">
             <?php
             if($r->tenant_name == 'Vacant')
             {

             }
             else if($r->rent < 0)
             {
               echo '('.number_format(abs($r->rent), 2).')';
             }
             else if($r->rent > 0)
             {
               echo number_format($r->rent, 2);
             }
             ?>
           </td>
         </tr>
       @endforeach

     @endif
     </tbody>
     </table>
   </div>

    <!-- Modal -->
  <div class="modal fade" id="addtounit" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Tenant</h4>
        </div>
        <form method="post" action="{{route('add_to_tenant.save')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-body">
            <select class="form-control" name="tenant_tenant">
                @if(!empty($tenants))
                    @foreach($tenants as $t)
                        <option value="{{$t->id}}">
                            {{$t->name}}
                        </option>
                    @endforeach
                @endif
            </select>

           <input type="hidden" name="tenant_unit" id="tenant_unit" value="0" />
        </div>
        <div class="modal-footer">
          <button type="submit" id="goto_tenant" class="btn btn-success" >Save</button>
        </div>
        </form>
      </div>

    </div>
  </div>


  <div class="modal fade" id="move_out_modal" role="dialog">
    <div class="modal-dialog modal-sm">

      <!-- Modal content-->
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Are you sure?</h4>
        </div>
        <form method="post" action="{{route('move_out_tenant')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="modal-body">
            Moving Out Date: <input type="date" class="form-control" name="moveout_date" value="{{date('Y-m-d')}}">
           <input type="hidden" name="moveout_tenant" id="moveout_tenant" value="0" />
           <br>
           <textarea name="reason" class="form-control" placeholder="Type Reason Here || Optional"></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger" >Move Out</button>
        </div>
        </form>
      </div>

    </div>
  </div>

</div>
</div>

<script type="text/javascript">
function show_modal(tenant_name)
{
  $("#tenant_name").text(tenant_name);
  $("#action-modal").modal('hide');
  $("#action-modal").modal('show');
}

function get_receivables(tenant_id = 0)
{
  $(".overlay").show();
  if(tenant_id > 0)
  {
      $("#my_tenant").val(tenant_id)
  }
  var tenant_id = $("#my_tenant").val();
  $.ajax({
    url: 'receivings/receivables',
    method: "get",
    data: {tenant_id:tenant_id, home:1},
    success: function(response)
    {
      show_modal(response['tenant_name']);
      $("#tab_content").html(response.html);
      $(".overlay").hide();
      $('.mydatepicker').datepicker({
      	dateFormat : 'dd-mm-yy',
      	prevText : '<i class="fa fa-chevron-left"></i>',
      	nextText : '<i class="fa fa-chevron-right"></i>',
      	onSelect : function(selectedDate) {
      		$('#finishdate').datepicker('option', 'minDate', selectedDate);
      	}
      });
    }
  });
}

function add_to_unit(unit_id)
{
    // window.location.href="{{route('tenants.show')}}";
    $(".overlay").show();
    $("#tenant_unit").val(unit_id);
    $("#goto_tenant").click();
    // $("#addtounit").modal('show');
}
function get_payment_history()
{
  $(".overlay").show();
  var tenant_id = $("#my_tenant").val();
  $.ajax({
    url: 'ledger/show',
    method: "post",
    data: {tenant_id:tenant_id, home:1, from_date:'1900-01-01', to_date:'3000-01-01'},
    success: function(response)
    {
      $("#tab_content").html(response.html);
      $(".overlay").hide();
    }
  });
}
function filter_tenant(){
  $(".overlay").show();
  var tenant_type = $("select[name=tenant_type] option:selected").val();
  var tenant_name = $("input[name=tenant_name]").val();
  $.ajax({
    url: 'tenants/detail',
    method: 'get',
    data: {tenant_name:tenant_name, tenant_type:tenant_type},
    success: function(response){
      $("#tenant_detail").html(response);
      $(".overlay").hide();
    }
  });
}

function move_out()
{
  $("#moveout_tenant").val($("#my_tenant").val());
  $("#move_out_modal").modal('show');
}

function goto_recurring()
{
  var tenant_id = $("#my_tenant").val();
  window.location.href="{{route('tenants.edit')}}/"+tenant_id;
}
</script>
@endsection
