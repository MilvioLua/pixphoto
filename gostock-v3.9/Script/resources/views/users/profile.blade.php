<?php
	$trueProfile = true;

	$userID = $user->id;

	$downloadsCount = App\Models\Images::join('downloads', function($join) use($userID) {
				$join->on('downloads.images_id', '=', 'images.id')->where('images.user_id', '=', $userID );
			})->count();

			if( $user->cover == '' ) {
				$cover = 'background: #232a29;';
			}	else {
				$cover = "background: url('".Storage::url(config('path.cover').$user->cover)."') no-repeat center center #232a29; background-size: cover;";
			}

			$purchases = App\Models\Purchases::leftJoin('images', function($join) {
				 $join->on('purchases.images_id', '=', 'images.id');
			 })
			 ->where('images.user_id',$user->id)
			 ->select('purchases.*')
			 ->addSelect('images.id')
			 ->addSelect('images.title')
			 ->orderBy('purchases.id','DESC');

if( Auth::check() ) {

	// FOLLOW ACTIVE
 	$followActive = App\Models\Followers::where( 'follower', Auth::user()->id )
 	->where('following',$user->id)
	->where('status', '1')
 	->first();

       if( $followActive ) {
       	  $textFollow   = trans('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'btnFollowActive';
       } else {
       		$textFollow   = trans('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }

 }//<<<<---- *** END AUTH ***
?>

@extends('app')

@section('title') {{ $title }} @endsection

@section('content')
<div class="jumbotron profileUser index-header jumbotron_set jumbotron-cover-user" style="{{$cover}}">

<div class="container wrap-jumbotron position-relative">

@if( Auth::check() && Auth::user()->id == $user->id )
	<!-- *********** COVER ************* -->
      <form class="pull-left myicon-right position-relative" style="z-index: 100;" action="{{url('upload/cover')}}" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<button type="button" class="btn btn-default btn-border btn-sm" id="cover_file" style="margin-top: 10px;">
	    		<i class="icon-camera myicon-right"></i> {{ trans('misc.change_cover') }}
	    		</button>
	    		 <input type="file" name="photo" id="uploadCover" accept="image/*" style="visibility: hidden;">
			</form><!-- *********** COVER ************* -->

			@endif
			</div>
    </div>

<div class="container-fluid margin-bottom-40 margin-top-40">

	<div class="row"></div>
<!-- Col MD -->
<div class="col-md-12">

	<div class="center-block text-center profile-user-over">

		<a href="{{ url($user->username) }}">

        		<img src="{{ Storage::url(config('path.avatar').$user->avatar) }}" width="150" height="150" class="img-circle border-avatar-profile avatarUser" />
        		</a>

        <h1 class="title-item none-overflow font-default">
		        		@if( $user->name != '' )

		        		{{ e( $user->name ) }}

									@if ($user->author_exclusive == 'yes')
										<small>
									 <i class="fa fa-diamond" data-toggle="tooltip" data-placement="top" title="{{ trans('misc.exclusive_author') }}"></i>
									 	</small>
								 @endif

								 <small class="text-muted">{{ '@'.$user->username }}</small>

		        		@else

		        		{{ e( $user->username ) }}

								@if ($user->author_exclusive == 'yes')
									<small>
								 <i class="fa fa-diamond" data-toggle="tooltip" data-placement="top" title="{{ trans('misc.exclusive_author') }}"></i>
									</small>
							 @endif

		        		@endif
		        	</h1>


@if( Auth::check() && Auth::user()->id == $user->id )
	<!-- *********** AVATAR ************* -->
      <form action="{{url('upload/avatar')}}" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
    		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<button type="button" class="btn btn-default btn-border btn-sm" id="avatar_file" style="margin-top: 10px;">
	    		<i class="icon-camera myicon-right"></i> {{ trans('misc.change_avatar') }}
	    		</button>
	    		 <input type="file" name="photo" id="uploadAvatar" accept="image/*" style="visibility: hidden;">
			</form><!-- *********** AVATAR ************* -->
			@endif

			@if( Auth::check() && $user->id != Auth::user()->id )
						<button type="button" class="btn btn-xs add-button btn-follow myicon-right btnFollow {{ $activeFollow }}" data-toggle="tooltip" data-placement="top" data-id="{{ $user->id }}" data-follow="{{ trans('users.follow') }}" data-following="{{ trans('users.following') }}">
	    			 			<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
	    			 		</button>
	    			 	@endif

	    			@if( Auth::check() && $user->id != Auth::user()->id && $user->paypal_account != '' || Auth::guest()  && $user->paypal_account != '' )
	    			 	<button type="button" class="btn btn-sm btn-default" id="btnFormPP" title="{{trans('misc.buy_coffee')}}">
	    			 			<i class="fa fa-paypal myicon-right" style="color: #003087"></i> {{trans('misc.coffee')}}
	    			 		</button>
	    			 		@endif

	    			 	@if( Auth::check() && $user->id != Auth::user()->id )
	    			 		<a href="#" class="btn btn-sm btn-default" data-toggle="modal" data-target="#reportUser" title="{{trans('misc.report')}}">
	    			 			<i class="fa fa-flag"></i>
	    			 		</a>
	    			 		@endif

		<h4 class="text-bold line-sm position-relative font-family-primary font-default text-muted">{{ trans('users.about_me') }}</h4>

		   	 <small class="center-block subtitle-user">
      		 	{{ trans('misc.member_since') }} {{ App\Helper::formatDate($user->date) }}
      		 	</small>

      		 @if( $user->countries_id != '' )
      		 	<small class="center-block subtitle-user">
      		 		<i class="fa fa-map-marker myicon-right"></i> {{ $user->country()->country_name }}
      		 	</small>
      		 	@endif

           @if( $user->bio != '' )
           <h4 class="text-center bio-user none-overflow">{{ e($user->bio) }}</h4>
           @endif

           @if( $user->website != ''
			            	 || $user->twitter != ''
			            	 || $user->facebook != ''
			             )

			             @if( $user->website != '' )
    							<a target="_blank" href="{{ e( $user->website ) }}" title="{{ trans('misc.website_misc') }}" class="urls-bio icons-bio" data-toggle="tooltip" data-placement="top">
    								<i class="icon-link myicon-right"></i>
    							</a>
    							@endif

    						@if( $user->twitter != '' )
    							<a target="_blank" href="{{ e($user->twitter) }}" title="Twitter"  class="urls-bio icons-bio" data-toggle="tooltip" data-placement="top">
    								<i class="icon-twitter myicon-right"></i>
    								</a>
    							@endif

    							@if( $user->facebook != '' )
    							<a target="_blank" href="{{ e($user->facebook) }}" title="Facebook"  class="urls-bio icons-bio" data-toggle="tooltip" data-placement="top">
    								<i class="fa fa-facebook-square myicon-right"></i>
    								</a>
    							@endif

									@if( $user->instagram != '' )
    							<a target="_blank" href="{{ e($user->instagram) }}" title="Instagram"  class="urls-bio icons-bio" data-toggle="tooltip" data-placement="top">
    								<i class="fa fa-instagram myicon-right"></i>
    								</a>
    							@endif

			             @endif

	   <ul class="nav nav-pills inlineCounterProfile justify-list-center">

        	  <li>
        	  	<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($user->images()->count()) }}</small>
        	  	<span class="text-muted">{{trans('misc.images')}}</span>
        	  </li><!-- End Li -->

        	 @if( Auth::check() && Auth::user()->id == $user->id )
        	  <li>
        	  	<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($user->images_pending()->count()) }}</small>
        	  	<a href="{{url( 'photos/pending')}}" class="text-muted link-nav-user">{{trans('misc.photos_pending')}}</a>
        	  </li><!-- End Li -->
        	  @endif

        	  <li>
        	  	<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($downloadsCount) }}</small>
        	  	<span class="text-muted">{{trans('misc.downloads')}}</span>
        	  </li><!-- End Li -->

						@if($settings->sell_option == 'on')
						<li>
        	  	<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($purchases->count()) }}</small>
        	  	<span class="text-muted">{{trans('misc.sales')}}</span>
        	  </li><!-- End Li -->
					@endif

    			<li>
    				<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($user->followers()->count()) }}</small>
    				<a href="{{url($user->username, 'followers')}}" class="text-muted link-nav-user">{{trans('users.followers')}}</a>
    			</li><!-- End Li -->

    			<li>
    				<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($user->following()->count()) }}</small>
    				<a href="{{url( $user->username, 'following')}}" class="text-muted link-nav-user">{{trans('users.following')}}</a>
    			</li><!-- End Li -->

    			<li>
    				<small class="btn-block sm-btn-size text-left counter-sm">{{ App\Helper::formatNumber($user->collections()->count()) }}</small>
    				<a href="{{url( $user->username, 'collections')}}" class="text-muted link-nav-user">{{trans('misc.collections')}}</a>
    			</li><!-- End Li -->
    	</ul>

	</div><!-- Center Div -->

