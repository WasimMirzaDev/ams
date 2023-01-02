<style media="screen">
li, a, tr {
  cursor:pointer !important;
}
tr:hover {
          background-color: rgb(219,221,231) !important;
        }
</style>

<div class="" style="width:60%; margin:0 auto;">
  <h2 style="margin-bottom:5px;">Account Statement / Ledger</h2>
  <span style="font-size:18px;"><b>{{date('F-d-Y', strtotime($from_date))}}</b> To <b>{{date('F-d-Y', strtotime($to_date))}}</b></span>
  <h3>{{$tenant->name}} {{!empty($tenant->cell) ? '('.$tenant->cell.')' : ''}}</h3>
  <table width="100%">
    <thead>
      <tr style="background-color:skyblue;">
        <td>Vch/Rct</td>
        <td>Date</td>
        <td>Method</td>
        <td>Narration</td>
        <td>Dr</td>
        <td>Cr</td>
        <td>Balance</td>
      </tr>
    </thead>
    <tbody>

  @if(!empty($ledger))
    @foreach($ledger as $l)
    @if($loop->iteration  > 1)
    @php
      $dr = empty($l->dr) ? 0 : $l->dr;
      $cr = empty($l->cr) ? 0 : $l->cr;
      $balance = $balance + $dr - $cr;
      @endphp
    @endif
      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      "
       >
        <td>{{$l->id}}</td>
        <td>{{!empty($l->date) ? date('m-d-Y', strtotime($l->date)) : ''}}</td>
        <td>{{$l->pm}}</td>
        <td>{{$l->remarks}}</td>
        <td>{{$l->dr > 0 ? '$'.$l->dr : ''}}</td>
        <td>{{$l->cr > 0 ? '$'.$l->cr : ''}}</td>
        <td>{{($balance < 0 ? "($".abs($balance).")" : '$'.$balance)}}</td>
      </tr>
    @endforeach

  @endif
      <tr style="background-color:skyblue;">
        <td colspan="6"></td>
        <td>{{($balance < 0 ? "($".abs($balance).")" : '$'.$balance)}}</td>
      </tr>
  </tbody>
  </table>

</div>
