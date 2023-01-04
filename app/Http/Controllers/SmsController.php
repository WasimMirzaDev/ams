<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\Tenant;
use App\Models\Message;
use GuzzleHttp\Client AS ClientInfo;
use Illuminate\Support\Facades\DB;


class SmsController extends Controller
{

  public function get_balance()
  {
    $accountSid = getenv("TWILIO_SID");
    $authToken = getenv("TWILIO_AUTH_TOKEN");
    $twilioNumber = getenv("TWILIO_NUMBER");
    $endpoint = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Balance.json";
    $client = new ClientInfo();
    $response = $client->get($endpoint, [
   'auth' => [
       $accountSid,
       $authToken
   ]
  ]);
  return $bi = json_decode($response->getBody());
  }

  public function get_residents()
  {
      return $residents = DB::SELECT("
      SELECT t.id, t.name, cell, email, concat(b.name, ' ', u.name) as address FROM tenants as t
      INNER JOIN tenant_units as tu on tu.tenant_id = t.id
      INNER JOIN units as u ON u.id = tu.unit_id
      INNER JOIN buildings as b ON b.id = u.building_id;
      ");
  }

  public function get_sms_history()
  {
      return $residents = DB::SELECT("
      SELECT h.sms, t.name, concat(b.name, ' ', u.name) as address, h.created_at FROM tenants as t
      INNER JOIN sms_history as h ON h.tenant_id = t.id
      INNER JOIN tenant_units as tu on tu.tenant_id = t.id
      INNER JOIN units as u ON u.id = tu.unit_id
      INNER JOIN buildings as b ON b.id = u.building_id
      WHERE date(h.created_at) = curdate()
      order by h.created_at desc;
      ");
  }


  public function get_twilio_messages()
  {
    $accountSid = getenv("TWILIO_SID");
    $authToken = getenv("TWILIO_AUTH_TOKEN");
    $twilioNumber = getenv("TWILIO_NUMBER");
    $twilio = new Client($accountSid, $authToken);
    $messages = $twilio->messages->read();
    foreach ($messages as $record) {

    }
  }

  public function index()
  {
    $sms = "";
    $error_ids = [];
    $success_ids = [];
    $bi = $this->get_balance();
    $list = $this->get_sms_history();
    $residents = $this->get_residents();
    return view('send-sms', get_defined_vars());
  }

  public function sendMessage(Request $request)
  {
      $this->validate($request, [
          // 'receiver' => 'required|max:15',
          'receiver' => 'required',
          'message' => 'required|min:5|max:155',
      ]);
      $accountSid = getenv("TWILIO_SID");
      $authToken = getenv("TWILIO_AUTH_TOKEN");
      $twilioNumber = getenv("TWILIO_NUMBER");


      $receivers = $request->receiver;
      $out_of = count($receivers);
      $sent = 0;
      $error_ids = [];
      $success_ids = [];
      $sms_history = array();
      $index = 0;
        foreach($receivers as $key=> $r)
        {
          $cell = json_decode($r, true)['cell'];
          $t = json_decode($r, true)['id'];
          $r = $cell;
            try
            {
              if(empty($r))
              {
                $r = "+1";
              }
                $client = new Client($accountSid, $authToken);
                $succ = $client->messages->create($r, [
                  'from' => $twilioNumber,
                  'body' => $request->message
                ]);
                $sent++;
                $success_ids[] = $t;
                $sms_history['tenant_id'] = $t;
                $sms_history['sms'] = $request->message;
                Message::create($sms_history);
                $index++;
            }
            catch(\Exception $e)
            {
              $error_ids[] = $t;
              $error_msg = $e->getMessage();
            }
        }
      $sms = $request->message;
      $result = "$sent out of $out_of Sent";
      if($sent == $out_of)
      {
        $alert="alert alert-success";
      }
      else
      {
        $alert="alert alert-danger";
      }
      $bi = $this->get_balance();
      $residents = $this->get_residents();
      $list = $this->get_sms_history();
      return view('send-sms', get_defined_vars());
  }

  public function sendMessageByNumber(Request $request)
  {

    $response['msg'] = 'Invalid Phone Number';
    $response['success'] = 0;

    if(!empty($request->cell_number))
    {
      $accountSid = getenv("TWILIO_SID");
      $authToken = getenv("TWILIO_AUTH_TOKEN");
      $twilioNumber = getenv("TWILIO_NUMBER");
      $client = new Client($accountSid, $authToken);

      try {
        $succ = $client->messages->create($request->cell_number, [
          'from' => $twilioNumber,
          'body' => $request->sms
        ]);
        $response['msg'] = "Sms sent successfully..!";
        $response['success'] = "1";
      }
      catch(\Exception $e)
      {
        $response['msg'] = $e->getMessage();
      }
    }
    return json_encode($response);
  }
}
// all done