<hr />

	@if( $images->total() != 0 )

<div id="imagesFlex" class="flex-images btn-block margin-bottom-40 dataResult">
	     @include('includes.images')

	     @if( $images->count() != 0  )
			    <div class="container-paginator">
			    	{{ $images->links() }}
			    	</div>
			    	@endif

	     </div><!-- Image Flex -->

	  @else
	  <div class="btn-block text-center">
	    			<i class="icon icon-Picture ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('users.user_no_images') }}
	    	</h3>
	  @endif

 </div><!-- /COL MD -->
 </div><!-- row -->

@if( Auth::check() && $user->id != Auth::user()->id && $user->paypal_account != '' || Auth::guest()  && $user->paypal_account != '' )
 <form id="form_pp" name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post"  style="display:none">
    <input type="hidden" name="cmd" value="_donations">
    <input type="hidden" name="return" value="{{url($user->username)}}">
    <input type="hidden" name="cancel_return"   value="{{url($user->username)}}">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="item_name" value="{{trans('misc.support').' @'.$user->username}} - {{$settings->title}}" >
    <input type="hidden" name="business" value="{{$user->paypal_account}}">
    <input type="submit">
</form>
@endif

@if( Auth::check() )
<div class="modal fade" id="reportUser" tabindex="-1" role="dialog" aria-hidden="true">
     		<div class="modal-dialog modal-sm">
     			<div class="modal-content">
     				<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <h4 class="modal-title text-center" id="myModalLabel">
				        	<strong>{{ trans('misc.report') }}</strong>
				        	</h4>
				     </div><!-- Modal header -->

				      <div class="modal-body listWrap">

				    <!-- form start -->
			    <form method="POST" action="{{ url('report/user') }}" enctype="multipart/form-data" id="formReport">
			    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
			    	<input type="hidden" name="id" value="{{ $user->id }}">
				    <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('admin.reason') }}</label>
                      	<select name="reason" class="form-control">
                      		<option value="spoofing">{{ trans('admin.spoofing') }}</option>
                            <option value="copyright">{{ trans('admin.copyright') }}</option>
                            <option value="privacy_issue">{{ trans('admin.privacy_issue') }}</option>
                            <option value="violent_sexual_content">{{ trans('admin.violent_sexual_content') }}</option>
                          </select>

                  </div><!-- /.form-group-->

                   <button type="submit" class="btn btn-sm btn-danger reportUser">{{ trans('misc.report') }}</button>

                    </form>

				      </div><!-- Modal body -->
     				</div><!-- Modal content -->
     			</div><!-- Modal dialog -->
     		</div><!-- Modal -->
     		@endif

 <!-- container wrap-ui -->
