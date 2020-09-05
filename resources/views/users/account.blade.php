@extends('app')

@section('title') {{ trans('users.account_settings') }} - @endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('users.account_settings') }}</h1>
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

			@include('users.navbar-edit')

		<!-- ***** FORM ***** -->
       <form action="{{ url('account') }}" method="post" name="form">

          	<input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">
        	<div class="col-md-6">
	           	<!-- ***** Form Group ***** -->
	            <div class="form-group has-feedback">
	            	<label class="font-default">{{ trans('misc.full_name_misc') }}</label>
	              <input type="text" class="form-control login-field custom-rounded" value="{{ e( Auth::user()->name ) }}" name="full_name" placeholder="{{ trans('misc.full_name_misc') }}" title="{{ trans('misc.full_name_misc') }}" autocomplete="off">
	             </div><!-- ***** Form Group ***** -->
           </div><!-- End Col MD-->


            <div class="col-md-6">
            	<!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('auth.email') }}</label>
              <input type="email" class="form-control login-field custom-rounded" value="{{Auth::user()->email}}" name="email" placeholder="{{ trans('auth.email') }}" title="{{ trans('auth.email') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->
            </div><!-- End Col MD-->

        </div><!-- End row -->

			<div class="row">

				<div class="col-md-6">
					<!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('misc.username_misc') }}</label>
              <input type="text" class="form-control login-field custom-rounded" value="{{Auth::user()->username}}" name="username" placeholder="{{ trans('misc.username_misc') }}" title="{{ trans('misc.username_misc') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->
				</div><!-- End Col MD-->

				<div class="col-md-6">
					<!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('misc.country') }}</label>
            	<select name="countries_id" class="form-control" >
                      		<option value="">{{trans('misc.select_your_country')}}</option>
                      	@foreach(  App\Models\Countries::orderBy('country_name')->get() as $country )
                            <option @if( Auth::user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
						@endforeach
                          </select>
            	    </div><!-- ***** Form Group ***** -->
				</div><!-- End Col MD-->

			</div><!-- End row -->

      <!-- ***** Form Group ***** -->
        <div class="form-group has-feedback">
          <label class="font-default">{{ trans('misc.exclusivity_items') }}</label>
          <select name="author_exclusive" class="form-control" id="authorExclusive">
                      <option @if (Auth::user()->author_exclusive == 'yes') selected="selected" @endif value="yes">{{trans('misc.exclusive_author')}}</option>
                      <option @if (Auth::user()->author_exclusive == 'no') selected="selected" @endif value="no">{{trans('misc.non_exclusive_author')}}</option>
                      </select>
                      <p class="help-block" id="percentage">
                        @if (Auth::user()->author_exclusive == 'yes')
                        * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}
                      @else
                        * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}
                        @endif
                      </p>
              </div><!-- ***** Form Group ***** -->

			<!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('admin.paypal_account') }}</label>
              <input type="email" class="form-control login-field custom-rounded" value="{{Auth::user()->paypal_account}}" name="paypal_account" placeholder="{{ trans('admin.paypal_account') }}" title="{{ trans('admin.paypal_account') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('misc.website_misc') }}</label>
              <input type="text" class="form-control login-field custom-rounded" value="{{Auth::user()->website}}" name="website" placeholder="{{ trans('misc.website_misc') }}" title="{{ trans('misc.website_misc') }}" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">Facebook</label>
              <input type="text" class="form-control login-field custom-rounded" value="{{Auth::user()->facebook}}" name="facebook" placeholder="https://www.facebook.com/username" title="https://www.facebook.com/Username" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">Twitter</label>
              <input type="text" class="form-control login-field custom-rounded" value="{{Auth::user()->twitter}}" name="twitter" placeholder="https://www.twitter.com/username" title="https://www.twitter.com/Username" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">Instagram</label>
              <input type="text" class="form-control login-field custom-rounded" value="{{Auth::user()->instagram}}" name="instagram" placeholder="https://instagram.com/username" title="https://instagram.com/username" autocomplete="off">
         </div><!-- ***** Form Group ***** -->

         <!-- ***** Form Group ***** -->
            <div class="form-group has-feedback">
            	<label class="font-default">{{ trans('misc.description') }}</label>
            	<textarea name="description" rows="4" id="bio" class="form-control login-field custom-rounded">{{ e( Auth::user()->bio ) }}</textarea>
         </div><!-- ***** Form Group ***** -->


           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-main custom-rounded">{{ trans('misc.save_changes') }}</button>

         @if( Auth::user()->id != 1 )
           <div class="btn-block text-center margin-top-20">
           		<a href="{{url('account/delete')}}" class="text-danger">{{trans('users.delete_account')}}</a>
           </div>
           @endif

       </form><!-- ***** END FORM ***** -->

</div><!-- wrap center -->

		</div><!-- /COL MD -->


 </div><!-- container -->

 <!-- container wrap-ui -->
@endsection

@section('javascript')
<script type="text/javascript">

$('#authorExclusive').on('change', function() {
  if($(this).val() == 'yes') {
    $('#percentage').html('* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}');

  } else {
      $('#percentage').html('* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}');
  }
});

</script>
@endsection
