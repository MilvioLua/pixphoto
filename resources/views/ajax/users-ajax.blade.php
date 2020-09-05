@foreach( $data as $user )

<?php
if( Auth::check() ) {
 	$followActive = $user->followers()->where( 'follower', Auth::user()->id )->first();

       if( $followActive ) {
       	  $textFollow   = trans('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'btnFollowActive';
       } else {
       		$textFollow   = trans('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }

 }//<<<<---- Auth
 ?>
 <article class="container-user">

			<div class="media">
			  <div class="media-left">
			    <a href="{{url($user->username)}}">
			      <img src="{{ url('public/avatar/',$user->avatar) }}" class="img-circle avatar-user">
			    </a>
			  </div>
			  <div class="media-body text-overflow">
			    <h4 class="media-heading data-user"><a href="{{url($user->username)}}">@if( $user->name != '' ) {{ e($user->name) }} @else {{ $user->username }} @endif</a></h4>
			    <ul class="padding-zero list-data-user">


						<li><i class="fa fa-clock-o myicon-right" aria-hidden="true"></i> {{trans('misc.member_since')}} {{ App\Helper::formatDate($user->date) }}</li>

						@if( $user->countries_id != '' )
						<li><i class="fa fa-map-marker myicon-right" aria-hidden="true"></i> {{ $user->country()->country_name }}</li>
						@endif

						@if( $user->website != '' )
						<li><i class="fa fa-link myicon-right"></i> <a href="{{ $user->website }}" target="_blank">{{ $user->website }}</a> </li>
						@endif

					@if( Auth::check() && $user->id != Auth::user()->id )
						<button type="button" class="btn btn-xs add-button btn-follow myicon-right btnFollow {{ $activeFollow }}" data-toggle="tooltip" data-placement="top" data-id="{{ $user->id }}" data-follow="{{ trans('users.follow') }}" data-following="{{ trans('users.following') }}">
	    			 			<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
	    			 		</button>
	    			 	@endif

					</ul>
			  </div>

			  <div class="media-right m-right text-center data-stats">
			  	<div class="btn-block color-default count-data">{{ App\Helper::formatNumber( $user->followers()->count() ) }}</div>
				<div class="btn-block color-link data-refer">{{trans('users.followers')}}</div>
			  </div>

			  <div class="media-right m-right text-center data-stats">
			  		<div class="btn-block color-default count-data">{{ App\Helper::formatNumber( $user->images()->count() ) }}</div>
					<div class="btn-block color-link data-refer">{{trans_choice('misc.photos_plural', $user->images()->count() )}}</div>
				</div>

			</div>

		</article>
		@endforeach

@if( $data->count() != 0  )
	    <div class="margin-top-30">
	    	{{ $data->appends(
	    		[
	    		'sort' => $sort,
    			'location' => $location,
	    		]
	    	)->links() }}
	    	</div>
	    	@endif