@endsection

@section('javascript')

<script type="text/javascript">

$('#imagesFlex').flexImages({ rowHeight: 320 });

$('#btnFormPP').click(function(e){
	$('#form_pp').submit();
});

@if( Auth::check() )

$(".reportUser").click(function(e) {
  	var element     = $(this);
	e.preventDefault();
  	 element.attr({'disabled' : 'true'});

  	 $('#formReport').submit();

  });

	@if (session('noty_error'))
	    		swal({
	    			title: "{{ trans('misc.error_oops') }}",
	    			text: "{{ trans('misc.already_sent_report') }}",
	    			type: "error",
	    			confirmButtonText: "{{ trans('users.ok') }}"
	    			});
	   		 @endif

	   		 @if (session('noty_success'))
	    		swal({
	    			title: "{{ trans('misc.thanks') }}",
	    			text: "{{ trans('misc.send_success') }}",
	    			type: "success",
	    			confirmButtonText: "{{ trans('users.ok') }}"
	    			});
	   		 @endif
  @endif


//<<---- PAGINATION AJAX
        $(document).on('click','.pagination a', function(e){
			e.preventDefault();
			var page = $(this).attr('href').split('page=')[1];
			$.ajax({
				headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
					url: '{{ url()->current() }}?page=' + page


			}).done(function(data){
				if( data ) {

					scrollElement('#imagesFlex');

					$('.dataResult').html(data);

					$('.hovercard').hover(
		               function () {
		                  $(this).find('.hover-content').fadeIn();
		               },
		               function () {
		                  $(this).find('.hover-content').fadeOut();
		               }
		            );

					$('#imagesFlex').flexImages({ rowHeight: 320 });
					jQuery(".timeAgo").timeago();

					$('[data-toggle="tooltip"]').tooltip();
				} else {
					sweetAlert("{{trans('misc.error_oops')}}", "{{trans('misc.error')}}", "error");
				}
				//<**** - Tooltip
			});

		});//<<---- PAGINATION AJAX

