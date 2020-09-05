@extends('app')

@section('title') {{ trans('misc.contact') }} - @endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('misc.contact') }}</h1>
        <p class="subtitle-site"><strong>@lang('misc.subtitle_contact')</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

			<!-- Col MD -->
		<div class="col-md-12">

	<div class="wrap-center center-block">
			@if (Session::has('notification'))
			<div class="alert alert-success btn-sm alert-fonts" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif

			@include('errors.errors-forms')

		<!-- ***** FORM ***** -->
       <form action="{{ url('contact') }}" method="post" name="form">

          	<input type="hidden" name="_token" value="{{ csrf_token() }}">
            @captcha
              <div class="row">
                	<div class="col-md-6">
			<!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('users.name') }}</label>
              <input type="text" required class="form-control login-field custom-rounded" value="{{Auth::user()->username ??  old('name')}}" name="full_name" placeholder="{{ trans('users.name') }}" title="{{ trans('users.name') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->
          </div><!-- End Col MD-->

          <div class="col-md-6">
         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('auth.email') }}</label>
              <input type="email" required class="form-control login-field custom-rounded" value="{{Auth::user()->email ??  old('email')}}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->
          </div><!-- End Col MD-->
        </div><!-- End row -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">@lang('misc.subject')</label>
              <input type="text" required class="form-control login-field custom-rounded" value="{{ old('subject') }}" name="subject" placeholder="@lang('misc.subject')" title="@lang('misc.subject')" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('misc.message') }}</label>
            	<textarea name="message" required rows="4" class="form-control login-field custom-rounded"></textarea>
         </div><!-- ***** Form Group ***** -->


           <button type="submit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('auth.send') }}</button>

       </form><!-- ***** END FORM ***** -->

</div><!-- wrap center -->

		</div><!-- /COL MD -->


 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection
