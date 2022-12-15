<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SmsController extends Controller
{
  public function index()
  {
      return view('send-sms');
  }

  public function sendMessage(Request $request)
  {
      $this->validate($request, [
          'receiver' => 'required|max:15',
          'message' => 'required|min:5|max:155',
      ]);

      try {
          $accountSid = getenv("TWILIO_SID");
          $authToken = getenv("TWILIO_TOKEN");
          $twilioNumber = getenv("TWILIO_NUMBER");

          $client = new Client($accountSid, $authToken);


          $receivers = $request->receiver;
          foreach($receivers as $r)
          {
            if(!empty($r))
            {
              $client->messages->create($r, [
                'from' => $twilioNumber,
                'body' => $request->message
              ]);
            }
          }


          return back()
          ->with('success','Sms has been successfully sent.');

      } catch (\Exception $e) {
          dd($e->getMessage());
          return back()
          ->with('error', $e->getMessage());
      }
  }
}
