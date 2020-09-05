@extends('dashboard.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<style>
/**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
.StripeElement {
  box-sizing: border-box;

  height: 35px;

  padding: 8px 12px;

  border: 1px solid #ccc;
  background-color: white;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;

  margin-top: 10px;
}

.StripeElement--focus {
	border-color: #3c8dbc;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('misc.add_funds') }}
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

          <div class="alert alert-danger display-none" id="error">
              <ul class="list-unstyled" id="showErrors"></ul>
            </div>

        	<div class="content">
            <div class="row">
              <div class="box box-danger">

                <!-- form start -->
                <form class="form-horizontal padding-top-20" method="post" action="{{url('user/dashboard/add/funds')}}" id="formAddFunds">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.amount') }}</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                        <div class="input-group-addon">
                          {{ $settings->currency_symbol }}
                        </div>
                        <input type="number" min="{{ $settings->min_deposits_amount }}" max="{{ $settings->max_deposits_amount }}" autocomplete="off" value="" name="amount" class="form-control onlyNumber" placeholder="{{ trans('misc.amount') }}">
                      </div>
                      <p class="help-block margin-bottom-zero">
                        + {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="handlingFee">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }} {{ trans('misc.handling_fee') }}

                        <strong>{{ trans('misc.total') }}:</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="total">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}
                      </p>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.payment_gateway') }}</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-credit-card"></i>
                        </div>
                      	<select name="payment_gateway" class="form-control" id="paymentGateway">

                          <option value="">{{trans('misc.select_payment_gateway')}}</option>

                          @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)

                            @php

                            if($payment->type == 'card' ) {
                              $paymentName = trans('misc.debit_credit_card') . ' ('.$payment->name.')';
                            } else {
                              $paymentName = $payment->name;
                            }

                            @endphp
                            <option value="{{$payment->id}}">{{$paymentName}}</option>
                          @endforeach

                        </select>
                      </div>

                      <div id="stripeContainer" class="display-none">
												<div id="card-element" class="margin-bottom-10">
													<!-- A Stripe Element will be inserted here. -->
												</div>
												<!-- Used to display form errors. -->
												<div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
											</div>

                    </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a href="{{url('user/dashboard/deposits')}}" class="btn btn-default"><i class="fa fa-long-arrow-left"></i> {{ trans('auth.back') }}</a>
                    <button type="submit" class="btn btn-success pull-right spin-btn" id="addFundsBtn">
                      {{ trans('misc.add_funds') }} <span></span>
                    </button>
                  </div><!-- /.box-footer -->
                </form>

        		</div><!-- /.row -->

        	</div><!-- /.content -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- icheck -->
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/js/jquery.form.js') }}"></script>
  <script src="https://js.stripe.com/v3/"></script>
  <script src='https://js.paystack.co/v1/inline.js'></script>

	<script type="text/javascript">

  $('#paymentGateway').on('change', function() {

  		if($(this).val() == '2') {
  			$('#stripeContainer').slideDown();
  		} else {
  			$('#stripeContainer').slideUp();
  		}
  });

  @if(isset($_stripe->key))
  // Create a Stripe client.
  var stripe = Stripe('{{$_stripe->key}}');

  // Create an instance of Elements.
  var elements = stripe.elements();

  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
    base: {
      color: '#32325d',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4'
      }
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a'
    }
  };

  // Create an instance of the card Element.
  var cardElement = elements.create('card', {style: style, hidePostalCode: true});

  // Add an instance of the card Element into the `card-element` <div>.
  cardElement.mount('#card-element');

  // Handle real-time validation errors from the card Element.
  cardElement.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    var payment = $('#paymentGateway').val();

    if(payment == 2) {
      if (event.error) {
    		displayError.classList.remove('display-none');
        displayError.textContent = event.error.message;
        $('#addFundsBtn').removeAttr('disabled');
      } else {
    		displayError.classList.add('display-none');
        displayError.textContent = '';
      }
    }

  });

  var cardButton = document.getElementById('addFundsBtn');

  cardButton.addEventListener('click', function(ev) {

  	ev.preventDefault();

    var payment = $('#paymentGateway').val();

    if(payment == 2) {

    stripe.createPaymentMethod('card', cardElement, {
      billing_details: {email: '{{Auth::user()->email}}'}
    }).then(function(result) {
      if (result.error) {

        if(result.error.type == 'invalid_request_error') {

          swal({
              type: 'error',
              title: 'Oops...',
              text: result.error.message,
            });
        }
        $('#addFundsBtn').removeAttr('disabled');

      } else {

        $('#addFundsBtn').attr({'disabled' : 'true'});

        // Otherwise send paymentMethod.id to your server
        $('input[name=payment_method_id]').remove();

  			var $input = $('<input id=payment_method_id type=hidden name=payment_method_id />').val(result.paymentMethod.id);
  			$('#formAddFunds').append($input);

  			$.ajax({
   		 	headers: {
           	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       		},
   		   type: "POST",
  			 dataType: 'json',
   		   url:"{{url('payment/stripe/charge')}}",
   		   data: $('#formAddFunds').serialize(),
   		   success: function(result) {
             handleServerResponse(result);

             if(result.success == false) {
               $('#addFundsBtn').removeAttr('disabled');
             }
   		 }//<-- RESULT
   	   })

      }//ELSE
    });
  }//PAYMENT STRIPE
  });

  function handleServerResponse(response) {
    if (response.error) {
      swal({
          type: 'error',
          title: 'Oops...',
          text: response.error,
        });
        $('#addFundsBtn').removeAttr('disabled');

    } else if (response.requires_action) {
      // Use Stripe.js to handle required card action
      stripe.handleCardAction(
        response.payment_intent_client_secret
      ).then(function(result) {
        if (result.error) {
          swal({
              type: 'error',
              title: 'Oops...',
              text: '{{trans('misc.error_payment_stripe_3d')}}',
            });
            $('#addFundsBtn').removeAttr('disabled');
        } else {
          // The card action has been handled
          // The PaymentIntent can be confirmed again on the server

  				var $input = $('<input type=hidden name=payment_intent_id />').val(result.paymentIntent.id);
  				$('#formAddFunds').append($input);

          $('input[name=payment_method_id]').remove();

  				$.ajax({
  	 		 	headers: {
  	         	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  	     		},
  	 		   type: "POST",
  				 dataType: 'json',
  	 		   url:"{{url('payment/stripe/charge')}}",
  	 		   data: $('#formAddFunds').serialize(),
  	 		   success: function(result){

  					 if(result.success) {
               $('#addFundsBtn').attr({'disabled' : 'true'});
               $url = '{{url('user/dashboard/deposits')}}';
           		  window.location.href = $url;
  					 } else {
               swal({
                   type: 'error',
                   title: 'Oops...',
                   text: result.error,
                 });
                 $('#addFundsBtn').removeAttr('disabled');
  					 }
  	 		 }//<-- RESULT
  	 	   })
        }// ELSE
      });
    } else {
      // Show success message
      if(response.success) {
        $('#addFundsBtn').attr({'disabled' : 'true'});
        $url = '{{url('user/dashboard/deposits')}}';
    		window.location.href = $url;
      }

    }
  }
  @endif

  $(document).ready(function() {

    //<---------------- Add Funds ----------->>>>
			$(document).on('click','#addFundsBtn',function(s){

				s.preventDefault();
				var element = $(this);
        var payment = $('#paymentGateway').val();
				element.attr({'disabled' : 'true'});

				(function(){
					 $("#formAddFunds").ajaxForm({
					 dataType : 'json',
					 success:  function(result) {

             // success
             if(result.success == true && result.insertBody) {

               $('#bodyContainer').html('');

              $(result.insertBody).appendTo("#bodyContainer");

              if (payment != 1 && payment != 2) {
                element.removeAttr('disabled');
              }

               $('#error').fadeOut();

             } else if(result.success == true && result.url) {
               window.location.href = result.url;
             } else {

               var error = '';

               for($key in result.errors) {
                 error += '<li><i class="glyphicon glyphicon-remove myicon-right"></i> ' + result.errors[$key] + '</li>';
               }

               $('#showErrors').html(error);
               $('#error').fadeIn(500);
               element.removeAttr('disabled');

             }

						},
            error: function(responseText, statusText, xhr, $form) {
                // error
                element.removeAttr('disabled');
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Error ('+xhr+')',
                  });
            }
					}).submit();
				})(); //<--- FUNCTION %
			});//<<<-------- * END FUNCTION CLICK * ---->>>>
	//<---------------- End Add Funds ----------->>>>

      $(".onlyNumber").keydown(function (e) {
          // Allow: backspace, delete, tab, escape, enter and .
          if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
               // Allow: Ctrl+A, Command+A
              (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
               // Allow: home, end, left, right, down, up
              (e.keyCode >= 35 && e.keyCode <= 40)) {
                   // let it happen, don't do anything
                   return;
          }
          // Ensure that it is a number and stop the keypress
          if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
              e.preventDefault();
          }
      });

  });// document ready

  @if($settings->currency_code == 'JPY')
  $decimal = 0;
  @else
  $decimal = 2;
  @endif

  $('#paymentGateway').on('change', function() {

    var valueOriginal = $('.onlyNumber').val();
    var value = parseFloat($('.onlyNumber').val());
    var element = $(this).val();

    if(element != '') {
      // Fees
      switch(parseFloat(element)) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case {{$payment->id}}:
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var total = (value + amount);

      if( valueOriginal != '' || valueOriginal !=  0 ) {
        $('#handlingFee').html(amount.toFixed($decimal));
        $('#total').html(total.toFixed($decimal));
      }
    }

});

//<-------- * TRIM * ----------->

$('.onlyNumber').on('keyup', function() {

    var valueOriginal = $(this).val();
    var value = parseFloat($(this).val());
    var paymentGateway = $('#paymentGateway').val();

    if(paymentGateway != '') {

      switch(parseFloat(paymentGateway)) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case {{$payment->id}}:
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var total = (value + amount);

      if( valueOriginal != '' || valueOriginal !=  0 ) {
        $('#handlingFee').html(amount.toFixed($decimal));
        $('#total').html(total.toFixed($decimal));
      } else {
        $('#handlingFee, #total').html('0');
        }
    }

});

</script>
@endsection
