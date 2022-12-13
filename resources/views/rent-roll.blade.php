<style media="screen">
/*li, a, tr {*/
/*  cursor:pointer !important;*/
/*}*/
/*tr:hover {*/
/*          background-color: rgb(219,221,231) !important;*/
/*        }*/
</style>

<div class="" style="width:60%; margin:0 auto;">
  <h2 style="margin-bottom:5px;">Rent Roll</h2>
  <span style="font-size:18px;"><b>{{date('F-d-Y', strtotime($from_date))}}</b> To <b>{{date('F-d-Y', strtotime($to_date))}}</b></span>
  <table width="100%">
    <thead>
      <tr style="background-color:skyblue;">
          @php
            $colspan = 1;          
          @endphp
        @if(!empty($show_apartment)) @php $colspan = $colspan + 1 @endphp <td>Apartment</td> @endif
        @if(!empty($show_resident)) @php $colspan = $colspan + 1 @endphp <td>Residential</td> @endif
        <td>Date</td>
        <td>Amount</td>
      </tr>
    </thead>
    <tbody>
@php $total = 0; @endphp
  @if(!empty($ledger))
    @foreach($ledger as $l)
      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      "
       > 
        @if(!empty($show_apartment)) <td>{{$l->building_name}} ({{$l->unit_name}})</td> @endif
        @if(!empty($show_resident)) <td>{{$l->tenant_name}}</td> @endif
        <td>{{!empty($l->date) ? date('d-m-Y', strtotime($l->date)) : ''}}</td>
        <td style="text-align:right;">{{$l->amount}}</td>
      </tr>
      @php
        $total += $l->amount;
      @endphp
    @endforeach

  @endif
      <tr style="background-color:skyblue;">
        <td colspan="{{$colspan}}"></td>
        <td align="right" style="font-size:20px; font-weight:bold;">{{$total}}</td>
      </tr>
  </tbody>
  </table>

</div>