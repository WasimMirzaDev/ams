<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    public function create()
    {
      $tenant = DB::SELECT("
      SELECT u.name as unit_name, b.name as address_name, tu.unit_id, t.id as tenant_id, t.name as tenant_name FROM tenants AS t
      LEFT OUTER JOIN tenant_units as tu ON tu.tenant_id = t.id
      LEFT OUTER JOIN units AS u ON u.id = tu.unit_id
      LEFT OUTER JOIN buildings AS b ON b.id = u.building_id
      ");
      return view('generate-ledger', get_defined_vars());
    }

    public function show(Request $request)
    {
      $tenant_id = $request->tenant_id;
      $tenant = Tenant::find($request->tenant_id);
      $from_date = date('Y-m-d', strtotime($request->from_date));
      $to_date = date('Y-m-d', strtotime($request->to_date));
      $ledger = DB::SELECT("
      SELECT '' as created_at, '' as ord, '' as id, '' as date, '' as pm, 'Opening' as remarks, (CASE WHEN sum(amt) > 0 THEN sum(amt) ELSE '' END)  as dr, (CASE WHEN sum(amt) < 0 THEN ABS(sum(amt)) ELSE '' END) as cr, '' as cheque_no
      FROM (
      SELECT opening AS amt FROM tenants WHERE id = $tenant_id

      UNION ALL
      SELECT 0-SUM(amount) AS amt FROM receivings AS r WHERE r.date < '$from_date' AND r.tenant_id = $tenant_id

      UNION ALL
      SELECT sum(cd.fh_amount) AS amt
      FROM challans AS c
      INNER JOIN challan_details AS cd ON c.id = cd.challan_id
      WHERE c.date < '$from_date' AND c.tenant_id = $tenant_id
      ) AS opening


      UNION ALL
      SELECT c.created_at, 1 as ord, c.id, c.date, '' as pm, c.remarks, sum(cd.fh_amount) as dr, '' as cr, '' as cheque_no
      FROM challans AS c
      INNER JOIN challan_details AS cd ON c.id = cd.challan_id
      WHERE c.tenant_id = $tenant_id AND c.date BETWEEN '$from_date' and '$to_date'
      GROUP BY c.created_at, c.id, c.date, c.date, c.remarks

      UNION ALL
      SELECT r.created_at, 2 as ord, r.id, r.date, p.name as pm, r.remarks, '' dr, amount as cr, cheque_no
      FROM receivings AS r
      LEFT OUTER JOIN payment_methods AS p ON p.id = r.pm_id
      WHERE r.tenant_id = $tenant_id AND r.date BETWEEN '$from_date' AND '$to_date'
      ORDER BY 4,2
      ");
      $balance = 0;
      if(!empty($ledger[0]->dr))
      {
        $balance = $ledger[0]->dr;
      }
      else if(!empty($ledger[0]->cr))
      {
        $balance = 0-$ledger[0]->cr;
      }
      if(!empty($request->home))
      {
        return response()->json([
            'statusCode' => 200,
            'html' => view('home-content.payment-history', get_defined_vars())->render(),
        ]);
      }
      return view('ledger', get_defined_vars());
    }

    public function rent_detail()
    {
      $rd = DB::SELECT("
      SELECT b.name AS building_name, u.name AS unit_name, IFNULL(t.name, 'Vacant') AS tenant_name, ps.name AS frequency, e.rent
      FROM units AS u
      LEFT OUTER JOIN buildings AS b ON b.id = u.building_id
      LEFT OUTER JOIN tenant_units AS tu ON tu.unit_id = u.id
      LEFT OUTER JOIN tenants AS t ON t.id = tu.tenant_id
      LEFT OUTER JOIN payment_schedules AS ps ON ps.c_id = t.pm_type
      LEFT OUTER JOIN
      (
      	SELECT e.tenant_id, e.unit_id, sum(ed.amount) as rent
          FROM enrolments AS e
          LEFT OUTER JOIN enrolmentd AS ed ON ed.enrolment_id = e.id
          GROUP BY e.tenant_id, e.unit_id
      ) AS e ON e.tenant_id = tu.tenant_id
      ORDER BY building_name, unit_name, tenant_name
      ");
        return view('rent-detail', get_defined_vars());
    }
}
