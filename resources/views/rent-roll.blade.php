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
        <td>Amount</td>
        <td>Date</td>
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
        @if(!empty($show_apartment)) <td> {{$l->unit_name}} ({{$l->building_name}})</td> @endif
        @if(!empty($show_resident)) <td>{{$l->tenant_name}}</td> @endif
        <td style="text-align:right;font-weight:bold;">{{$l->amount}}</td>
        <td style="text-align:center;">{{!empty($l->date) ? date('m-d-Y', strtotime($l->date)) : ''}}</td>
      </tr>
      @php
        $total += $l->amount;
      @endphp
    @endforeach

  @endif
      <tr style="background-color:skyblue;">
        @if($colspan > 0)
        <td colspan="{{$colspan}}"></td>
        @endif
        <td align="right" style="font-size:20px; font-weight:bold;">{{$total}}</td>
      </tr>
  </tbody>
  </table>

</div>
