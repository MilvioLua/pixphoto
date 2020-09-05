<?php
// ** Data User logged ** //
$user = Auth::user();
	  ?>
@extends('app')

@section('title') {{ trans('users.notifications') }} - @endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
	<div class="container wrap-jumbotron position-relative">
		<h1 class="title-site title-sm">{{ trans('users.notifications') }}</h1>

		@if( $sql->count() !=  0  )
		<p class="subtitle-site delete-notifications">
			<strong><i class="fa fa-trash myicon-right"></i> {{ trans('users.delete_all_notifications') }}</strong>
		</p>
		@endif

	</div>
</div>

<div class="container margin-bottom-40">

	<!-- Col MD -->
	<div class="col-md-12">
		<div class="wrap-center center-block">

			<dl class="margin-zero">

				<?php

 if( $sql->count() !=  0 ) {

	  foreach ( $sql as $key ) {

		$url_photo = url('photo',$key->id);

		switch( $key->type ) {
			case 1:
				$action          = trans('users.followed_you');
				$icoDefault      = '<i class="icon icon-User ico-btn-followed"></i>';
				$title           = null;
				$linkDestination = false;
				break;
			case 2:
				$action          = trans('users.like_you_photo');
				$icoDefault      = '<i class="icon-heart ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;
			case 3:
				$action          = trans('users.comment_you_photo');
				$icoDefault      = '<i class="icon-bubble"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;

			case 4:
				$action          = trans('users.liked_your_comment');
				$icoDefault      = '<i class="icon-heart ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;

			case 5:
				$action          = trans('misc.has_bought');
				$icoDefault      = '<i class="fa fa-shopping-cart"></i>';
				$title           = $key->title;
				$linkDestination = $url_photo;
				break;
		}

?>
				<!-- Start -->
				<div class="media li-group noty-media">
					<div class="pull-left">
						<a href="{{ url('/') }}/{{ $key->username }}"> <img width="40" height="40" class="img-circle myicon-righ media-objectt" alt="User" src="{{ Storage::url(config('path.avatar').$key->avatar) }}"> </a>
					</div>
					<div class="media-body">
						<div class="pull-right small">
							<span class="timestamp timeAgo" data="{{ date('c', strtotime( $key->created_at )) }}"></span>
						</div>
						<h5 class="media-heading"><a href="{{ url('/') }}/{{ $key->username }}"> <strong>@if( $key->name != '') {{ e( $key->name ) }} @else {{ e( $key->username )  }} @endif</strong> </a></h5>

						<p class="list-grid-block p-text" style="height: auto; padding: 0 2px;">
							{!! $icoDefault !!} {{ $action }}

							@if( $linkDestination != false )
							<a href="{{ $linkDestination }}"> <strong>{{ e(str_limit($title, 30,  '...')) }}</strong> </a>
							@endif

						</p>
					</div>
				</div>
				<!-- End -->

				<?php
				}//foreach

				} // != 0
 ?>

				@if( $sql->count() == 0 )

				<div class="btn-block text-center">
					<i class="icon icon-Ringer ico-no-result"></i>
				</div>

				<h3 class="margin-top-none text-center no-result color-no-result"> {{ Lang::get('misc.no_notifications') }} </h3>

				@endif

			</dl>

			@if( $sql->lastPage() > 1 )

			<hr />
			{{ $sql->links() }}

			@endif

		</div><!-- wrap center -->
	</div><!-- /COL MD -->

</div><!-- container -->

<!-- container wrap-ui -->
@endsection

@section('javascript')

<script type="text/javascript">
	//<<<---------- Delete Account
	$(".delete-notifications").click(function(e) {
		e.preventDefault();

		var element = $(this);
		var url = '{{url("notifications/delete")}}';

		swal({
			title : "{{trans('misc.delete_confirm')}}",
			text : "{{trans('misc.confirm_delete_all_notifications')}}",
			type : "warning",
			showLoaderOnConfirm : true,
			showCancelButton : true,
			confirmButtonColor : "#DD6B55",
			confirmButtonText : "{{trans('misc.yes_confirm')}}",
			cancelButtonText : "{{trans('misc.cancel_confirm')}}",
			closeOnConfirm : false,
		}, function(isConfirm) {
			if (isConfirm) {
				window.location.href = url;
			}
		});

	});
</script>
@endsection
