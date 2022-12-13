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
                         <form id="dataForm1" class="smart-form" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="0">
                            <input type="hidden" id="route_prefix" name="" value="{{url('units/')}}">
                            <div class="container">
                               <fieldset>
                                 <div class="row" style="margin-top:5px;">
                                   <section class="col col-3">
                                      <label for="building_id" class="label" style="font-weight:bold;">Address:</label>
                                        <select class="form-control" name="building_id">
                                          <option value="">Select Address</option>
                                          @if(!empty($buildings))
                                            @foreach($buildings as $b)
                                              <option value="{{$b->id}}">{{$b->name}}</option>
                                            @endforeach
                                          @endif
                                        </select>
                                   </section>
                                 </div>
                                  <div class="row" style="margin-top:5px;">
                                    <section class="col col-3">
                                       <label for="number" class="label" style="font-weight:bold;">Unit Number:</label>
                                       <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                         <input type="text" name="number" id="number" value="{{$next_number}}" autocomplete="off" placeholder="Unit Number">
                                       </label>
                                    </section>
                                    <section class="col col-3">
                                      <label for="name" class="label" style="font-weight:bold;">Unit Name:</label>
                                      <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" name="name" id="name" value="" autocomplete="off" placeholder="Unit Name">
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row">
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

                                  <div class="row" style="margin-top:5px;">
                                     <section class="col col-6">
                                       <label class="textarea">
                                         <textarea rows="3" name="description" id="description" placeholder="Description"></textarea>
                                       </label>
                                     </section>
                                  </div>
                               </fieldset>
                            </div>
                            <footer>
                               <button type="submit" onclick="save1()" id="save_btn" class="btn btn-success">
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
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th class="hasinput">
                                     <input class="form-control" placeholder="" type="text">
                                  </th>
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Number</th>
                                  <th>Name</th>
                                  <th>Address</th>
                                  <th>Weekly Rent</th>
                                  <th>Monthly Rent</th>
                                  <th>Yearly Rent</th>
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
                                    <td>{{$l->number}}</td>
                                    <td>{{$l->name}}</td>
                                    <td>{{$l->building->name}}</td>
                                    <td>{{$l->weekly_rent}}</td>
                                    <td>{{$l->monthly_rent}}</td>
                                    <td>{{$l->yearly_rent}}</td>
                                    <td><button type="button" id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs" onclick="edit1({{$l->id}})"><i class="fa fa-edit"></i></button> </td>
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
@endsection
