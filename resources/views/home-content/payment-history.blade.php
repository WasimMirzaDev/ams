<style media="screen">
li, a, tr {
  cursor:pointer !important;
}
tr:hover {
          background-color: rgb(219,221,231) !important;
        }
</style>

<div class="" style="width:100%; height:65vh; overflow-y:scroll; margin:0 auto;">
  <table width="100%" border="1" bordercolor="white">
    <thead style="position:sticky; top:0;">
      <tr style="background-color:skyblue;">
        <td>Cheque No.</td>
        <td>Method</td>
        <td>Date</td>
        <td>Narration</td>
        <td>Dr</td>
        <td>Cr</td>
        <td>Balance</td>
        <td>Action</td>
      </tr>
    </thead>
    <tbody>
  @php
  $total_dr = $total_cr = 0;
  @endphp

  @if($balance > 0)
  @php
  $total_dr = $balance;
  @endphp
  @else
  @php
  $total_cr = abs($balance);
  @endphp
  @endif

  @if(!empty($ledger))
    @foreach($ledger as $l)
    @if($loop->iteration  > 1)
    @php
      $dr = empty($l->dr) ? 0 : $l->dr;
      $cr = empty($l->cr) ? 0 : $l->cr;
      $balance = $balance + $dr - $cr;
      $total_dr += $dr;
      $total_cr += $cr;
      @endphp
    @endif
      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      "
      id="row_{{$l->id}}"
       >
        <td>{{$l->cheque_no}}</td>
        <td>{{$l->pm}}</td>
        <td>{{!empty($l->date) ? date('m-d-Y', strtotime($l->date)) : ''}}</td>
        <td>{{$l->remarks}}</td>
        <td style="text-align:right;">{{$l->dr > 0 ? '$'.$l->dr : ''}}</td>
        <td style="text-align:right;">{{$l->cr > 0 ? '$'.$l->cr : ''}}</td>
        <td style="text-align:right;">{{($balance < 0 ? "($".abs($balance).")" : '$'.$balance)}}</td>
        <td align="center">

        @if($loop->iteration > 1)
        <button type="button" class="btn btn-danger btn-xs"  id="delete_{{$l->id}}" onclick="del({{$l->id}})"
            @if($l->cr > 0 )
                href="{{route('receivings.delete')}}/{{$l->id}}"
            @endif

            @if($l->dr > 0)
                href="{{route('vouchers.delete')}}/{{$l->id}}"
            @endif
        >X</button type="button">
        @endif
        </td>
      </tr>
    @endforeach

  @endif
      <tr style="background-color:skyblue; position:sticky; bottom:0;">
        <td colspan="4"></td>
        <td style="text-align:right;">${{$total_dr}}</td>
        <td style="text-align:right;">${{$total_cr}}</td>
        <td style="text-align:right;">{{($balance < 0 ? "($".abs($balance).")" : '$'.$balance)}}</td>
        <td></td>
      </tr>
  </tbody>
  </table>

</div>
