@extends('app')

@section('title'){{ trans('auth.login').' - ' }}@endsection

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('auth.login') }}</h1>
        <p class="subtitle-site"><strong>{{$settings->title}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

	<div class="row">
<!-- Col MD -->
<div class="@if( $settings->registration_active == 1 ) col-md-6 line-login @else col-md-12 @endif">

	<h2 class="text-center line position-relative">{{ trans('auth.sign_in') }}</h2>

	<div class="login-form">

		@include('errors.errors-forms')

					@if (session('login_required'))
			<div class="alert alert-danger" id="dangerAlert">
            		<i class="glyphicon glyphicon-alert myicon-right"></i> {{ session('login_required') }}
            		</div>
            	@endif

          	<form action="{{ url('login') }}" method="post" name="form" id="signup_form">

          		<input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="_url" value="{{ url()->previous() }}">

              @if($settings->captcha == 'on')
                @captcha
              @endif

            <div class="form-group has-feedback">

              <input type="text" class="form-control login-field custom-rounded" value="{{ old('email') }}" name="email" id="email" placeholder="{{ trans('auth.username_or_email') }}" title="{{ trans('auth.username_or_email') }}" autocomplete="off">
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
             </div>


            <div class="form-group has-feedback">
              <input type="password" class="form-control login-field custom-rounded" name="password" id="password" placeholder="{{ trans('auth.password') }}" title="{{ trans('auth.password') }}" autocomplete="off">
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
         </div>

         <div class="row margin-bottom-15">
     	<div class="col-xs-7">
     		<div class="checkbox icheck margin-zero">
				<label class="margin-zero">
					<input @if( old('remember') ) checked="checked" @endif id="keep_login" class="no-show" name="remember" type="checkbox" value="1">
					<span class="keep-login-title">{{ trans('auth.remember_me') }}</span>
			</label>
		</div>
     	</div>

     	<div class="col-xs-5">
     		<label class="btn-block">
		   <a href="{{url('password/reset')}}" class="label-terms recover">{{ trans('auth.forgot_password') }}</a>
		</label>
     	</div>
     </div><!-- row -->

           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.sign_in') }}</button>

			@if( $settings->facebook_login == 'on' || $settings->twitter_login == 'on' )
			<span class="login-link auth-social" id="twitter-btn-text">{{ trans('auth.or_sign_in_with') }}</span>
    @endif

      @if( $settings->facebook_login == 'on' )
					<div class="facebook-login auth-social" id="twitter-btn">
						<a href="{{url('oauth/facebook')}}" class="btn btn-block btn-lg facebook custom-rounded"><i class="fa fa-facebook"></i> Facebook</a>
					</div>
					@endif

          @if( $settings->twitter_login == 'on')
    					<div class="facebook-login auth-social" id="twitter-btn">
    						<a href="{{url('oauth/twitter')}}" class="btn btn-block btn-lg twitter custom-rounded"><i class="fa fa-twitter"></i> Twitter</a>
    					</div>
    					@endif
          </form>

     </div><!-- Login Form -->

 </div><!-- /COL MD -->

@if( $settings->registration_active == 1 )
 <!-- Col MD -->
<div class="col-md-6">


	<div class="btn-block text-center">
	    	<i class="icon-users ico-no-result"></i>
	    </div>

	<h2 class="text-center">{{ Lang::get('auth.not_have_account') }}</h2>

	<div class="btn-block text-center">
		<a href="{{ url('register') }}" class="btn btn-lg btn-success custom-rounded">{{ trans('auth.sign_up') }}</a>
	</div>

</div><!-- /COL MD -->
@endif

</div><!-- ROW -->

 </div><!-- row -->

 <!-- container wrap-ui -->

@endsection

@section('javascript')
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>

	<script type="text/javascript">

	$('#email').focus();

	$(document).ready(function(){
	  $('input').iCheck({
	  	checkboxClass: 'icheckbox_square-red',
    	radioClass: 'iradio_square-red'
	  });
	});

	@if (count($errors) > 0)
    	scrollElement('#dangerAlert');
    @endif
</script>
@endsection
