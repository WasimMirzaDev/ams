<style media="screen">
li, a, tr {
  cursor:pointer !important;
}
tr:hover {
          background-color: rgb(219,221,231) !important;
        }
</style>
<br>
<div class="" style="width:60%; margin:0 auto;">
  <h2 style="margin-bottom:4px;">Profit and Loss Report</h2>
<span style="font-size:18px;"><b>{{date('F-d-Y', strtotime($from_date))}}</b> To <b>{{date('F-d-Y', strtotime($to_date))}}</b></span>
<br>
<br>
  <table width="100%" >
    <thead>
      <tr style="background-color:skyblue;">
        <td>Particulars</td>
        <td width="100px;">Amount ($)</td>
        <td width="100px;">Amount ($)</td>
      </tr>
    </thead>
    <tbody style="">
      <tr style="font-weight:bold; font-size:18px;">
        <td>Revenues</td>
        <td></td>
        <td></td>
      </tr>

  @php
  $total_income = 0;
  @endphp
  @if(!empty($rd))
    @foreach($rd as $r)
    @php
      $total_income += $r->fh_amount;
    @endphp
      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      "
       >
        <td>{{$r->fh_name}}</td>
        <td style="text-align:right;">${{$r->fh_amount}}</td>
        <td></td>
      </tr>
    @endforeach

  @endif
      <tr style="font-weight:bold; font-size:18px;">
        <td>Total Revenues (R)</td>
        <td></td>
        <td align="right">${{$total_income}}</td>
      </tr>
    </tbody>
      <tbody style="">
      <tr style="font-weight:bold; font-size:18px;">
        <td>Expenses</td>
        <td></td>
        <td></td>
      </tr>
      @php
      $total_expense = 0;
      @endphp
      @if(!empty($ed))

        @foreach($ed as $e)
        @php
          $total_expense += $e->expense_amount;
        @endphp
          <tr
          style="
          @if($loop->iteration%2 == 0)
            background-color:gainsboro;
          @endif
          "
           >
            <td>{{$e->et_name}}</td>
            <td style="text-align:right;">${{$e->expense_amount}}</td>
            <td></td>
          </tr>
        @endforeach

      @endif
          <tr style="font-weight:bold; font-size:18px;">
            <td>Total Expenses (E)</td>
            <td></td>
            <td align="right">${{$total_expense}}</td>
          </tr>
          @php
            $total = $total_income - $total_expense;
          @endphp
          <tr style="background-color:skyblue;font-weight:bold; font-size:18px;">
            <td>Net Income (R - E)</td>
            <td></td>
            <td align="right">
              <?php
              if($total > 0)
              {
                echo '$'. $total;
              }
              else
              {
                echo '('.'$'.abs($total).')';
              }
               ?>
            </td>
          </tr>

  </tbody>
  </table>

<br>
<br>


</div>
