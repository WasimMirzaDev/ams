 @extends('layouts.app')
@section('content')
<style media="screen">
  #sms_menu{
    background-color:lightgrey !important;
  }
  #sms_datatable td, th{
    padding:2px !important;
  }
  .error_color{
    background-color:#FFA8B5 !important;
  }
  .success_color{
    background-color:#99e599 !important;
  }
</style>
<section id="widget-grid" class="">
    <div class="row">
       <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
          <div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" role="widget">
             <header role="heading">
                <div class="jarviswidget-ctrls" role="menu">   <a href="javascript:void(0);" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus"></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-expand "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
                <!-- <span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span> -->
                <h2 style="color:red;">Your Current Balance: <span>{{$bi->balance}} {{$bi->currency}}</span> </h2>
                <span class="jarviswidget-loader" style="display: none;"><i class="fa fa-refresh fa-spin"></i></span>
             </header>
             <div role="content" style="display: block;">

               <div class="modal fade" id="edit_modal" role="dialog">
                 <div class="modal-dialog modal-sm">

                   <!-- Modal content-->
                   <div class="modal-content modal-sm">
                     <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                       <h4 class="modal-title" id="resident_name"></h4>
                     </div>
                     <form method="post" action="{{route('tenants.update_tenant')}}">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="modal-body">
                        <input type="text" class="form-control" name="cell" value="" id="cell" placeholder="Enter Cell Number without (-)">
                        <small style="color:red;">Number With country code</small>
                        <br>
                        <input type="hidden" name="tenant_id" id="tenant_id" value="0" />
                        <br>
                         <input type="text" class="form-control" name="email" value="" id="email" placeholder="Add Email here...">
                     </div>
                     <div class="modal-footer">
                       <button type="submit" class="btn btn-success" >Update</button>
                     </div>
                     </form>
                   </div>

                 </div>
               </div>
                <div class="jarviswidget-editbox">
                </div>
                <div class="widget-body no-padding">
                   <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" id="addnew" href="#add">SEND SMS</a></li>
                      <li><a data-toggle="tab" href="#list">SMS HISTORY</a></li>
                   </ul>
                   <div class="tab-content">
                      <div id="add" class="tab-pane fade in active">
                        <div class="container" style="margin-top:10px;">
                          @if ($message = Session::get('success'))
                              <div class="alert alert-success alert-block">
                                 <button type="button" class="close" data-dismiss="alert">×</button>
                                 <strong>{{ $message }}</strong>
                              </div>
                          @endif

                          @if (count($errors) > 0)
                          <div class="alert alert-danger">
                             <strong>Whoops!</strong> There were some problems with your input.
                             <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                             </ul>
                          </div>
                          @endif

                          <form action="{{ route('send.sms') }}" method="POST" enctype="multipart/form-data">
                             @csrf
                             <div class="row">

                                <div class="col-md-12">
                                  <div class="col-md-8 form-group">
                                    <table data-order=[] class="table table-striped" id="sms_datatable">
                                      <thead>
                                        <tr>
                                          <th>All <input type="checkbox" name="" onclick="check_all(this)" value=""> </th>
                                          <th>Resident</th>
                                          <th>Address</th>
                                          <th>Cell</th>
                                          <th>Email</th>
                                          <th>Edit</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @if(!empty($residents))
                                         @foreach($residents as $r)
                                         <tr id="row_{{$r->id}}" class="<?= in_array($r->id, $error_ids) ? 'error_color' : ''?> <?= in_array($r->id, $success_ids) ? 'success_color' : ''?>">
                                           <td>
                                             <input class="checkboxes" type="checkbox" {{in_array($r->id, $error_ids) ? 'checked' : ''}} name="receiver[]" value='<?php echo json_encode(['cell' => $r->cell, 'id' => $r->id]) ?>'>
                                             <input type="hidden" name="rec_tenant[]" value="{{$r->id}}">
                                            </td>
                                           <td>{{$r->name}}</td>
                                           <td>{{$r->address}}</td>
                                           <td>{{$r->cell}}</td>
                                           <td>{{$r->email}}</td>
                                           <td> <button type="button" class="btn btn-xs btn-info" onclick="edit_info('{{$r->name}}', {{$r->id}}, '{{$r->cell}}', '{{$r->email}}')" name="button">Edit</button> </td>
                                         </tr>
                                         @endforeach
                                        @endif
                                      </tbody>
                                    </table>
                                  </div>
                                  <div class="col-md-4 form-group">
                                     <label>Message:</label>
                                     <textarea name="message" rows="6" class="form-control">{{$sms}}</textarea>
                                     <br>
                                     <button type="submit" class="btn btn-success" onclick="show_overlay()">Send SMS</button>
                                     <br>
                                     <br>
                                     @if(!empty($result))
                                      <div class="{{$alert}}">
                                        {{$result}}
                                      </div>
                                     @endif
                                  </div>
                                </div>
                             </div>
                          </form>
                        </div>
                      </div>
                      <div id="list" class="tab-pane fade">
                        <table data-order=[] class="table table-striped" id="datatable_fixed_column" style="width:100%;">
                          <thead>
                            <tr>
                              <th>Resident</th>
                              <th>Address</th>
                              <th>Message</th>
                              <th>Sent at</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(!empty($list))
                             @foreach($list as $l)
                             <tr>
                               <td>{{$l->name}}</td>
                               <td>{{$l->address}}</td>
                               <td>{{$l->sms}}</td>
                               <td>{{$l->created_at}}</td>
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
   function edit_info(name, id, cell, email)
   {
     $("#resident_name").text(name);
     $("#cell").val(cell);
     $("#email").val(email);
     $("#tenant_id").val(id);

     $("#edit_modal").modal('show');
   }

   function show_overlay()
   {
     $(".overlay").show();
   }
   function check_all(cb)
   {
     // console.log($(cb).prop('checked'));
     $(cb).prop('checked') ? $(".checkboxes").prop('checked', true) : $(".checkboxes").prop('checked', false);
   }
 </script>
@endsection
