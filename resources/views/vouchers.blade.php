@extends('layouts.app')
@section('content')
@php
$route_prefix = "vouchers.";
@endphp
<style media="screen">
#generate_voucher_menu{
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


<form method="post" id="voucher_form" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
   <input type="hidden" id="route_prefix" name="" value="{{url('vouchers/')}}">
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
                <h2>Vouchers</h2>
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
                                    <section class="col col-6">
                                      <label for="name" class="label" style="font-weight:bold;">Tenant/Unit:</label>
                                      <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <select class="select2" id="my_tenant">
                                          @foreach($tenant as $t)
                                            <option tenant_unit="{{$t->unit_id}}" value="{{$t->tenant_id}}">{{$t->tenant_name}},  {{$t->address_name}} : {{$t->unit_name}}</option>
                                          @endforeach
                                        </select>
                                      </label>
                                    </section>

                                    <section class="col col-3">
                                      <label for="name" class="label" style="font-weight:bold;"></label>
                                      <label class="input" style="margin-top:20px;">
                                          <button type="button" class="btn btn-success" name="button" onclick="showVoucher()" style="padding:8px 12px">Generate Voucher</button>
                                      </label>
                                    </section>
                                  </div>
                                  <div class="row">
                                    <div class="col col-md-2">

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
                                     <input type="text" class="form-control" placeholder="" />
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Tenant</th>
                                  <th>Address</th>
                                  <th>Unit</th>
                                  <th>Voucher No</th>
                                  <th>Voucher Date</th>
                                  <th>Amount</th>
                                  <th>Issue Date</th>
                                  <th>Last Date</th>
                                  <th>Remarks</th>
                                  <th>Edit</th>
                                  <th>Delete</th>
                               </tr>
                            </thead>
                            <tbody>
                              @if(!empty($challans))
                                @foreach($challans as $c)
                                  <tr id="row_{{$c->id}}">
                                    <td>{{$c->tenant_name}}</td>
                                    <td>{{$c->address_name}}</td>
                                    <td>{{$c->unit_name}}</td>
                                    <td>{{$c->id}}</td>
                                    <td>{{date('d-m-Y', strtotime($c->date))}}</td>
                                    <td>{{$c->total}}</td>
                                    <td>{{date('d-m-Y', strtotime($c->i_date))}}</td>
                                    <td>{{date('d-m-Y', strtotime($c->l_date))}}</td>
                                    <td>{{$c->remarks}}</td>
                                    <td><button id="edit_{{$c->id}}" href="{{route($route_prefix.'edit')}}/{{$c->id}}" onclick="get_voucher({{$c->id}})" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></button> </td>
                                    <td><button type="button" id="delete_{{$c->id}}" href="{{route($route_prefix.'delete')}}/{{$c->id}}" class="btn btn-danger btn-xs"  onclick="del({{$c->id}})">X</button> </td>
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

function showVoucher()
{
  var tenant_id = $("#my_tenant option:selected").val();
    var unit_id = $("#my_tenant option:selected").attr('tenant_unit');
  $.ajax({
    url: 'generate',
    method: "get",
    data: {tenant_id:tenant_id, unit_id:unit_id},
    success: function(response)
    {
      $("#modal_content").html(response.html);
      $("#my_modal").modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#tenant_id").val(tenant_id);
        $("#unit_id").val(unit_id);

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


function addMoreExtra()
{
  $("#extras").append("<tr>"+$("#appendable_extra").html()+"</tr>");
}

function removeRow(tr)
{
  $(tr).remove();
  count_total();
}


function count_total()
{
  var total = 0;
  $(".fh_amount").each(function(){
    if($(this).val() > 0)
    {
      total += parseInt($(this).val());
    }
  });
  $("#total").html(total);
}
</script>


@endsection
