<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeeHead;
use App\Models\Tenant;
use App\Models\TenantUnit;
use App\Models\Enrolment;
use App\Models\EnrolmentD;
use App\Models\Daysofweek;
use App\Models\Unit;
use App\Models\Challan;
use App\Models\ChallanDetail;
use App\Models\Receiving;
use App\Models\Adjustment;
use Validator;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{

  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

   public function index()
   {
      $tenant = DB::SELECT("
      SELECT u.name as unit_name, b.name as address_name, tu.unit_id, t.id as tenant_id, t.name as tenant_name FROM tenants AS t
      JOIN tenant_units as tu ON tu.tenant_id = t.id
      JOIN units AS u ON u.id = tu.unit_id
      JOIN buildings AS b ON b.id = u.building_id
      WHERE t.id in (select tenant_id FROM tenant_units) AND t.active = 1
      ");

      $challans = DB::SELECT("SELECT c.date, b.name AS address_name, t.name as tenant_name, u.name as unit_name, c.id, sum(cd.fh_amount) AS total, c.i_date, c.l_date, c.remarks
                      FROM challans AS c
                      LEFT OUTER JOIN tenants AS t ON t.id = c.tenant_id
                      LEFT OUTER JOIN units AS u ON u.id = c.unit_id
                      LEFT OUTER JOIN buildings AS b ON b.id = u.building_id
                      LEFT OUTER JOIN challan_details AS cd ON cd.challan_id = c.id
                      GROUP BY c.date, b.name, t.name, u.name, c.id, c.i_date, c.l_date, c.remarks
                      ORDER BY c.date desc
");

      $fee_head = FeeHead::where('fh_active', 1)->get();
       return view('vouchers', get_defined_vars());
   }

   public function Getsuffix($number)
   {
     $ends = array('th','st','nd','rd','th','th','th','th','th','th');
     if (($number %100) >= 11 && ($number%100) <= 13)
        $abbreviation = $number. 'th';
     else
        $abbreviation = $number. $ends[$number % 10];

        return $abbreviation;
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create(Request $request)
   {
     $tenant = Tenant::find($request->tenant_id);
     $ps = $tenant->pm_type;
     $pays_on = "";
     if($ps == 'd')
     {
       $pays_on = "Daily";
     }
     if($ps == 'w')
     {

        $day = Daysofweek::where('number', $tenant->weekly_daynum)->first();

        $pays_on = !empty($day->name) ? "on every ".$day->name : '';
     }

     if($ps == 'm')
     {
        $day = $tenant->monthly_datenum;
        $pays_on = !empty($day->name) ? "on every ".$day->name : '';
     }
     if($ps == 'y')
     {
       $pays_on = !empty($day->name) ? "on every ".date('d F', strtotime($tenant->yearly_month_date)) : '';
     }

      $fee_head = FeeHead::where('fh_active', 1)->get();
      $en = Enrolment::with('en_d')
      ->where([['enrolments.tenant_id', '=', $request->tenant_id], ['enrolments.unit_id', '=', $request->unit_id]])->first();
      $end = $en->en_d;
      return response()->json([
          'statusCode' => 200,
          'html' => view('voucher-content', get_defined_vars())->render(),
      ]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
     $validator = Validator::make($request->all(), [
       'i_date' => 'required',
       'l_date' => 'required',
       'tenant_id' => 'required',
       'unit_id' => 'required',
       'fh_id' => 'required',
       'fh_amount' => 'required',
       'date'     => 'required'
     ]);
     $fh_id = $request->fh_id;
     $fh_amount = $request->fh_amount;


     if ($validator->passes()) {
       $challan   =   Challan::updateOrCreate(
                       [
                           'id' => $request->id
                       ],
                       [
                         'date'    => date('Y-m-d', strtotime($request->date)),
                         'i_date'    => date('Y-m-d', strtotime($request->i_date)),
                         'l_date'    => date('Y-m-d', strtotime($request->l_date)),
                         'tenant_id' => empty($request->tenant_id)  ? 0 : $request->tenant_id,
                         'unit_id'   => empty($request->unit_id) ? 0 : $request->unit_id,
                         'remarks'   =>  $request->remarks
                       ]);
       if($challan)
       {
         ChallanDetail::where('challan_id', '=', $challan->id)->delete();
         for($a=0; $a<count($fh_amount); $a++)
         {
           if(!empty($fh_amount[$a]))
           {
             $det = new ChallanDetail;
             $det->challan_id = $challan->id;
             $det->fh_id      = $fh_id[$a];
             $det->fh_amount      = $fh_amount[$a];
             $det->save();
           }
         }
         Adjustment::where('challan_id', $challan->id)->delete();
         $this->adjust_tenant_vouchers($challan->tenant_id);
         $b = array_merge($request->all());
         $b['id'] = $challan->id;
         $b['tenant_name'] = $challan->tenant->name;
         $b['unit_name'] = $challan->unit->name;
         $b['address_name']  = $challan->unit->building->name;
         $b['challan_total'] = ChallanDetail::where('challan_id', $challan->id)->sum('fh_amount');
         $msg = "Saved successfully..!";
         if($request->id > 0)
         {
           $msg = "Updated successfully..!";
         }
         return response()->json(['success'=>1, 'msg'=>$msg, 'data' => $b]);
       }
     }
     return response()->json(['success' => 0, 'msg'=>$validator->errors()->all()]);
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Product  $extra
    * @return \Illuminate\Http\Response
    */
   public function show(Product $extra)
   {
       return view('extras.show',compact('extra'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $extra
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {

     $v = Challan::whereId($id)->first();
     $tenant_id = $v->tenant_id;
     $unit_id = $v->unit_id;
     $tenant = Tenant::find($tenant_id);
     $ps = $tenant->pm_type;
     $pays_on = "";
         if($ps == 'd')
     {
       $pays_on = "Daily";
     }
     if($ps == 'w')
     {

        $day = Daysofweek::where('number', $tenant->weekly_daynum)->first();

        $pays_on = !empty($day->name) ? "on every ".$day->name : '';
     }

     if($ps == 'm')
     {
        $day = $tenant->monthly_datenum;
        $pays_on = !empty($day->name) ? "on every ".$day->name : '';
     }
     if($ps == 'y')
     {
       $pays_on = !empty($day->name) ? "on every ".date('d F', strtotime($tenant->yearly_month_date)) : '';
     }


      $fee_head = FeeHead::where('fh_active', 1)->get();

      $end = $v->voucher_detail;

      return response()->json([
          'statusCode' => 200,
          'html' => view('voucher-content', get_defined_vars())->render(),
      ]);
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Product $product)
   {
       $request->validate([
           'name' => 'required',
           'detail' => 'required',
       ]);

       $product->update($request->all());

       return redirect()->route('products.index')
                       ->with('success','Product updated successfully');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
      $tenant_id = Challan::whereId($id)->pluck('tenant_id')->first();
      $deleted = Challan::find($id)->delete();
      if($deleted)
      {
        $cd = ChallanDetail::where('challan_id', $id)->delete();
        $del_rec = Adjustment::where('challan_id', $id)->delete();
        $this->adjust_tenant_vouchers($tenant_id);
        return response()->json(['success'=>1, 'msg'=>'Deleted Successfully!']);
      }
      return response()->json(['success'=>0, 'msg'=>'Error in deleting record!']);
    }
}