@if( Auth::check() && Auth::user()->id == $user->id )

	//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
    $(document).on('change', '#uploadAvatar', function(){

    $('.wrap-loader').show();

   (function(){
	 $("#formAvatar").ajaxForm({
	 dataType : 'json',
	 error: function error(responseText, statusText, xhr, $form)  {
	 	$('.wrap-loader').hide();
	 	$('#uploadAvatar').val('');
	 	$('.popout').addClass('popout-error').html('{{trans('misc.error')}} ('+xhr+')').fadeIn('500').delay('5000').fadeOut('500');
	     /*alert('status: ' + statusText + '\n\rresponseText: \n' + responseText + '\n\nxhr: \n' + xhr);*/
	 },
	 success:  function(e){
	 if( e ){
        if( e.success == false ){
		$('.wrap-loader').hide();

		var error = '';
                        for($key in e.errors){
                        	error += '' + e.errors[$key] + '';
                        }
		swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: ""+ error +"",
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

			$('#uploadAvatar').val('');

		} else {

			$('#uploadAvatar').val('');
			$('.avatarUser').attr('src',e.avatar);
			$('.wrap-loader').hide();
		}

		}//<-- e
			else {
				$('.wrap-loader').hide();
				swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: '{{trans("misc.error")}}',
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

				$('#uploadAvatar').val('');
			}
		   }//<----- SUCCESS
		}).submit();
    })(); //<--- FUNCTION %
});//<<<<<<<--- * ON * --->>>>>>>>>>>
//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//

//<<<<<<<=================== * UPLOAD COVER  * ===============>>>>>>>//
$(document).on('change', '#uploadCover', function(){

    $('.wrap-loader').show();

   (function(){
	 $("#formCover").ajaxForm({
	 dataType : 'json',
	 error: function error(responseText, statusText, xhr, $form) {
	 	$('.wrap-loader').hide();
	 	$('#uploadCover').val('');
	 	$('.popout').addClass('popout-error').html('{{trans('misc.error')}} ('+xhr+')').fadeIn('500').delay('5000').fadeOut('500');
	     /*alert('status: ' + statusText + '\n\rresponseText: \n' + responseText + '\n\nxhr: \n' + xhr);*/
	 },
	 success:  function(e){
	 if( e ){
        if( e.success == false ){
		$('.wrap-loader').hide();

		var error = '';
                        for($key in e.errors){
                        	error += '' + e.errors[$key] + '';
                        }
		swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: ""+ error +"",
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

			$('#uploadCover').val('');

		} else {

			$('#uploadCover').val('');

			$('.jumbotron-cover-user').css({ background: 'url("'+e.cover+'") center center #232a29','background-size': 'cover' });;
			$('.wrap-loader').hide();
		}

		}//<-- e
			else {
				$('.wrap-loader').hide();
				swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: '{{trans("misc.error")}}',
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});

				$('#uploadCover').val('');
			}
		   }//<----- SUCCESS
		}).submit();
    })(); //<--- FUNCTION %
});//<<<<<<<--- * ON * --->>>>>>>>>>>
//<<<<<<<=================== * UPLOAD COVER  * ===============>>>>>>>//



@endif
</script>

@endsection
