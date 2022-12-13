
@if(empty($ra))
  <div class="row">
    <div class="col col-md-7">
      <h5 class="alert alert-danger">No receivable voucher found..!</h5>
    </div>
  </div>
  @else
  <table width="80%" style="border:1px solid lightgrey;" style="max-height:300px; overflow-y:auto;">
    <thead>
      <tr bgcolor="#FFD583">
        <th width="3%;">Sr.</th>
        <th width="15%">Date</th>
        <th width="6%">Vch #</th>
        <th width="10%">Vch Amt</th>
        <th width="10%">Receivable</th>
        <th>Remarks</th>
        <th width="">Apartment</th>
        <th width="10%">Status</th>
        <th width="8%">Action</th>
      </tr>
    </thead>
    <tbody>
      @php
        $total = 0;
      @endphp
      @foreach($ra as $r)
      @php
        $total += $r->receiveable_amt;
      @endphp
      <tr style="background-color:{{$loop->iteration%2 == 0 ? '#ADD8E9' : '' }}">
        <td>{{$loop->iteration}}</td>
        <td>{{date('d-m-Y', strtotime($r->date))}}</td>
        <td>{{$r->id}}</td>
        <td>{{$r->vch_total}}</td>
        <td>{{$r->receiveable_amt}}</td>
        <td>{{$r->remarks}}</td>
        <td>{{$r->building_name}} : {{$r->unit_name}}</td>
        <td style="color:red;">{{$r->status}}</td>
        <td style="text-align:center;color:green;"><i style="cursor:pointer;color:dodgerblue;" class="fa fa-eye fa-lg"></i> &nbsp;&nbsp; <i onclick="createReceiving({{$r->id}})" style="cursor:pointer;" class="fa fa-money fa-lg"></i></td>
      </tr>
      @endforeach
    </tbody>
    <tbody style="background-color:#FFD583;">
      <tr>
        <th colspan="4">Total</th>
        <th colspan="5">{{$total}}</th>
      </tr>
    </tbody>
  </table>
@endif
