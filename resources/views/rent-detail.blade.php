<style media="screen">
li, a, tr {
  cursor:pointer !important;
}
tr:hover {
          background-color: rgb(219,221,231) !important;
        }
</style>

<div class="" style="width:80%; margin:0 auto;">
  <h2>Current Rent Details of all Apartments</h2>
  <table width="100%">
    <thead>
      <tr style="background-color:skyblue;">
        <td>Sr.</td>
        <td>Property</td>
        <td>Unit/Apartment</td>
        <td>Resident Name</td>
        <td>Frequency</td>
        <td>Rent</td>
      </tr>
    </thead>
    <tbody>
@php
$total_rent = 0;
@endphp
  @if(!empty($rd))
    @foreach($rd as $r)

    @if(!empty($r->rent))
      @php
        $total_rent+= $r->rent;
      @endphp
    @endif

      <tr
      style="
      @if($loop->iteration%2 == 0)
        background-color:gainsboro;
      @endif
      "
       >
        <td>{{$loop->iteration}}</td>
        <td>{{$r->building_name == 'None' ? '' : $r->building_name}}</td>
        <td>{{$r->unit_name}}</td>
        <td style="color:{{$r->tenant_name == 'Vacant' ? 'red;' : ''}}">{{$r->tenant_name}}</td>
        <td>{{$r->frequency}}</td>
        <td>{{$r->rent}}</td>
      </tr>
    @endforeach

  @endif
      <tr style="background-color:skyblue;">
        <td colspan="5"></td>
        <td>{{$total_rent}}</td>
      </tr>
  </tbody>
  </table>

</div>
