<?php

$u  = 'fiftaeer_ams';
$db = 'fiftaeer_ams';
$p  = '#*2NnFVwmwMh';


// $u  = 'root';
// $db = 'fiftaeer_ams';
// $p  = '';

$con = mysqli_connect('localhost', $u, $p, $db);

if($con)
{
    echo 'connected';
}
// $query = "
// select e.id as en_id, tu.unit_id, t.id as tenant_id, s.name, t.weekly_daynum, t.monthly_datenum, t.yearly_month_date, s.no_days,
// (select
//  count(*) from challans
//  inner join challan_details ON challans.id = challan_details.challan_id
//  inner join fee_heads as fh on fh.fh_id = challan_details.fh_id and fh.is_rent = 1
//  where i_date between DATE_SUB(curdate(), INTERVAL s.no_days-1 DAY) and curdate() and challans.tenant_id = t.id and challans.unit_id = tu.unit_id
//  and fh.is_rent = 1
// ) as total
// FROM tenants AS t
// LEFT OUTER JOIN payment_schedules AS s ON s.c_id = t.pm_type
// INNER JOIN tenant_units as tu ON tu.tenant_id = t.id
// inner join enrolments as e ON e.unit_id = tu.unit_id and e.tenant_id = tu.tenant_id and e.active = 1
// where t.id = 517
// having total = 0
// ";

// mysqli_query($con, "TRUNCATE TABLE challans;");
// mysqli_query($con, "TRUNCATE TABLE challan_details;");


// mysqli_query($con, "update enrolmentd set next_voucher = start_date");


$query = "
SELECT ed.id as end_id, tu.tenant_id, tu.unit_id, ed.fh_id, ed.amount, ed.last_voucher, ed.start_date, datediff(curdate(), ed.start_date) as diff, ps.no_days,
substring_index(format(datediff(curdate(), ed.start_date) / ps.no_days, 1), '.', -1) as reminder, ed.next_voucher,
DATE_ADD(next_voucher, INTERVAL ps.no_days DAY) as next_next_voucher
FROM enrolmentd as ed
INNER JOIN payment_schedules as ps ON ps.c_id = ed.pm_type
INNER JOIN enrolments as e on e.id = ed.enrolment_id
INNER JOIN tenant_units as tu ON tu.tenant_id = e.tenant_id and tu.unit_id = e.unit_id
where ed.next_voucher <= curdate() and last_voucher <> next_voucher;
";
$exe = mysqli_query($con, $query);
while($d = mysqli_fetch_assoc($exe))
{
        extract($d);
    $date = date('Y-m-d');
    $date = $next_voucher;
    if($amount > 0)
    {
        $q = "INSERT INTO challans (date, i_date, l_date, tenant_id, unit_id, remarks)
        values('$date', '$date', '$date', '$tenant_id', '$unit_id', '')";
        echo "<pre>";
        echo $q;
            if(mysqli_query($con, $q))
            {
                $challan_id = mysqli_insert_id($con);
                $qd = "
                INSERT INTO challan_details (challan_id, fh_id, fh_amount)
                VALUES($challan_id, $fh_id, $amount)
                ";
                if(mysqli_query($con, $qd))
                {
                    $update_enrolmentd = mysqli_query($con, "UPDATE enrolmentd SET last_voucher = '$date', next_voucher = '$next_next_voucher' where id = $end_id");
                    echo 'success';
                }
                else
                {
                    echo $qd;
                }
            }
            else
            {
                echo 'failed';
            }
            echo "<br/>";
    }
}

?>
