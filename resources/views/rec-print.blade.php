<!DOCTYPE html>
<html>
<head>
    <title>Rent Receipt</title>
    <style media="screen">
    @page { margin: 20px; }
body { margin: 0px; }
    </style>
</head>
<body style="font-size:16px;">
    <center>
    <b style="font-size:20px;">
        {{$cmp_name}}
    </b>
    </center>
    <center>
        <span style="font-size:12px;">
            {{$cmp_address}}
        </span>
    </center>
    <br>
    <center>
        <b style="font-size:18px;">Payment Receipt</b>
    </center>

<span style="font-size:12px;">{{$address}}, Unit: {{$unit}}</span>
<fieldset>
  <table width="100%" border="0">
    <tr>
      <td width="100%" style="text-align:center;">
        @if(!empty($remarks))
        <span>{{$remarks}}</span>
        <br>
        @endif
        <span style="font-size:25px;">
          <b>${{$rct_amt}}</b>
        </span>

        <br>
            Received From: <b>{{$customer}}</b>
            <br>
            Payment Method: <b>{{$pm_name}} {{!empty($cheque_no) ? '('.$cheque_no.')' : ''}}</b>
            <br>
            @if ($pending_amt > 0)
                Balance Due: <b>(${{$pending_amt}})</b>
            @else
                Balance Due: <b>${{abs($pending_amt)}}</b>
            @endif
      </td>

    </tr>
  </table>
</fieldset>
<center>
    Thank You
    <br>
<b>{{$date}}</b>
</center>
</body>
</html>
