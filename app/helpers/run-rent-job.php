<?php



function add_rent()
{
  // $u  = 'root';
  // $db = 'fiftaeer_ams';
  // $p  = '';

  $u  = 'fiftaeer_ams';
  $db = 'fiftaeer_ams';
  $p  = '#*2NnFVwmwMh';

  $con = mysqli_connect('localhost', $u, $p, $db);
  
    mysqli_query($con, "update `payment_schedules` set no_days = (SELECT DAY(LAST_DAY(CURRENT_DATE())) AS Number_of_Days) where c_id = 'm'");
    
    
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
                  }
                  else
                  {
                  }
              }
              else
              {

              }
      }
  }
  return true;
}
?>
