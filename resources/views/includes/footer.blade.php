<!-- ***** Footer ***** -->
    <footer class="footer-main">
    	<div class="container">

    		<div class="row">
    			<div class="col-md-4">
    				<a href="{{ url('/') }}">
    					<img src="{{ url('public/img', $settings->logo) }}" />
    				</a>
    			   <p class="margin-tp-xs">{{ $settings->description }}</p>

    			   <ul class="list-inline">

					  @if( $settings->twitter != '' )
					   <li><a href="{{$settings->twitter}}" target="_blank" class="ico-social"><i class="fa fa-twitter"></i></a></li>
					   @endif

					 @if( $settings->facebook != '' )
					   <li><a href="{{$settings->facebook}}" target="_blank" class="ico-social"><i class="fa fa-facebook"></i></a></li>
					 @endif

					 @if( $settings->instagram != '' )
					   <li><a href="{{$settings->instagram}}" target="_blank" class="ico-social"><i class="fa fa-instagram"></i></a></li>
					 @endif

					 @if( $settings->linkedin != '' )
					   <li><a href="{{$settings->linkedin}}" target="_blank" class="ico-social"><i class="fa fa-linkedin"></i></a></li>
					   @endif

           @if( $settings->youtube != '' )
					   <li><a href="{{$settings->youtube}}" target="_blank" class="ico-social"><i class="fa fa-youtube-play"></i></a></li>
					   @endif

           @if( $settings->pinterest != '' )
					   <li><a href="{{$settings->pinterest}}" target="_blank" class="ico-social"><i class="fa fa-pinterest"></i></a></li>
					   @endif
					 </ul >

    			</div><!-- ./End col-md-* -->



    			<div class="col-md-3 margin-tp-xs">
    				<h4 class="margin-top-zero font-default">{{trans('misc.about')}}</h4>
    				<ul class="list-unstyled">
    					@foreach( App\Models\Pages::all() as $page )
        			<li><a class="link-footer" href="{{url('page', $page->slug) }}">{{ $page->title }}</a></li>
        	@endforeach
          <li><a class="link-footer" href="{{url('contact')}}">{{ trans('misc.contact') }}</a></li>
    				</ul>
    			</div><!-- ./End col-md-* -->


    			<div class="col-md-3 margin-tp-xs">
    				<h4 class="margin-top-zero font-default">{{trans('misc.categories')}}</h4>
    				<ul class="list-unstyled">
    		@foreach(  App\Models\Categories::where('mode','on')->orderBy('name')->take(6)->get() as $category )
        			<li><a class="link-footer" href="{{ url('category') }}/{{ $category->slug }}">{{ $category->name }}</a></li>
        	@endforeach

        	@if( App\Models\Categories::count() > 6 )
        		<li><a class="link-footer" href="{{ url('categories') }}">
        			<strong>{{ trans('misc.view_all') }} <i class="fa fa-long-arrow-right"></i></strong>
        		</a></li>
        		@endif

    				</ul>
    			</div><!-- ./End col-md-* -->

    			<div class="col-md-2 margin-tp-xs">
    				<h4 class="margin-top-zero font-default">{{trans('misc.links')}}</h4>
    				<ul class="list-unstyled">

        			@if( Auth::guest() )
        			<li>
        				<a class="link-footer" href="{{ url('login') }}">
        					{{ trans('auth.login') }}
        				</a>
        				</li>

        			@if( $settings->registration_active == 1 )
        				<li>
        					<a class="link-footer" href="{{ url('register') }}">
        					{{ trans('auth.sign_up') }}
        				</a>
        				</li>
        				@endif

        				@else

        				@if( Auth::user()->role == 'admin' )
	          		 	<li>
	          		 		<a href="{{ url('panel/admin') }}" class="link-footer">
	          		 			{{ trans('admin.admin') }}</a>
	          		 			</li>
	          		 	@endif

        				<li>
	          		 		<a href="{{ url(Auth::user()->username) }}" class="link-footer">
	          		 			{{ trans('users.my_profile') }}
	          		 		</a>
	          		 		</li>

	          		 		<li>
	          		 			<a href="{{ url('logout') }}" class="logout link-footer">
	          		 				{{ trans('users.logout') }}
	          		 			</a>
	          		 		</li>
        				@endif

              <div class="dropup margin-top-10">
                <li class="dropdown default-dropdown">
  	        			<a href="javascript:void(0);" class="link-footer link-lang" data-toggle="dropdown">
  	        				<i class="icon icon-WorldWide myicon-right"></i>
                    @foreach(App\Models\Languages::orderBy('name')->get() as $languages)
                    @if( $languages->abbreviation == config('app.locale') ) {{ $languages->name }}  @endif
                    @endforeach
  									<i class="ion-chevron-down margin-lft5"></i>
  	        				</a>

  	        				<!-- DROPDOWN MENU -->
  	        				<ul class="dropdown-menu arrow-down nav-session margin-bottom-10" role="menu" aria-labelledby="dropdownMenu2">
  	        				@foreach(  App\Models\Languages::orderBy('name')->get() as $languages )
  	        					<li @if( $languages->abbreviation == config('app.locale') ) class="active"  @endif>
  	        						<a @if( $languages->abbreviation != config('app.locale') ) href="{{ url('lang',$languages->abbreviation) }}" @endif class="text-overflow">
  	        						{{ $languages->name }}
  	        							</a>
  	        					</li>
  	        					@endforeach
  	        				</ul><!-- DROPDOWN MENU -->
  	        			</li>
                  </div>

    				</ul>
    			</div><!-- ./End col-md-* -->
    		</div><!-- ./End Row -->
    	</div><!-- ./End Container -->
    </footer><!-- ***** Footer ***** -->

<footer class="subfooter">
	<div class="container">
	<div class="row">
    			<div class="col-md-12 text-center padding-top-20">
    				<p>&copy; {{ $settings->title }} - <?php echo date('Y'); ?></p>
    			</div><!-- ./End col-md-* -->
	</div>
</div>
</footer>
