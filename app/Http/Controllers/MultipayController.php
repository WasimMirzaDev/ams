<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\TenantUnit;
use App\Models\FeeHead;
use App\Models\Challan;
use App\Models\ChallanDetail;
use Illuminate\Support\Facades\DB;
class MultipayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $total_buildings = Building::count();
      $total_units     = Unit::count();
      $total_tenants   = Tenant::count();
      $tenants = DB::SELECT("select * from tenants where id not in (select tenant_id from tenant_units)");
      $fee_head = FeeHead::all();
      $rd = DB::SELECT("
      SELECT u.id as unit_id, t.id as tenant_id, b.name AS building_name, u.name AS unit_name, IFNULL(t.name, 'Vacant') AS tenant_name, ifnull(rem_amt, 0) as rent,
      (
        SELECT date FROM `challans` as c
        inner join challan_details as cd ON c.id = cd.challan_id
        inner join fee_heads as fh on fh.fh_id = cd.fh_id
        where tenant_id = tu.tenant_id and unit_id = tu.unit_id and fh.is_electricbill = 1
        order by c.id desc limit 1
        ) as last_entry, tu.id as tu_id
      FROM units AS u
      LEFT OUTER JOIN buildings AS b ON b.id = u.building_id
      JOIN tenant_units AS tu ON tu.unit_id = u.id
      LEFT OUTER JOIN tenants AS t ON t.id = tu.tenant_id
      LEFT OUTER JOIN  (
      	SELECT tenant_id, SUM(amt) AS rem_amt FROM (
    	 SELECT tenant_id, SUM(fh_amount) AS amt FROM challan_details
              INNER JOIN challans as c ON c.id = challan_details.challan_id
               GROUP BY tenant_id
             UNION ALL
             SELECT tenant_id, 0-SUM(amount) AS amt FROM receivings GROUP BY tenant_id
             UNION ALL
             SELECT id as tenant_id, opening as amt from tenants
         ) AS trn GROUP BY tenant_id
      ) AS r ON r.tenant_id = t.id
      ORDER BY building_name, unit_name, tenant_name
      ");
        return view('multipay', get_defined_vars());
    }
    public function tenant_detail(Request $request)
    {
      $name_condition = !empty($request->tenant_name) ? " AND t.name like '%$request->tenant_name%' or b.name like '%$request->tenant_name%' " : "";
      $rd = DB::SELECT("
      SELECT u.id as unit_id, t.id as tenant_id, b.name AS building_name, u.name AS unit_name, IFNULL(t.name, 'Vacant') AS tenant_name, ifnull(rem_amt, 0) as rent
      FROM units AS u
      LEFT OUTER JOIN buildings AS b ON b.id = u.building_id
      LEFT OUTER JOIN tenant_units AS tu ON tu.unit_id = u.id
      LEFT OUTER JOIN tenants AS t ON t.id = tu.tenant_id
      LEFT OUTER JOIN  (
        SELECT tenant_id, SUM(amt) AS rem_amt FROM (
          SELECT tenant_id, SUM(fh_amount) AS amt FROM challan_details
          INNER JOIN challans as c ON c.id = challan_details.challan_id
          GROUP BY tenant_id
          UNION ALL
          SELECT tenant_id, 0-SUM(amount) AS amt FROM receivings GROUP BY tenant_id
          UNION ALL
          SELECT id as tenant_id, opening as amt from tenants
          ) AS trn GROUP BY tenant_id
          ) AS r ON r.tenant_id = t.id
          WHERE 1=1 $request->tenant_type $name_condition
          ORDER BY building_name, unit_name, tenant_name
          ");
          return view('home-content.tenant-detail', get_defined_vars());
        }

    public function store(Request $request)
    {
      $response['msg'] = 'Error';
      $response['success'] = 0;
      $response['last_entry'] = '';

      $tu_id = $request->tu_id;
      $amt = $request->amt;


      $tu = TenantUnit::where('id', $tu_id)->first();
      $tenant_id = $tu->tenant_id;
      $unit_id  = $tu->unit_id;

      $challan   =   new Challan;
       $ch = [
         'date'    => date('Y-m-d'),
         'i_date'    => date('Y-m-d'),
         'l_date'    => date('Y-m-d'),
         'tenant_id' => $tenant_id,
         'unit_id'   => $unit_id,
         'remarks'   =>  'One Time Charges'
       ];
       $challan = $challan->create($ch);

       if($challan)
       {
         $det = new ChallanDetail;
         $det->challan_id = $challan->id;
         $det->fh_id      = $request->fh_id;
         $det->fh_amount  = $amt;
         $det->save();
         $response['msg'] = 'Added Successfully';
         $response['success'] = 1;
         $is_electricbill = FeeHead::where('fh_id', $request->fh_id)->where('is_electricbill', 1)->first();
         $response['last_entry'] = !empty($is_electricbill->fh_id) ? date('m-d-Y') : '';
       }
       return json_encode($response);
    }
}
