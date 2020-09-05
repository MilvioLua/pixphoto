<button type="button" class="btn btn-default btn-block btn-border btn-lg show-toogle btn-collapse" data-toggle="collapse" data-target=".responsive-side" style="margin-bottom: 20px;">
	   <i class="fa fa-bars myicon-right"></i> {{ trans('misc.menu') }}
	</button>

<nav class="navbar navbar-default margin-b-10 navbar-user-ui nav-filters" role="navigation">
    	<div class="container">
    		<div style="width: 100%; text-align: center;">

    	<div class="responsive-side collapse">

    		<ul class="nav nav-pills nav-user-profile tabs_index">
					@if ($settings->sell_option == 'on')
					<li @if (Request::is('photos/premium')) class="active" @endif><a href="{{url('photos/premium')}}">{{trans('misc.premium')}}</a></li>
					@endif
    			<li @if (Request::is('latest')) class="active" @endif><a href="{{url('latest')}}">{{trans('misc.latest')}}</a></li>
    			<li @if (Request::is('featured')) class="active" @endif><a href="{{url('featured')}}">{{trans('misc.featured')}}</a></li>
    			<li @if (Request::is('popular')) class="active" @endif><a href="{{url('popular')}}">{{trans('misc.popular')}}</a></li>
    			<li @if (Request::is('most/commented')) class="active" @endif><a href="{{url('most/commented')}}">{{trans('misc.most_commented')}}</a></li>
    			<li @if (Request::is('most/viewed')) class="active" @endif><a href="{{url('most/viewed')}}">{{trans('misc.most_viewed')}}</a></li>
    			<li @if (Request::is('most/downloads')) class="active" @endif><a href="{{url('most/downloads')}}">{{trans('misc.most_downloads')}}</a></li>
    		</ul>

    	</div>

    		</div>
    	</div><!-- container -->
  </nav>

	@if ( Request::is('featured')
		 || Request::is('popular')
		 || Request::is('most/commented')
		 || Request::is('most/viewed')
		 || Request::is('most/downloads')
		 )
	<nav class="navbar navbar-inverse margin-b-10 nav-filters" role="navigation" style="background-color:#FFF;">
		<div class="container text-center">
			<div style="width: 100%; text-align: center;">
		<ul class="nav nav-pills nav-user-profile tabs_index">
			<li @if (!request()->get('timeframe') || request()->get('timeframe') != 'today' && request()->get('timeframe') != 'week' && request()->get('timeframe') != 'month' && request()->get('timeframe') != 'year') class="active" @endif>
				<a href="{{url()->current()}}">{{trans('misc.all_time')}}</a>
			</li>
			<li @if (request()->get('timeframe') == 'today') class="active" @endif><a href="{{url()->current()}}?timeframe=today">{{trans('misc.today')}}</a></li>
			<li @if (request()->get('timeframe') == 'week') class="active" @endif><a href="{{url()->current()}}?timeframe=week">{{trans('misc.this_week')}}</a></li>
			<li @if (request()->get('timeframe') == 'month') class="active" @endif><a href="{{url()->current()}}?timeframe=month">{{trans('misc.this_month')}}</a></li>
			<li @if (request()->get('timeframe') == 'year') class="active" @endif><a href="{{url()->current()}}?timeframe=year">{{trans('misc.this_year')}}</a></li>
		</ul>
		</div>
		</div>
</nav>
@endif
