@extends('app')

@section('title'){{ trans('misc.members').' - ' }}@endsection

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('misc.members') }} ({{number_format($data->total())}})</h1>
        <p class="subtitle-site"><strong>{{$settings->title}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

<!-- Col MD -->
<div class="col-md-9 margin-bottom-20 dataResult" id="dataResult">

		@foreach( $data as $user )

			@include('includes.users')

		@endforeach

        @if( $data->count() == 0  )
    	<div class="btn-block text-center">
	    			<i class="icon icon-Users ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	    	@endif


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

 </div><!-- /COL MD -->

 <div class="col-md-3">

<div class="panel panel-default">
    				<span class="panel-heading btn-block grid-panel-title">
    					<strong>{{ trans('misc.sort') }}</strong>
    					</span>

    				<div class="panel-body">

    				<!-- Form -->
    				<form role="search" id="formSortMembers" action="{{ url('members') }}" method="get">

    					<dl>

    					<div class="form-group">

    						<dd>
    							<div class="radio margin-bottom-15">
    								<label class="padding-zero">
    								<input <?php if( isset($sort) && $sort == 'popular' || !isset( $sort ) ): echo 'checked="checked"'; endif ?> id="popular_sort" class="no-show " name="sort" type="radio" value="popular">
    								<span class="input-sm">{{ trans('misc.popular') }}</span>
    							</label>
    							</div>

    							<div class="radio margin-bottom-15">
    							<label class="padding-zero">
    								<input <?php if( isset($sort) && $sort == 'latest'): echo 'checked="checked"'; endif ?> class="no-show" id="latest_sort" name="sort" type="radio" value="latest">
    								<span class="input-sm">{{ trans('misc.latest') }}</span>
    							</label>
    							</div>

    							<div class="radio margin-bottom-15">
    							<label class="padding-zero">
    								<input <?php if( isset($sort) && $sort == 'photos'): echo 'checked="checked"'; endif ?> class="no-show" id="latest_sort" name="sort" type="radio" value="photos">
    								<span class="input-sm">{{ trans_choice('misc.photos_plural',0) }}</span>
    							</label>
    							</div>

    						</dd>
    					</div><!-- form-group -->


    					<div class="form-group">
    						<dt class="margin-bottom-10"><i class="icon icon-Pointer myicon-right"></i> {{ trans('misc.location') }}</dt>
    						<dd>
    						<select name="location" class="form-control">
    							 <option value="">{{ trans('misc.all_countries') }}</option>

	                            @foreach( App\Models\Countries::orderBy('country_name')->get() as $country )
							  	 <option  @if( $location == $country->id ) selected="selected" @endif  value="{{$country->id}}">{{$country->country_name}}</option>
							  	 @endforeach
                          </select>
    						</dd>
    					</div><!-- form-group -->

    					</dl><!-- Margin Zero DL -->

    					 <div class="form-group">
    					 	<button type="submit" id="searchBtn" value="search" class="btn btn-danger btn-sm btn-block">
    					 		{{ trans('misc.find') }} <span class="glyphicon glyphicon-arrow-right"></span>
    					 		</button>

    					 		<div class="btn-block text-center margin-top-15">
    					 			<a href="{{url('members')}}">{{ trans('misc.reset') }}</a>
    					 		</div>

    					 </div><!-- form-group -->


    					</form><!-- Form -->

    				</div><!-- Panel Body -->
    			</div>

    			@if( isset( $settings->google_adsense ) )
    				<?php echo html_entity_decode($settings->google_adsense); ?>
    			@endif

</div><!-- /End col md-4 -->

 </div><!-- container wrap-ui -->

@endsection

@section('javascript')
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">

		$('input').iCheck({
          radioClass: 'iradio_flat-red',
          checkboxClass: 'icheckbox_square-red',
        });


        //<<---- PAGINATION AJAX
        $(document).on('click','.pagination a', function(e){
			e.preventDefault();
			var page = $(this).attr('href').split('page=')[1];
			$.ajax({
				headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
					url: '{{ URL::to("/") }}/ajax/users?sort={{$sort}}&location{{$location}}=&page=' + page


			}).done(function(data){
				if( data ) {
					scrollElement('#dataResult');
					$('.dataResult').html(data);
					$('[data-toggle="tooltip"]').tooltip();

					//window.history.pushState("object or string", "Title", "{{ Request::url() }}?page=" + page);

				} else {
					sweetAlert("{{trans('misc.error_oops')}}", "{{trans('misc.error')}}", "error");
				}
				//<**** - Tooltip
			});

		});//<<---- PAGINATION AJAX

	</script>

@endsection
