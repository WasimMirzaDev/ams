<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\TenantUnit;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
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
      ORDER BY building_name, unit_name, tenant_name
      ");
        return view('home', get_defined_vars());
    }
    public function tenant_detail(Request $request)
    {
      $name_condition = !empty($request->tenant_name) ? " AND t.name like '%$request->tenant_name%' " : "";
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
        
    public function add_to_tenant(Request $request)
    {
        // $tu = new TenantUnit;
        // $tu->unit_id = $request->tenant_unit;
        // $tu->tenant_id = $request->tenant_tenant;
        // $tu->save();
        // Tenant::where('id', $request->tenant_tenant)->update(['unit_id'=>$request->tenant_unit]);
        // return redirect()->route('home')
        //               ->with('success',[]);
       
       
       
       
   
    }
}
