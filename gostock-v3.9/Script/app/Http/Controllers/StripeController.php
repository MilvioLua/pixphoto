<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Deposits;
use App\Models\User;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class StripeController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
    $this->settings = $settings::first();
    $this->request = $request;
  }

  public function show() {

    return response()->json([
      'success' => true,
      'insertBody' => '<i></i>'
    ]);

  }// End Show

  public function charge()
  {

    // Get Payment Gateway
    $payment = PaymentGateways::whereId($this->request->payment_gateway)->whereName('Stripe')->firstOrFail();

    //<---- Validation
		$validator = Validator::make($this->request->all(), [
      'amount' => 'required|integer|min:'.$this->settings->min_deposits_amount.'|max:'.$this->settings->max_deposits_amount,
      'payment_gateway' => 'required'
    ]);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

    $email    = Auth::user()->email;

  	$feeStripe   = $payment->fee;
  	$centsStripe =  $payment->fee_cents;

    if ($this->settings->currency_code == 'JPY') {
      $amountFixed = round($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe);
    } else {
      $amountFixed = number_format($this->request->amount + ($this->request->amount * $feeStripe / 100) + $centsStripe, 2, '.', ',');
    }

  	$amountGross = ($this->request->amount);
  	$amount   = $this->settings->currency_code == 'JPY' ? $amountFixed : ($amountFixed*100);
  	$currency_code = $this->settings->currency_code;
  	$description = trans('misc.add_funds_desc');
  	$nameSite = $this->settings->title;

    \Stripe\Stripe::setApiKey($payment->key_secret);

    $intent = null;
    try {
      if (isset($this->request->payment_method_id)) {
        # Create the PaymentIntent
        $intent = \Stripe\PaymentIntent::create([
          'payment_method' => $this->request->payment_method_id,
          'amount' => $amount,
          'currency' => $currency_code,
          "description" => $description,
          'confirmation_method' => 'manual',
          'confirm' => true
        ]);
      }
      if (isset($this->request->payment_intent_id)) {
        $intent = \Stripe\PaymentIntent::retrieve(
          $this->request->payment_intent_id
        );
        $intent->confirm();
      }
      return $this->generatePaymentResponse($intent);
    } catch (\Stripe\Exception\ApiErrorException $e) {
      # Display error on client
      return response()->json([
        'error' => $e->getMessage()
      ]);
    }
  }// End charge

  protected function generatePaymentResponse($intent) {
    # Note that if your API version is before 2019-02-11, 'requires_action'
    # appears as 'requires_source_action'.
    if ($intent->status == 'requires_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {
      # Tell the client to handle the action
      return response()->json([
        'requires_action' => true,
        'payment_intent_client_secret' => $intent->client_secret,
      ]);
    } else if ($intent->status == 'succeeded') {
      # The payment didn’t need any additional actions and completed!
      # Handle post-payment fulfillment

      // Insert DB
      $sql          = new Deposits;
      $sql->user_id = Auth::user()->id;
      $sql->txn_id  = $intent->id;
      $sql->amount  = $this->request->amount;
      $sql->payment_gateway = 'Stripe';
      $sql->save();

      //Add Funds to User
      User::find(Auth::user()->id)->increment('funds', $this->request->amount);

      return response()->json([
        "success" => true
      ]);
    } else {
      # Invalid status
      http_response_code(500);
      return response()->json(['error' => 'Invalid PaymentIntent status']);
    }
  }// End generatePaymentResponse

}
