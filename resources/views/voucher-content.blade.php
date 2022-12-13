<div class="modal-header alert alert-danger">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h4 class="modal-title">{{$tenant->name}}</h4>
   <small style="font-size:10px;">{{!empty($pays_on) ? 'Rent pays' : ''}} {{$pays_on}}</small>
</div>
<div class="modal-body" style="padding-top:0px">

  <input type="hidden" name="id" value="{{!empty($v->id) ? $v->id : ''}}">
  <input type="hidden" id="unit_id" name="unit_id" value="{{!empty($v->id) ? $v->unit_id : 0}}">
  <input type="hidden" id="tenant_id" name="tenant_id" value="{{!empty($v->id) ? $v->tenant_id : 0}}">


  <div class="row">
    <section class="col col-md-2">
      Vch Date:
    </section>

    <section class="col col-md-4">
      <label class="input">
        <input type="text" class="form-control mydatepicker" autocomplete="off" name="date" value="{{(!empty($v->id)) ? date('d-m-Y', strtotime($v->date)) : date('d-m-Y')}}">
      </label>
    </section>
  </div>

  <div class="row">
    <section class="col col-md-2">
      Issue Date:
    </section>

    <section class="col col-md-4">
      <label class="input">
        <input type="text" class="form-control mydatepicker" autocomplete="off" name="i_date" value="{{(!empty($v->id)) ? date('d-m-Y', strtotime($v->i_date)) : date('d-m-Y')}}">
      </label>
    </section>

    <section class="col col-md-6">
      Last Date:
      <label class="input">
        <input type="text" class="form-control mydatepicker" autocomplete="off" name="l_date" value="{{(!empty($v->id)) ? date('d-m-Y', strtotime($v->l_date)) : date('d-m-Y')}}">
      </label>
    </section>
  </div>

    <div class="row" style="margin-top:5px;">
      <section class="col col-md-12">
        Remarks
        <label class="textarea">
          <textarea name="remarks" rows="3" cols="78">{{(!empty($v->id)) ? $v->remarks : ''}}</textarea>
        </label>
      </section>
    </div>

  <div class="row">
    <section class="col col-md-12"
      style="
      max-height: 250px;
      overflow-y: auto;
      ">
      <table class="table table-bordered">

        <thead class="bg-success">
          <tr style="color:black; background:lightgrey;">
            <td>Recurring Head</td>
            <td>Recurring Charges</td>
            <td> <button type="button" onclick="addMoreExtra()" class="btn btn-info btn-xs" style="border:1px solid white; background-color:black;" name="button">Add More</button> </td>
          </tr>
        </thead>

      <tbody id="extras">
        @php
          $total = 0;
        @endphp

      @if(empty($v->id))
        @foreach($end as $ed)
          @php
          $total += $ed->amount;
          @endphp
        <tr>
          <td>
            <select class="form-control" name="fh_id[]">
              @if(!empty($fee_head))
                @foreach($fee_head as $fh)
                  <option {{$ed->fh_id == $fh->fh_id ? 'selected' : ''}} value="{{$fh->fh_id}}">{{$fh->fh_name}}</option>
                @endforeach
              @endif
            </select>
          </td>
          <td>
            <label for="" class="input">
              <input type="number" class="form-control fh_amount" name="fh_amount[]" onkeyup="count_total()" value="{{$ed->amount}}">
            </label>
        </td>
        <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
        </tr>
        @endforeach

        @else

        @foreach($end as $ed)
          @php
          $total += $ed->fh_amount;
          @endphp
        <tr>
          <td>
            <select class="form-control" name="fh_id[]">
              @if(!empty($fee_head))
                @foreach($fee_head as $fh)
                  <option {{$ed->fh_id == $fh->fh_id ? 'selected' : ''}} value="{{$fh->fh_id}}">{{$fh->fh_name}}</option>
                @endforeach
              @endif
            </select>
          </td>
          <td>
            <label for="" class="input">
              <input type="number" class="form-control fh_amount" name="fh_amount[]" onkeyup="count_total()" value="{{$ed->fh_amount}}">
            </label>
        </td>
        <td> &nbsp; <button type="button" class="btn btn-xs btn-danger" onclick="removeRow(this.parentElement.parentElement)" name="button">X</button> </td>
        </tr>
        @endforeach


        @endif
      </tbody>
      </table>
    </section>
  </div>

</div>
<div class="modal-footer" style="text-align:left;">
  <span style="font-size:20px;"> Total: <span id="total" style="font-size:20px;">{{$total}}</span> </span>
  <button type="button" class="btn btn-danger" data-dismiss="modal" style="float:right">Cancel</button>
   &nbsp;&nbsp;&nbsp;
  <button class="btn btn-success" type="submit" onclick="save_voucher()" id="save_btn" style="float:right;margin-right:5px;">Generate</button>
</div>
