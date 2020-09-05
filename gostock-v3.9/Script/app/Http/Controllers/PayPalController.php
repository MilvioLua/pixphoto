<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Models\Deposits;
use App\Models\User;
use Fahim\PaypalIPN\PaypalIPNListener;
use App\Helper;
use Mail;
use Carbon\Carbon;
use App\Models\PaymentGateways;

class PayPalController extends Controller
{
  public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function show() {

    if(!$this->request->expectsJson()) {
        abort(404);
    }

      // Get Payment Gateway
      $payment = PaymentGateways::findOrFail($this->request->payment_gateway);

      // Verify environment Sandbox or Live
      if ( $payment->sandbox == 'true') {
				$action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
				} else {
				$action = "https://www.paypal.com/cgi-bin/webscr";
				}

        $urlSuccess = url('user/dashboard/deposits');
  			$urlCancel   = url('user/dashboard/add/funds');
  			$urlPaypalIPN = url('paypal/ipn');

        $feePayPal   = $payment->fee;
  			$centsPayPal =  $payment->fee_cents;

  			$amountFixed = number_format($this->request->amount + ($this->request->amount * $feePayPal / 100) + $centsPayPal, 2, '.', ',');

  			return response()->json([
  					        'success' => true,
  					        'insertBody' => '<form id="form_pp" name="_xclick" action="'.$action.'" method="post"  style="display:none">
  					        <input type="hidden" name="cmd" value="_xclick">
  					        <input type="hidden" name="return" value="'.$urlSuccess.'">
  					        <input type="hidden" name="cancel_return"   value="'.$urlCancel.'">
  					        <input type="hidden" name="notify_url" value="'.$urlPaypalIPN.'">
  					        <input type="hidden" name="currency_code" value="'.$this->settings->currency_code.'">
  					        <input type="hidden" name="amount" id="amount" value="'.$amountFixed.'">
  					        <input type="hidden" name="custom" value="id='.Auth::user()->id.'&amount='.$this->request->amount.'">
  					        <input type="hidden" name="item_name" value="'.trans('misc.add_funds_desc').'">
  					        <input type="hidden" name="business" value="'.$payment->email.'">
  					        <input type="submit">
  					        </form> <script type="text/javascript">document._xclick.submit();</script>',
  					    ]);
    }

    public function paypalIpn() {

      $ipn = new PaypalIPNListener();

      $payment = PaymentGateways::find(1);

			if ($payment->sandbox == 'true') {
				// SandBox
				$ipn->use_sandbox = true;
				} else {
				// Real environment
				$ipn->use_sandbox = false;
				}

	    $verified = $ipn->processIpn();

			$custom  = $_POST['custom'];
			parse_str($custom, $funds);

			$payment_status = $_POST['payment_status'];
			$txn_id         = $_POST['txn_id'];
			$amount         = $_POST['mc_gross'];


	    if($verified) {
				if($payment_status == 'Completed') {
	          // Check outh POST variable and insert in DB

						$verifiedTxnId = Deposits::where('txn_id', $txn_id)->first();

			if(!isset($verifiedTxnId)) {

				$sql = new Deposits;
		   	$sql->user_id = $funds['id'];
			  $sql->txn_id = $txn_id;
				$sql->amount = $funds['amount'];
				$sql->payment_gateway = 'PayPal';
			  $sql->save();

				//Add Funds to User
				User::find($funds['id'])->increment('funds', $funds['amount']);

			}// <--- Verified Txn ID

	      } // <-- Payment status
	    } else {
	    	//Some thing went wrong in the payment !
	    }

    }//<----- End Method paypalIpn()
}
