<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiving;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{

    public function show(Request $request)
    {
       return view('get-profit-loss');
    }

    public function generate(request $request)
    {
      $from_date = date('Y-m-d', strtotime($request->from_date));
      $to_date = date('Y-m-d', strtotime($request->to_date));

      $ed = DB::SELECT("
      select et_id, et_name, sum(expense_amount) as expense_amount
      FROM expenses AS e
      LEFT OUTER JOIN expensetypes AS et ON et.et_id = e.expensetype_id
      WHERE e.expense_date BETWEEN '$from_date' AND '$to_date'
      GROUP BY et_id, et_name
      ORDER BY et_name
      ");

      $rd = DB::SELECT("select fh_name, a.fh_id, sum(adj_amount) as fh_amount from adjustments as a
      left outer join fee_heads as fh on fh.fh_id = a.fh_id
      where receiving_id in (select id from receivings where date between '$from_date' and '$to_date')
      group by a.fh_id, fh_name");
        return view('pl', get_defined_vars());
    }
}
