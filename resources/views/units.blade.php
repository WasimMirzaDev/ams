@extends('layouts.app')

@section('content')
@php
$route_prefix = "units.";
@endphp
<style media="screen">
#units_menu{
  color:white !important;
}
</style>
<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
             <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus"></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
                <span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span>
                <h2>Units</h2>
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
                        <br>
                        <div class="container">
                          @if (!empty($errors->any()))
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
                        </div>
                        <!-- Modal -->
                      <div class="modal fade" id="addaddressmodal" role="dialog">
                        <div class="modal-dialog modal-sm">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Add Address</h4>
                            </div>
                            <form class="" action="{{route('buildings.save_building')}}" method="post">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <div class="modal-body">
                               <input type="text" class="form-control" name="address_name" value="" placeholder="Type Address Name">
                           </div>
                           <div class="modal-footer">
                             <input type="submit" class="btn btn-primary" name="" value="Save">
                           </div>
                         </form>
                          </div>

                        </div>
                      </div>

                         <form method="post" id="dataForm1" class="smart-form" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($r->id) ? $r->id : 0}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('units/')}}">
                            <div class="container">
                               <fieldset>
                                 <div class="row" >
                                   <section class="col col-4">
                                      <label for="building_id" class="label" style="font-weight:bold;">Address:</label>
                                        <select class="select2" name="building_id">
                                          <option value="">Select Address</option>
                                          @if(!empty($buildings))
                                            @foreach($buildings as $b)
                                              <option value="{{$b->id}}"  {{!empty($r->building_id) && $r->building_id == $b->id ? 'selected' : ''}}
                                                @if(!empty(Session::get('selected_address')))
                                                  @if(Session::get('selected_address') == $b->id)
                                                    selected
                                                  @endif
                                                @endif
                                                 >{{$b->name}}</option>
                                            @endforeach
                                          @endif
                                        </select>
                                   </section>
                                   <div class="col-md-2">
                                     <button type="button" class="btn btn-lg btn-primary" onclick="add_address()" name="button" style="margin-top:23px;padding:6px 16px;">Add New Address</button>
                                   </div>
                                 </div>
                                  <div class="row" style="margin-top:5px;">
                                    <section class="col col-3" style="display:none;">
                                       <label for="number" class="label" style="font-weight:bold;">Unit Number:</label>
                                       <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                         <input type="text" name="number" id="number" value="{{$next_number}}" autocomplete="off" placeholder="Unit Number">
                                       </label>
                                    </section>
                                    <section class="col col-3">
                                      <label for="name" class="label" style="font-weight:bold;">Unit Name:</label>
                                      <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" name="name" id="name" value="{{!empty($r->id) ? $r->name : ''}}" autocomplete="off" placeholder="Unit Name">
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row" style="display:none;">
                                    <section class="col col-2">
                                      <label class="input"> <i class="icon-prepend fa fa-dollar"></i>
                                        <input type="number" name="weekly_rent" value="" autocomplete="off" placeholder="Weekly Rent">
                                      </label>
                                    </section>
                                    <section class="col col-2">
                                      <label class="input"> <i class="icon-prepend fa fa-dollar"></i>
                                        <input type="number" name="monthly_rent" autocomplete="off" placeholder="Monthly Rent">
                                      </label>
                                    </section>
                                    <section class="col col-2">
                                      <label class="input"> <i class="icon-prepend fa fa-dollar"></i>
                                        <input type="number" name="yearly_rent" autocomplete="off" placeholder="Yearly Rent">
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row" style="margin-top:5px;display:none;">
                                     <section class="col col-6">
                                       <label class="textarea">
                                         <textarea rows="3" name="description" id="description" placeholder="Description"></textarea>
                                       </label>
                                     </section>
                                  </div>
                               </fieldset>
                            </div>
                            <footer>
                              @if(!empty($r->id))
                              <a href="{{route('units.show')}}" class="btn btn-danger">
                              Cancel
                            </a>
                            @endif
                               <button type="submit" class="btn btn-success">
                               Save
                               </button>
                            </footer>
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
                                     <input type="text" class="form-control" placeholder="" />
                                  </th>
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Unit</th>
                                  <th>Address</th>
                                  <th>Edit</th>
                                  <th>Delete</th>
                               </tr>
                            </thead>
                            <tbody>
                               @if(!empty($list))
                                 @php $sr = 1
                                 @endphp
                                 @foreach($list as $l)
                                 <tr id="row_{{$l->id}}">
                                    <td>{{$l->name}}</td>
                                    <td>{{$l->building->name}}</td>
                                    <td><a type="button" id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a> </td>
                                    <td><button type="button" id="delete_{{$l->id}}" href="{{route($route_prefix.'delete')}}/{{$l->id}}" class="btn btn-danger btn-xs"  onclick="del({{$l->id}})">X</button> </td>
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
   function add_address()
   {
     $("#addaddressmodal").modal('show');
   }
 </script>
@endsection
