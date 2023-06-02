<table width="100%" >
  <tbody>
@php
$total_rent = 0;
$color = "black;";
$prev_building="...";
@endphp
@if(!empty($rd))
  @foreach($rd as $r)

  @if(!empty($r->rent))
    @php
      $total_rent+= $r->rent;
    @endphp
  @endif

  @if($r->rent > 0)
  @php
   $color = "red;"
   @endphp
  @endif

  @if($r->rent < 0)
  @php
   $color = "blue;"
   @endphp
  @endif

  @if($r->rent == 0)
  @php
   $color = "black;"
   @endphp
  @endif




  @if($r->building_name != $prev_building)
  <tr style="background-color:#fed8b1;">
    <td>Sr.</td>
    <td>Property/Address</td>
    <td>Unit/Apartment</td>
    <td>Residential</td>
    <td>Amount Due</td>
  </tr>
  @endif

    <tr
    style="
    @if($loop->iteration%2 == 0)
    /* background-color:gainsboro; */
    @endif
    "
    @if($r->tenant_name != 'Vacant')
    onclick="get_receivables({{$r->tenant_id}})"
    @endif

    @if($r->tenant_name == 'Vacant')
         onclick="add_to_unit({{$r->unit_id}})"
    @endif
     >
      <td>{{$loop->iteration}}</td>
      <td>
        <?php
        if($r->building_name != $prev_building)
        {
          echo $r->building_name == 'None' ? '' : $r->building_name;
        }
        $prev_building = $r->building_name;
        ?>
      </td>
      <td>{{$r->unit_name}}</td>
      <td style="background-color: {{$r->tenant_name == 'Vacant' ? 'lightgreen;' : ';'}} color:{{$color}}">{{$r->tenant_name}}</td>
      <td style="color:{{$color}}">
        <?php
        if($r->tenant_name == 'Vacant')
        {

        }
        else if($r->rent < 0)
        {
          echo '('.number_format(abs($r->rent), 2).')';
        }
        else if($r->rent > 0)
        {
          echo number_format($r->rent, 2);
        }
        ?>
      </td>
    </tr>
  @endforeach

@endif
</tbody>
</table>
