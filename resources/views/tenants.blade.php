@extends('layouts.app')

@section('content')
@php
$route_prefix = "tenants.";
@endphp
<style media="screen">
#tenants_menu{
  color:white !important;
}

label, .col {
  color:black;
}

</style>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
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
        <input type="number" name="fh_amount[]" step="any" value="">
      </label>
  </td>
  <td>
    <select class="form-control" name="pm_type[]">
      @foreach($ps as $p)
        <option {{!empty($t->id) && $t->pm_type == $p->c_id ? 'selected' : '' }} value="{{$p->c_id}}">{{$p->name}}</option>
      @endforeach
    </select>
  </td>
  <td>
    <label class="input">
        <input autocomplete="off" type="date" value="{{date('d-m-Y')}}" name="start_date[]" class="" >
        <input type="hidden" name="last_voucher[]" value="0000-00-00">
    </label>
  </td>
  <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
  </tr>
</table>

<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
             <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus"></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
                <span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span>
                <h2>Tenants</h2>
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
                         <form target="_blank" method="post" id="dataForm2" class="smart-form" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{!empty($t->id) ? $t->id : 0}}">
                            <input type="hidden" id="route_prefix" name="" value="{{url('tenants/')}}">
                            <fieldset>
                              <div class="row">
                                <section class="col col-2">
                                    <span class="input-group-btn" style="display:none;">
                                    <span class="btn btn-default btn-file">
                                       Browse… <input type="file" name="file" style="display:none;" id="imgInp" onchange="readURL(this);">
                                    </span>
                                    </span>
                                 <input type="text" id="browse_img" class="form-control bg-white" style="display:none;" name="" value="" disabled>
                                 <label for="imgInp" class="col-lg-4 text-center" style="color:black;width:110px; height:120px; display:block;">
                                    <img id="blah" src="{{asset('/app_images/default.jpg')}}" alt="" style="=cursor:pointer;max-width:100%;max-height:100%;" class="img-fluid"/>
                                    Tenant Image (jpg/png)
                               </label>
                             </section>

                            <section class="col-md-5">
                              <div class="row">
                                <section class="col col-6" style="display:none;">
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" name="number" value="{{$next_number}}" placeholder="Tenant Number">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                    <input type="text" name="name" value="{{!empty($t->id) ? $t->name : ''}}" placeholder="Full name">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-6">
                                  <label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
                                    <input type="email" value="{{!empty($t->id) ? $t->email : ''}}" name="email" placeholder="E-mail">
                                  </label>
                                </section>
                                <section class="col col-6">
                                  <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                    <input type="text" value="{{!empty($t->id) ? $t->cell : ''}}" name="cell" placeholder="Phone">
                                  </label>
                                </section>
                              </div>
                             </section>
                             <section class="col col-md-5">
                               <div class="row">
                                 <div class="col col-md-4">
                                   Driver Licence
                                 </div>
                                 <div class="col-md-8">
                                   <input class="my_file" type="file" name="licence" value="">
                                 </div>
                               </div>
                               <div class="row" style="margin-top:4px">
                                 <div class="col col-md-4">
                                  Family
                                 </div>
                                 <div class="col-md-8">
                                   <input class="my_file" type="file" name="family" value="">
                                 </div>
                               </div>
                               <div class="row" style="margin-top:4px">
                                 <div class="col col-md-4">
                                   Misc
                                 </div>
                                 <div class="col-md-8">
                                   <input class="my_file" type="file" name="misc" value="">
                                 </div>
                               </div>
                               <div class="row" style="margin-top:4px">
                                 <div class="col col-md-4">
                                   Lease Copy
                                 </div>
                                 <div class="col-md-8">
                                   <input class="my_file" type="file" name="lease" value="">
                                 </div>
                               </div>
                             </section>
                            </div>

                              <div class="row" style="margin-top:25px;">
                                <section class="col col-4">
                                  <label class="input">
                                    <input type="text" name="identity" value="{{!empty($t->id) ? $t->identity : ''}}" placeholder="ID Card Number">
                                  </label>
                                </section>
                              </div>
                              <div class="row">
                                <section class="col col-4">
                                  <label class="input">
                                    <input type="text" name="country" value="{{!empty($t->id) ? $t->country : ''}}" placeholder="Country">
                                  </label>
                                </section>

                                <section class="col col-4">
                                  <label class="input">
                                    <input type="text" name="city" value="{{!empty($t->id) ? $t->city : ''}}" placeholder="City">
                                  </label>
                                </section>
                              </div>
                              <section>
                                <label class="textarea">
                                  <textarea rows="3" name="additional_info" placeholder="Additional info">{{!empty($t->id) ? $t->additional_info : ''}}</textarea>
                                </label>
                              </section>
                            </fieldset>

                            <div class="container">
                              <section class="col ">
                                <div class="inline-group">

                                  <label class="radio">
                                    <input checked {{!empty($t->id) && $t->gender == 'm' ? 'checked' : ''}} id="male" type="radio" name="gender"  value="m">
                                    <i></i>Male
                                  </label>

                                  <label class="radio">
                                    <input {{!empty($t->id) && $t->gender == 'f' ? 'checked' : ''}} id="female" type="radio" name="gender" value="f">
                                    <i></i>Female
                                  </label>

                                </div>
                              </section>
                            </div>
                            <br>
                            <fieldset style="color:black;">
                              <hr>
                              <h2 >Rent Structure</h2>
                              <br/>
                              <div class="row">
                                <div class="col col-md-6">
                                  <div class="row">
                                    <section class="col col-10">
                                      Available Units (Vacant)
                                      <label class="input">
                                        <select class="select2" name="unit_id">
                                          @if(!empty($t->id))

                                          <option value="">Select</option>
                                          @foreach($units as $u)
                                            <option {{$t->unit_id == $u->id ? 'selected' : ''}} value="{{$u->id}}">{{$u->building_name}}:{{$u->unit_name}}</option>
                                          @endforeach

                                          @else

                                          <option value="">Select</option>
                                          @foreach($units as $u)
                                          <option {{!empty($my_unit) && $my_unit == $u->id ? 'selected' : ''}} value="{{$u->id}}">{{$u->building->name}}:{{$u->name}}</option>
                                          @endforeach

                                          @endif
                                        </select>
                                      </label>
                                    </section>
                                  </div>
                                  <div class="row">
                                    <section class="col col-6">
                                      Joining Date
                                      <label class="input">
                                        <input autocomplete="off" type="text" value="{{date('d-m-Y')}}" name="joining_date" class="mydatepicker" >
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row" style="display:none;">
                                    <section class="col col-6">
                                      Payment Schedule
                                      <label class="input">
                                        <select class="select2" name="pm_type" id="payment_schedule" onchange="paymentSchedule()">
                                          @foreach($ps as $p)
                                            <option {{!empty($t->id) && $t->pm_type == $p->c_id ? 'selected' : '' }} value="{{$p->c_id}}">{{$p->name}}</option>
                                          @endforeach
                                        </select>
                                      </label>
                                    </section>

                                    <section class="col col-6" id="day_div" style="display: {{!empty($t->id) && $t->pm_type == 'w' ? 'block' : 'none' }}"  >
                                      Pay on Every
                                      <label class="input">
                                        <select class="select2" name="weekly_daynum" id="day">
                                          @foreach($dow as $d)
                                            <option {{!empty($t->id) && $t->weekly_daynum == $d->number ? 'selected' : '' }} value="{{$d->number}}">{{$d->name}}</option>
                                          @endforeach
                                        </select>
                                      </label>
                                    </section>


                                    <section class="col col-6" id="month_div" style="display: {{!empty($t->id) && $t->pm_type == 'm' ? 'block' : 'none' }}">
                                      Pay On Every
                                      <label class="input">
                                        <input type="text" class="day-date-picker" name="monthly_datenum" value="{{!empty($t->id) ? $t->monthly_datenum : ''}}">
                                      </label>
                                    </section>


                                    <section class="col col-6" id="year_div" style="display: {{!empty($t->id) && $t->pm_type == 'y' ? 'block' : 'none' }}">
                                      Pay On Every
                                      <label class="input">
                                      <input type="text" name="yearly_month_date" value="{{!empty($t->id) ? date('d-F', strtotime($t->yearly_month_date)) : ''}}" class="month-day-picker">
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row">
                                    <section class="col col-6">
                                      Advance
                                      <label class="input">
                                        <input type="number" name="advance" value="{{!empty($t->id) ? $t->advance : ''}}">
                                      </label>
                                    </section>

                                    <section class="col col-6">
                                      Opening Balance
                                      <label class="input">
                                        <input type="number" name="opening" value="{{!empty($t->id) ? $t->opening : ''}}">
                                      </label>
                                    </section>
                                  </div>

                                  <div class="row">
                                    <section class="col col-md-6">
                                      Status
                                      <label class="input">
                                        <select class="select2" name="active">
                                          <option {{!empty($t->id) && $t->active == 1 ? 'selected' : ''}} value="1">Active</option>
                                          <option {{!empty($t->id) && $t->active == 0 ? 'selected' : ''}} value="0">Left</option>
                                        </select>
                                      </label>
                                    </section>
                                  </div>


                                </div>
                                <div class="col-md-6">
                                  <table class="table table-bordered" width="100%">
                                    <thead class="bg-success">
                                      <tr style="color:white; background:black;">
                                        <td>Recurring Head</td>
                                        <td>Recurring Charges</td>
                                        <td>Recurring Type</td>
                                        <td>Starting Date</td>
                                        <td> <button type="button" onclick="addMoreExtra()" class="btn btn-info btn-xs" style="border:1px solid white; background-color:black;" name="button">Add More</button> </td>
                                      </tr>
                                    </thead>
                                    <tbody id="extras">
                                      @if(!empty($end))
                                      @foreach($end as $ed)
                                      <tr>
                                        <td>
                                          <select class="form-control" name="fh_id[]">
                                            @if(!empty($fee_head))
                                              @foreach($fee_head as $fh)
                                                <option {{$ed->fh_id == $fh->fh_id ? 'selected' : ''}} value="{{$fh->fh_id}}">{{$fh->fh_name}}</option>
                                              @endforeach
                                            @endif
                                          </select>
                                        </td>
                                        <td>
                                          <label for="" class="input">
                                            <input type="number" name="fh_amount[]" step="any" value="{{$ed->amount}}">
                                          </label>
                                      </td>
                                       <td>
                                        <select class="form-control" name="pm_type[]">
                                          @foreach($ps as $p)
                                            <option {{!empty($ed->id) && $ed->pm_type == $p->c_id ? 'selected' : '' }} value="{{$p->c_id}}">{{$p->name}}</option>
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <label class="input">
                                            <input autocomplete="off" type="date" value="{{date('Y-m-d', strtotime($ed->start_date))}}" name="start_date[]" class="" >
                                            <input type="hidden" name="last_voucher[]" value="{{ $ed->last_voucher == '0000-00-00' ? '0000-00-00' : date('Y-m-d', strtotime($ed->last_voucher))}}">
                                        </label>
                                      </td>
                                      <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
                                      </tr>
                                      @endforeach

                                      @else
                                        @php
                                        $myarr = [1,2,3,4];
                                        @endphp
                                        @foreach($myarr as $arr)
                                          @php
                                            $inc = 1;
                                          @endphp
                                      <tr>
                                        <td>
                                          <select class="form-control" name="fh_id[]">
                                            @if(!empty($fee_head))
                                              @foreach($fee_head as $fh)
                                                <option {{$inc}} {{$inc == $arr ? 'selected' : ''}} value="{{$fh->fh_id}}">{{$fh->fh_name}}</option>
                                                  @php
                                                  $inc++;
                                                  @endphp
                                              @endforeach
                                            @endif
                                          </select>
                                        </td>
                                        <td>
                                          <label for="" class="input">
                                            <input type="number" name="fh_amount[]" step="any" value="">
                                          </label>
                                      </td>
                                      <td>
                                        <select class="form-control" name="pm_type[]">
                                          @foreach($ps as $p)
                                            <option {{!empty($t->id) && $t->pm_type == $p->c_id ? 'selected' : '' }} value="{{$p->c_id}}">{{$p->name}}</option>
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <label class="input">
                                            <input autocomplete="off" type="text" value="{{date('d-m-Y')}}" name="start_date[]" class="mydatepicker" >
                                            <input type="hidden" name="last_voucher[]" value="0000-00-00">
                                        </label>
                                      </td>
                                      <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
                                      </tr>
                                      @endforeach
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </fieldset>
                            <footer>
                               <button type="submit"
                               onclick="save2()"
                                id="save_btn" class="btn btn-success">
                               Save
                               </button>
                               <a href="{{route('tenants.show')}}"
                               id="save_btn" class="btn btn-primary">
                               New
                             </a>
                            </footer>
                         </form>
                      </div>
                      <div id="list" class="tab-pane fade">
                         <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
                            <thead> 
                               <tr>
                                  <th>Number</th>
                                  <th>Name</th>
                                  <th>Address</th>
                                  <th>Identity</th>
                                  <th>Cell</th>
                                  <th>Country</th>
                                  <th>City</th>
                                  <th>Gender</th>
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
                                    <td>{{$l->address}}</td>
                                    <td>{{$l->identity}}</td>
                                    <td>{{$l->cell}}</td>
                                    <td>{{$l->country}}</td>
                                    <td>{{$l->city}}</td>
                                    <td>{{$l->gender == 'm' ? 'Male' : 'Female'}}</td>
                                    <td><a id="edit_{{$l->id}}" href="{{route($route_prefix.'edit')}}/{{$l->id}}"     class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a> </td>
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
   function paymentSchedule()
   {
     $("#day_div").hide();
     $("#month_div").hide();
     $("#year_div").hide();
     if($("#payment_schedule").val() == 'w')
     {
       $("#day_div").show();
     }
     if($("#payment_schedule").val() == 'm')
     {
       $("#month_div").show();
     }
     if($("#payment_schedule").val() == 'y')
     {
       $("#year_div").show();
     }
   }


   function addMoreExtra()
   {
     $("#extras").append("<tr>"+$("#appendable_extra").html()+"</tr>");
   }

   function removeRow(tr)
   {
     $(tr).remove();
   }
 </script>


@endsection
