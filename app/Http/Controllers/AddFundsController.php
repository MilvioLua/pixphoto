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

class AddFundsController extends Controller
{
	public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

    public function send()
		{

			if($this->settings->sell_option == 'off') {
				return response()->json([
						'success' => false,
						'errors' => ['error' => trans('misc.error') ],
				]);
			}

			if($this->settings->currency_position == 'right') {
				$currencyPosition =  2;
			} else {
				$currencyPosition =  null;
			}

			Validator::extend('check_payment_gateway', function($attribute, $value, $parameters)
			{
				return PaymentGateways::find($value);
			});

			$messages = array (
			'amount.min' => trans('misc.amount_minimum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'amount.max' => trans('misc.amount_maximum'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
			'payment_gateway.check_payment_gateway' => trans('misc.payments_error'),
		);

		//<---- Validation
		$validator = Validator::make($this->request->all(), [
				'amount' => 'required|integer|min:'.$this->settings->min_deposits_amount.'|max:'.$this->settings->max_deposits_amount,
				'payment_gateway' => 'required|check_payment_gateway',
	    	],$messages);

			if ($validator->fails()) {
			        return response()->json([
					        'success' => false,
					        'errors' => $validator->getMessageBag()->toArray(),
					    ]);
			    }

					// Get name of Payment Gateway
					$payment = PaymentGateways::find($this->request->payment_gateway);

					if(!$payment) {
						return response()->json([
								'success' => false,
								'errors' => ['error' => trans('misc.payments_error')],
						]);
					}

					// Send data to the payment processor
						return redirect()->route(str_slug($payment->name), $this->request->except(['_token']));

					}//<--------- End Method  Send

}
