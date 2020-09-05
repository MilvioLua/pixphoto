@extends('app')

@section('title'){{ trans('auth.sign_up').' - ' }}@endsection

  @section('css')
  <link href="{{ asset('public/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
  @endsection

@section('content')

<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{{ trans('auth.sign_up') }}}</h1>
        <p class="subtitle-site"><strong>{{{$settings->title}}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

	<div class="row">
<!-- Col MD -->
<div class="col-md-6 line-login">

	<h2 class="text-center line position-relative">{{{ trans('auth.sign_up') }}}</h2>

	<div class="login-form">

		@if (session('notification'))
						<div class="alert alert-success text-center">

							<div class="btn-block text-center margin-bottom-10">
								<i class="glyphicon glyphicon-ok ico_success_cicle"></i>
								</div>

							{{{ session('notification') }}}
						</div>
					@endif

		@include('errors.errors-forms')

          	<form action="{{{ url('register') }}}" method="post" name="form" id="signup_form">

            <input type="hidden" name="_token" value="{{{ csrf_token() }}}">

            @if($settings->captcha == 'on')
              @captcha
            @endif

             <!-- FORM GROUP -->
            <div class="form-group has-feedback">
              <input type="text" class="form-control login-field custom-rounded" value="{{{ old('username') }}}" name="username" placeholder="{{{ trans('auth.username') }}}" title="{{{ trans('auth.username') }}}" autocomplete="off">
              <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div><!-- ./FORM GROUP -->

             <!-- FORM GROUP -->
            <div class="form-group has-feedback">
              <input type="text" class="form-control login-field custom-rounded" value="{{{ old('email') }}}" name="email" placeholder="{{{ trans('auth.email') }}}" title="{{{ trans('auth.email') }}}" autocomplete="off">
              <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div><!-- ./FORM GROUP -->

         <!-- FORM GROUP -->
         <div class="form-group has-feedback">
              <input type="password" class="form-control login-field custom-rounded" name="password" placeholder="{{{ trans('auth.password') }}}" title="{{{ trans('auth.password') }}}" autocomplete="off">
              <span class="glyphicon glyphicon-lock form-control-feedback"></span>
         </div><!-- ./FORM GROUP -->

         <div class="form-group has-feedback">
			<input type="password" class="form-control" name="password_confirmation" placeholder="{{{ trans('auth.confirm_password') }}}" title="{{{ trans('auth.confirm_password') }}}" autocomplete="off">
			<span class="glyphicon glyphicon-log-in form-control-feedback"></span>
		</div>

      <div class="row margin-bottom-15">
        	<div class="col-xs-11">
        		<div class="checkbox icheck margin-zero">
   				<label class="margin-zero">
   					<input @if( old('agree_gdpr') ) checked="checked" @endif class="no-show" name="agree_gdpr" type="checkbox" value="1">
   					<span class="keep-login-title">{{ trans('admin.i_agree_gdpr') }}</span>
            @if($settings->link_privacy != '')
              <a href="{{$settings->link_privacy}}" target="_blank">{{ trans('admin.privacy_policy') }}</a>
            @endif
   			</label>
   		</div>
        	</div>
        </div><!-- row -->

           <button type="submit" id="buttonSubmitRegister" class="btn btn-block btn-lg btn-main custom-rounded">{{{ trans('auth.sign_up') }}}</button>

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

 <!-- Col MD -->
<div class="col-md-6">

	<div class="btn-block text-center">
	    	<i class="icon-users ico-no-result"></i>
	    </div>

	<h2 class="text-center">{{ Lang::get('auth.already_have_an_account') }}</h2>

	<div class="btn-block text-center">
		<a href="{{{ url('login') }}}" class="btn btn-lg btn-success custom-rounded">{{{ trans('auth.login') }}}</a>
	</div>

</div><!-- /COL MD -->

</div><!-- ROW -->

 </div><!-- row -->

 <!-- container wrap-ui -->

@endsection

@section('javascript')

  <script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}"></script>

	<script type="text/javascript">

    @if (count($errors) > 0)
    	scrollElement('#dangerAlert');
    @endif

    @if (session('notification'))
    	$('#signup_form, #dangerAlert').remove();
    @endif

    $(document).ready(function(){
  	  $('input').iCheck({
  	  	checkboxClass: 'icheckbox_square-red',
  	  });
  	});

</script>


@endsection
