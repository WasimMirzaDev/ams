<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Adjustment;
use Illuminate\Support\Facades\DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function adjust_tenant_vouchers($tenant_id)
    {
          $rct_adjustable = DB::SELECT("SELECT r.unit_id, r.date, receiving_id, SUM(x.amount) as amt FROM (
      SELECT id as receiving_id, amount FROM `receivings` WHERE tenant_id = $tenant_id
      UNION ALL
      SELECT receiving_id, 0-SUM(adj_amount) AS amount FROM adjustments WHERE tenant_id = $tenant_id GROUP BY receiving_id
  		) AS x
          INNER JOIN receivings as r ON r.id = x.receiving_id
          GROUP BY r.unit_id, receiving_id, r.date
          HAVING amt > 0
          ORDER BY r.date, receiving_id");

      for($a=0; $a<count($rct_adjustable); $a++)
      {
        $adjustable_amt = $rct_adjustable[$a]->amt;
        $rec_id  = $rct_adjustable[$a]->receiving_id;
        $unit_id  = $rct_adjustable[$a]->unit_id;



        //
        $ra = DB::SELECT("
        SELECT c.tenant_id, c.date, c.challan_id as id, c.remarks, b.name as building_name, u.name as unit_name, (CASE WHEN (r_amt > 0 AND r_amt IS NOT NULL) AND c_amt > r_amt THEN 'Partial Paid' WHEN (r_amt = 0) OR r_amt IS NULL THEN 'Unpaid' ELSE 'Paid' END) AS status, c_amt AS vch_total, ifnull(c_amt, 0) - ifnull(r_amt, 0) AS receiveable_amt FROM
        (
         select v.tenant_id, v.date, v.remarks, v.unit_id, challan_id, sum(fh_amount) as c_amt
         FROM challan_details
         JOIN challans AS v ON v.id = challan_details.challan_id
         GROUP BY v.tenant_id, v.date, v.id, v.remarks, v.unit_id, challan_id
        ) AS c

        LEFT OUTER JOIN (select challan_id, sum(adj_amount) as r_amt FROM adjustments GROUP BY challan_id)
        AS r ON r.challan_id = c.challan_id

        LEFT OUTER JOIN units AS u ON u.id = c.unit_id
        LEFT OUTER JOIN buildings AS b ON b.id = u.building_id

        WHERE (c_amt <> r_amt || r_amt IS NULL) AND c.tenant_id = $tenant_id

            ORDER BY c.date, c.challan_id
        ");

        for($i = 0; $i<count($ra); $i++)
        {
          $challan_id = $ra[$i]->id;
          $challan_amt = $ra[$i]->receiveable_amt;

          //
          $challan_detail = DB::select("
          select fh_id, sum(fh_amount) as fh_amount from (
          select fh_id, sum(fh_amount) as fh_amount from challan_details WHERE challan_id = $challan_id group by fh_id
          union all
          select fh_id, sum(0-adj_amount) as fh_amount from adjustments WHERE challan_id = $challan_id group by fh_id
              ) as x GROUP BY fh_id
          ");
        for($u = 0; $u<count($challan_detail); $u++)
        {
          $fh_id = $challan_detail[$u]->fh_id;
          $fh_amount = $challan_detail[$u]->fh_amount;

            if($adjustable_amt <= 0)
            {
              break;
            }

            if($adjustable_amt > $fh_amount)
            {
              $adj_amount = $fh_amount;
              $adjustable_amt = $adjustable_amt - $adj_amount;
            }
            else
            {
              $adj_amount = $adjustable_amt;
              $adjustable_amt = 0;
            }
            if($adj_amount > 0)
            {
              $adj = new Adjustment;
              $adj->fh_id = $fh_id;
              $adj->adj_amount = $adj_amount;
              $adj->tenant_id = $tenant_id;
              $adj->challan_id = $challan_id;
              $adj->receiving_id = $rec_id;
              $adj->save();
            }
        }
        //
    }

        }
        //
    }
}
