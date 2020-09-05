@extends('app')

@section('content')
<div class="jumbotron index-header jumbotron_set jumbotron-cover @if( Auth::check() ) session-active-cover @endif">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site vivify driveInTop delay-500" id="titleSite">{{$settings->welcome_text}}</h1>
        <p class="subtitle-site vivify fadeInBottom delay-600"><strong>{{$settings->welcome_subtitle}}</strong></p>

        <form role="search" autocomplete="off" action="{{ url('search') }}" method="get">
			  <div class="input-group input-group-lg searchBar">
                    <input type="text" class="form-control" name="q" id="btnItems" placeholder="{{trans('misc.title_search_bar')}}">
                    <span class="input-group-btn">
                      <button class="btn btn-main btn-flat" type="submit" id="btnSearch">
                      	<i class="glyphicon glyphicon-search"></i>
                      </button>
                    </span>
                  </div>
                  </form>

                  @if($categoryPopular)
                  <div style="text-align:center;color:#FFF;margin-top: 10px;font-size: 16px;">
                    <strong>{{trans('misc.popular_categories')}} {!! $categoryPopular !!}</strong>
                  </div>
                  @endif

      </div><!-- container wrap-jumbotron -->
</div><!-- jumbotron -->


	<div class="container-fluid margin-bottom-40">

		<div class="row margin-bottom-20">

		@if( $images->total() != 0 )

      @if($settings->show_images_index == 'featured' || $settings->show_images_index == 'both')
      <div class="col-md-12 btn-block margin-bottom-40">
				<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow highlight-word-color">@lang('misc.featured_photos')</h1>
				<h5 class="btn-block text-center class-montserrat text-uppercase">{{trans('misc.subtitle_featured_home')}}</h5>
			</div>

      <div id="imagesFlexFeatured" class="flex-images btn-block margin-bottom-40">
    	     @include('includes.images', ['images' => $featured])
    	  </div><!-- Image Flex -->

        <div class="col-md-12 text-center margin-bottom-20">
        	<a href="{{ url('featured') }}" class="btn btn-lg btn-main custom-rounded">
        		{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i>
        		</a>
        </div>
      @endif

      @if($settings->show_images_index == 'latest' || $settings->show_images_index == 'both')
			<div class="col-md-12 btn-block margin-bottom-40">
				<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow highlight-word-color">@lang('misc.recent_photos')</h1>
				<h5 class="btn-block text-center class-montserrat text-uppercase">{{trans('misc.title_2_index')}}</h5>
			</div>

	<div id="imagesFlex" class="flex-images btn-block margin-bottom-40">
	     @include('includes.images')
	  </div><!-- Image Flex -->

	  <div class="col-md-12 text-center margin-bottom-20">
      	<a href="{{ url('latest') }}" class="btn btn-lg btn-main custom-rounded">
      		{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i>
      		</a>
      </div>
      @endif

	  @else
	  <div class="btn-block text-center">
	    			<i class="icon icon-Picture ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('misc.no_images_published') }}
	    	</h3>
	  @endif

		</div><!-- row -->

		<div class="row margin-bottom-40">

		<div class="container">

      @if ($settings->show_counter == 'on')
			<div class="col-md-4 border-stats">
					<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow"><span class=".numbers-with-commas counter"><?php echo html_entity_decode( App\Helper::formatNumbersStats(  App\Models\User::where('status','active')->count() ) ) ?></span></h1>
					<h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.members')}}</h5>
				</div><!-- col-md-3 -->

			<div class="col-md-4 border-stats">
					<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow"><span class=".numbers-with-commas counter"><?php echo html_entity_decode( App\Helper::formatNumbersStats( App\Models\Downloads::count() ) ) ?></span></h1>
					<h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.downloads')}}</h5>
				</div><!-- col-md-3 -->

				<div class="col-md-4 border-stats">
					<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow"><span class=".numbers-with-commas counter"><?php echo html_entity_decode( App\Helper::formatNumbersStats( App\Models\Images::where('status','active')->count() ) ) ?></span></h1>
					<h5 class="btn-block text-center class-montserrat subtitle-color text-uppercase">{{trans('misc.stock_photos')}}</h5>
				</div><!-- col-md-3 -->
      @endif

	@if( isset( $settings->google_adsense ) && $settings->google_ads_index == 'on' && $settings->google_adsense_index != '' )
		<div class="col-md-12 margin-top-40">
		<?php echo html_entity_decode($settings->google_adsense_index); ?>
		</div>
		@endif

		</div><!-- row -->
		</div>

	</div><!-- container wrap-ui -->

	<div class="jumbotron jumbotron-bottom margin-bottom-zero jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site">{{trans('misc.title_2_index')}}</h1>
        <p class="subtitle-site"><strong>{{$settings->welcome_subtitle}}</strong></p>


        @if( Auth::check() || $settings->registration_active == 0	 )

          <form role="search" autocomplete="off" action="{{ url('search') }}" method="get">
  			  <div class="input-group input-group-lg searchBar">
                      <input type="text" class="form-control" name="q" id="btnItems_2" placeholder="{{trans('misc.title_search_bar')}}">
                      <span class="input-group-btn">
                        <button class="btn btn-main btn-flat" type="submit" id="btnSearch_2">
                        	<i class="glyphicon glyphicon-search"></i>
                        </button>
                      </span>
                    </div>
                    </form>

                  @else
                  <div class="btn-block text-center">
                  	<a href="{{ url('register') }}" class="btn btn-lg btn-main custom-rounded">
                  		{{ trans('misc.signup_free') }}
                  		</a>
                  </div>

                  @endif
      </div><!-- container wrap-jumbotron -->
</div><!-- jumbotron -->

@if ($settings->show_categories_index == 'on')
<div class="wrapper">
<div class="container">
	<div class="row margin-bottom-40">
				<div class="col-md-12 btn-block margin-bottom-40">
					<h1 class="btn-block text-center class-montserrat margin-bottom-zero none-overflow color-white">{{trans('misc.categories')}}</h1>
					<h5 class="btn-block text-center class-montserrat text-uppercase color-gray">{{trans('misc.browse_by_category')}}</h5>
				</div>

	    	@foreach($categories->chunk(3) as $column)

	    		<div class="col-md-3 col-center">
	    			<ul class="list-unstyled imagesCategory">
	    		@foreach ($column as $category)

	        				<li>
	        					<a class="link-category" href="{{ url('category') }}/{{ $category->slug }}">{{ $category->name }} ({{$category->images()->count()}}) </a>
	        					</li>

	        			@endforeach

	        				</ul>
	        			</div>
	        	@endforeach

	        	@if( $categories->total() > 11 )
	        		<div class="col-md-12 text-center margin-top-40">
                  	<a href="{{ url('categories') }}" class="btn btn-lg btn-main custom-rounded">
                  		{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i>
                  		</a>
                  </div>
	        	@endif

			</div><!-- row -->
	</div><!-- container -->
</div><!-- wrapper -->
@endif
@endsection

@section('javascript')
	<script src="{{ asset('public/plugins/jquery.counterup/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('public/plugins/jquery.counterup/waypoints.min.js') }}"></script>

		<script type="text/javascript">
		 $('#imagesFlex').flexImages({ rowHeight: 320, maxRows: 8, truncate: true });

     $('#imagesFlexFeatured').flexImages({ rowHeight: 320, maxRows: 8, truncate: true });


		jQuery(document).ready(function( $ ) {
			$('.counter').counterUp({
			delay: 10, // the delay time in ms
			time: 1000 // the speed time in ms
			});
		});

		 @if (session('success_verify'))
    		swal({
    			title: "{{ trans('misc.welcome') }}",
    			text: "{{ trans('users.account_validated') }}",
    			type: "success",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});
   		 @endif

   		 @if (session('error_verify'))
    		swal({
    			title: "{{ trans('misc.error_oops') }}",
    			text: "{{ trans('users.code_not_valid') }}",
    			type: "error",
    			confirmButtonText: "{{ trans('users.ok') }}"
    			});
   		 @endif

       // GET country
       /*$.get("https://ipinfo.io", function (response) {
   		     console.log(response.country);

   		 }, "jsonp");*/


		</script>

@endsection
