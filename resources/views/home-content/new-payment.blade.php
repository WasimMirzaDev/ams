
<style media="screen">
  input {
    border:1px solid gainsboro !important;
  }
</style>
@php
$route_prefix = "receivings.";
@endphp
<div class="row">
  <section class="col-md-8" id="new_payment" style="max-height:60vh;overflow-y:scroll;">
    @if(empty($ra) && empty($rem_amt))
      <div class="row">
        <div class="col col-md-12">
          <h5 class="alert alert-success">No debits left. All Vouchers payment is clear!</h5>
        </div>
      </div>
      @else
      <table style="width:100%;" border="1" bordercolor="white">
        <thead style="position:sticky; top:0;">
          <tr style="background-color:#ADD8E9;">
            <th width="5%;">Sr.</th>
            <th>Date</th>
            <th>Vch #</th>
            <th>Vch Amt</th>
            <th>Receivable</th>
            <th>Remarks</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @php
            $total = 0;
          @endphp
          @foreach($ra as $vch)
          @php
            $total += $vch->receiveable_amt;
          @endphp
          <tr style="background-color:{{$loop->iteration%2 == 0 ? 'lightgrey' : '' }}">
            <td>{{$loop->iteration}}</td>
            <td>{{date('d-m-Y', strtotime($vch->date))}}</td>
            <td style="text-align:right;">{{$vch->id}}</td>
            <td style="text-align:right;">{{$vch->vch_total}}</td>
            <td style="text-align:right;">{{$vch->receiveable_amt}}</td>
            <td style="text-align:center;">{{$vch->remarks}}</td>
            <td style="color:red;">{{$vch->status}}</td>
          </tr>
          @endforeach
          @if(count($ra) < 15)
          @php
            $remaining_lines = 15 - count($ra);
          @endphp
          @for($a = 0; $a < $remaining_lines; $a++)
          <tr style="background-color:{{$a%2 == 0 ? 'lightgrey' : '' }}">
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          @endfor
          @endif
        </tbody>
        <tbody style="background-color:#ADD8E9;position:sticky; bottom:0;">
          <tr >
            <th colspan="4">Total</th>
            <th style="text-align:right">{{$total}}</th>
            <th colspan="2"></th>
          </tr>
        </tbody>
      </table>
    @endif
  </section>
  <section class="col-md-4">

    <form method="post" id="receiving_formv2" enctype="multipart/form-data" action="{{route($route_prefix.'save')}}">
       <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <input type="hidden" id="route_prefix" name="" value="{{url('receivings/')}}">
       <input type="hidden" name="id" value="{{!empty($r->id) ? $r->id : 0}}">
       <input type="hidden" id="unit_id" name="unit_id" value="{{!empty($r->unit_id) ? $r->unit_id : $request->unit_id}}">
       <input type="hidden" id="tenant_id" name="tenant_id" value="{{!empty($r->tenant_id) ? $r->tenant_id : $request->tenant_id}}">
       <input type="hidden" id="vch_id" name="vch_id" value="0">

       <div class="row">
         <section class="col col-md-4">
           Rec Date:
         </section>
         <section class="col col-md-8">
           <label class="input">
             <input type="text" class="form-control mydatepicker" autocomplete="off" name="date" value="{{(!empty($r->id)) ? date('d-m-Y', strtotime($r->date)) : date('d-m-Y')}}">
           </label>
         </section>
       </div>
       <div class="row" style="margin-top:5px;">
         <section class="col col-md-4">
           Paid via:
         </section>
         <section class="col col-md-8">
           <select class="form-control" name="pm_id" id="pm_id" onchange="isCash()">
             @foreach($pm as $p)
             <option {{!empty($r->id) && $r->pm_id == $p->id ? 'selected' : '' }} is_cash="{{$p->is_cash}}" value="{{$p->id}}">{{$p->name}}</option>
             @endforeach
           </select>
         </section>
       </div>
       <div class="row" style="margin-top:5px;">
         <div class="col col-md-4">

         </div>
         <section class="col col-md-8" style="{{!empty($r->id) && $r->pm->is_cash == 0 ? 'display:block;' : 'display:none' }}"  id="cheque_no">
           <label class="input">
             <input type="text" class="form-control" name="cheque_no" placeholder="Bank/Cheque Number" value="{{!empty($r->id) ? $r->cheque_no : '' }}">
           </label>
         </section>
       </div>
       <div class="row">
         <div class="col col-md-12" id="credit_customer_error" style="color:red;">

         </div>
       </div>
       <div class="row" style="margin-top:5px;">
         <section class="col col-md-4">
           Remarks
         </section>
         <section class="col col-md-8">
           <label class="textarea">
             <textarea name="remarks" rows="3">{{(!empty($r->id)) ? $r->remarks : ''}}</textarea>
           </label>
         </section>
         <section class="col col-md-4">
           Balance:
         </section>
         <section class="col col-md-8" style="text-align:left;">
           <label class="input" style="font-size:25px; text-align:left;">
             {{$rem_amt <= 0 ? number_format(abs($rem_amt), 2) : '('.number_format($rem_amt,2).')'}}
             <input type="hidden" class="form-control" name="pending_amt" disabled readonly value="{{$rem_amt > 0 ? $rem_amt : 0}}">
           </label>
         </section>
         <section class="col col-md-4" style="color:green;">
           Receive:
         </section>
         <section class="col col-md-8">
           <label class="input">
             <input type="number" step="any" class="form-control" style="color:green; font-size:25px;" onkeyup="check_pending_amount()" name="amount" value="{{!empty($r->id) ? $r->amount : ($rem_amt > 0 ? $rem_amt : '')}}">
           </label>
         </section>
       </div>
       <button class="btn btn-success btn-block" type="submit"
       onclick="save_receiving_home()"
       id="save_btn">Save</button>
    </form>

  </section>
</div>
