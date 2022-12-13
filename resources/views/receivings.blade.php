@extends('layouts.app')
@section('content')
@php
$route_prefix = "receivings.";
@endphp
<style media="screen">
#payment_receiving_menu{
  color:white !important;
}
</style>

<table style="display:none;">
  <tr id="appendable_extra">
    <td>
      <select class="form-control" name="fh_id[]">
        @if(!empty($fee_head))
          @foreach($fee_head as $fh)
            <option value="{{$fh->fh_id}}">{{$fh->fh_name}}</option>
          @endforeach
        @endif
      </select>
    </td>
    <td>
      <label for="" class="input">
        <input type="number" class="form-control fh_amount" name="fh_amount[]" onkeyup="count_total()" value="">
      </label>
  </td>
  <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
  </tr>
</table>


<form method="post" id="receiving_formv2" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input type="hidden" id="route_prefix" name="" value="{{url('receivings/')}}">
    <div class="container">
      <!-- Modal -->
      <div class="modal fade" id="my_modal" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content" id="modal_content">

          </div>
        </div>
      </div>
    </div>
</form>


<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
             <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus"></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
                <span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span>
                <h2>Receivables</h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                   <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" id="addnew" href="#add">Add</a></li>
                      <li><a data-toggle="tab" href="#list">List</a></li>
                   </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade in active">
                         <form  class="smart-form">
                            <div class="container">
                               <fieldset>

                                  <div class="row" style="margin-top:5px;">
                                    <section class="col col-md-7">
                                      <label for="name" class="label" style="font-weight:bold;">Tenant/Unit:</label>
                                      <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <select class="select2" id="my_tenant" onchange="get_receivables()">
                                          <option value="">Select</option>
                                          @foreach($tenant as $t)
                                            <option tenant_unit="{{$t->unit_id}}" value="{{$t->tenant_id}}">{{$t->tenant_name}},  {{$t->address_name}} : {{$t->unit_name}}</option>
                                          @endforeach
                                        </select>
                                      </label>
                                    </section>

                                    <button type="button" onclick="createReceivingv2()" class="btn btn-success" name="button" style="padding:10px 12px; margin-top:20px;"><b>Receive Payment</b></button>
                                  </div>

                                  <div class="row">
                                    <div class="col col-md-12" id="receivables">

                                    </div>
                                  </div>



                                  <div class="row" style="margin-top:5px;">

                                  </div>

                                  @if ($errors->any())
                                  <div class="alert alert-danger">
                                      <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                      <ul>
                                          @foreach ($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                              @endif

                              @if ($message = Session::get('success'))
                                   <div class="alert alert-success">
                                       <p>{{ $message }}</p>
                                   </div>
                               @endif
                               </fieldset>
                            </div>
                         </form>
                      </div>
                      <div id="list" class="tab-pane fade">
                         <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                            <thead>
                               <tr>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th class="hasinput">
                                     <input type="text" class="form-control" placeholder="">
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Tenant</th>
                                  <th>Address</th>
                                  <th>Unit</th>
                                  <th>Vch No</th>
                                  <th>Receipt No</th>
                                  <th>Receipt Date</th>
                                  <th>Received Amount</th>
                                  <th>Method</th>
                                  <th>Remarks</th>
                                  <th>Edit</th>
                                  <th>Delete</th>
                               </tr>
                            </thead>
                            <tbody>
                              @if(!empty($receipts))
                                @foreach($receipts as $r)
                                  <tr id="row_{{$r->id}}">
                                    <td>{{$r->tenant->name}}</td>
                                    <td>{{$r->unit->building->name}}</td>
                                    <td>{{$r->unit->name}}</td>
                                    <td>{{$r->challan_id}}</td>
                                    <td>{{$r->id}}</td>
                                    <td>{{date('d-m-Y', strtotime($r->date))}}</td>
                                    <td>{{$r->amount}}</td>
                                    <td>{{$r->pm->name}}</td>
                                    <td>{{$r->remarks}}</td>
                                    <td>

                                        <a href="{{route($route_prefix.'print')}}/{{$r->id}}" target="_blank" class="btn btn-success btn-xs" ><i class="fa fa-print"></i></a>
                                        <button id="edit_{{$r->id}}" href="{{route($route_prefix.'edit')}}/{{$r->id}}" onclick="get_voucher({{$r->id}})" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></button>
                                    </td>
                                    <td><button type="button" id="delete_{{$r->id}}" href="{{route($route_prefix.'delete')}}/{{$r->id}}" class="btn btn-danger btn-xs"  onclick="del({{$r->id}})">X</button> </td>
                                  </tr>
                                @endforeach
                              @endif
                            </tbody>
                         </table>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </article>
    </div>
 </section>

<script type="text/javascript">

function get_voucher(id)
{
	var formAction = $("#edit_"+id).attr("href");
  $.ajax({
    url: formAction,
    method: "get",
    data: {},
    success: function(response)
    {
      $("#modal_content").html(response.html);
      $("#my_modal").modal({
        backdrop: 'static',
        keyboard: false
      });
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

function createReceiving(vch_id)
{
  $.ajax({
    url: 'generate',
    method: "get",
    data: {vch_id:vch_id},
    success: function(response)
    {
      $("#modal_content").html(response.html);
      $("#my_modal").modal({
        backdrop: 'static',
        keyboard: false
      });

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


function createReceivingv2()
{
  var tenant_id = $("#my_tenant option:selected").val();
  $.ajax({
    url: 'generatev2',
    method: "get",
    data: {tenant_id:tenant_id},
    success: function(response)
    {
      $("#modal_content").html(response.html);
      $("#my_modal").modal({
        backdrop: 'static',
        keyboard: false
      });

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



function get_receivables()
{
  $(".overlay").show();
  var tenant_id = $("#my_tenant option:selected").val();
  $.ajax({
    url: 'receivables',
    method: "get",
    data: {tenant_id:tenant_id},
    success: function(response)
    {
      $("#receivables").html(response.html);
      $(".overlay").hide();
    }
  });
}
</script>


@endsection
