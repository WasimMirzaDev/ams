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

  public function sendMessage($message, $recipients)
  {
      $account_sid = getenv("TWILIO_SID");
      $auth_token = getenv("TWILIO_AUTH_TOKEN");
      $twilio_number = getenv("TWILIO_NUMBER");
      $client = new Client($account_sid, $auth_token);
      $client->messages->create($recipients, ['from' => $twilio_number, 'body' => $message]);
  }
}
