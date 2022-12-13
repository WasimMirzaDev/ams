@extends('layouts.app')
@section('content')
@php
$route_prefix = "extras.";
@endphp
<style media="screen">
#extra_menu{
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
                <h2>Charge Type</h2>
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
                         <form method="post" id="dataForm1" class="smart-form" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($extra->fh_id) ? $extra->fh_id : ''}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('extras/')}}">
                            <div class="container">
                               <fieldset>

                                  <div class="row" style="margin-top:5px;">
                                    <section class="col col-3">
                                      <label for="name" class="label" style="font-weight:bold;">Name:</label>
                                      <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" name="fh_name" id="name" value="{{!empty($extra->fh_name) ? $extra->fh_name : ''}}" autocomplete="off" placeholder="Name">
                                      </label>
                                    </section>

                                    <section class="col col-3">
                                       <label for="number" class="label" style="font-weight:bold;">Amount:</label>
                                       <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                         <input type="text" name="fh_amount" id="number" value="{{!empty($extra->fh_amount) ? $extra->fh_amount: ''}}" autocomplete="off" placeholder="Amount">
                                       </label>
                                    </section>
                                  </div>



                                  <div class="row" style="margin-top:5px;">
                                     <section class="col col-6">

                                       <label class="">
                                          <input  type="checkbox" name="fh_active" value="1" {{!empty($extra->fh_active) ? 'checked': ''}} />Active
                                       </label>
                                     </section>
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
                            <footer>
                               <button type="submit" id="save_btn" class="btn btn-success">
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
                                  <th></th>
                                  <th></th>
                               </tr>
                               <tr>
                                  <th>Name</th>
                                  <th>Amount</th>
                                  <th>Status</th>
                                  <th>Edit</th>
                                  <th></th>
                               </tr>
                            </thead>
                            <tbody>
                               @if(!empty($fh))
                                 @php $sr = 1
                                 @endphp
                                 @foreach($fh as $f)
                                 <tr id="row_{{$f->fh_id}}">
                                    <td>{{$f->fh_name}}</td>
                                    <td>{{$f->fh_amount}}</td>
                                    <td>{{$f->fh_active == 1 ? 'Active' : 'Inactive'}}</td>
                                    <td><a type="button" id="edit_{{$f->fh_id}}" href="{{route($route_prefix.'edit')}}/{{$f->fh_id}}"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a> </td>
                                    <td>
                                      <!-- <a type="button" id="delete_{{$f->fh_id}}" href="{{route($route_prefix.'delete')}}/{{$f->fh_id}}" class="btn btn-danger btn-xs"  onclick="del({{$f->fh_id}})">X</a> -->
                                    </td>
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
