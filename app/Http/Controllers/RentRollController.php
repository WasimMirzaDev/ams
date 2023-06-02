<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class RentRollController extends Controller
{
    public function create()
    {

    }

    public function generate()
    {
        return view('rent-roll-generate', get_defined_vars());
    }

    public function show(Request $request)
    {
      $from_date = date('Y-m-d', strtotime($request->from_date));
      $to_date = date('Y-m-d', strtotime($request->to_date));
      // $ledger = DB::SELECT("
      //  SELECT  b.name as building_name, u.name as unit_name, t.name as tenant_name, date, amount FROM receivings as r
      //   INNER join units as u ON u.id = r.unit_id
      //   INNER join tenants as t on t.id = r.tenant_id
      //   left outer join buildings as b ON b.id = u.building_id
      //   WHERE r.date BETWEEN '$from_date' AND '$to_date'
      //   order by r.date
      // ");

      $ledger = DB::SELECT("
       SELECT  b.name as building_name, u.name as unit_name, t.name as tenant_name, sum(amount) as amount, date FROM receivings as r
        INNER join units as u ON u.id = r.unit_id
        INNER join tenants as t on t.id = r.tenant_id
        left outer join buildings as b ON b.id = u.building_id
        WHERE r.date BETWEEN '$from_date' AND '$to_date'
        GROUP BY b.name, u.name, t.name, date
        order by r.date
      ");


      $show_apartment = $request->show_apartment;
      $show_resident = $request->show_resident;
      return view('rent-roll', get_defined_vars());
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
