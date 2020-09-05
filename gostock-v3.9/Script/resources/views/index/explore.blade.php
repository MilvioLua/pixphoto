@extends('app')

@php
switch(request()->get('timeframe')) {

	case 'today':
		$timeframe_text = ' '.trans('misc.today');
		break;
	case 'week':
				$timeframe_text = ' '.trans('misc.this_week');
		break;
	case 'month':
				$timeframe_text = ' '.trans('misc.this_month');
		break;
	case 'year':
				$timeframe_text = ' '.trans('misc.this_year');
		break;
	default:
				$timeframe_text = null;
	}
@endphp

@section('title'){{ $title.$timeframe_text.' - ' }}@endsection

@section('content')

@include('includes.nav-pills')

<div class="container-fluid margin-bottom-40 padding-top-40">
	<div class="row">

	<!-- col-md-8 -->
	<div class="col-md-12">

		@if( $images->total() != 0 )

	<div id="imagesFlex" class="flex-images btn-block margin-bottom-40 dataResult">
	     @include('includes.images')


	      @if( $images->count() != 0  )
			    <div class="container-paginator">
						{{ $images->appends(['timeframe' => request()->get('timeframe')])->links() }}
			    	</div>
			    	@endif

	  </div><!-- Image Flex -->



	  @else
	  <div class="btn-block text-center">
	    			<i class="icon icon-Picture ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ request()->get('timeframe') ? trans('misc.no_results_found') : trans('misc.no_images_published') }}
	    	</h3>
	  @endif

		</div><!-- col-md-12-->

	</div><!-- row -->
</div><!-- container -->
@endsection

@section('javascript')

<script type="text/javascript">

 $('#imagesFlex').flexImages({ rowHeight: 320 });

//<<---- PAGINATION AJAX
        $(document).on('click','.pagination a', function(e){
			e.preventDefault();
			var page = $(this).attr('href').split('page=')[1];
			$.ajax({
				headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		},
				@if (request()->get('timeframe') == 'today'
						|| request()->get('timeframe') == 'week'
						|| request()->get('timeframe') == 'month'
						|| request()->get('timeframe') == 'year')

							url: '{{ url()->current() }}?timeframe={{ request()->get('timeframe') }}&page=' + page

						@else

							url: '{{ url()->current() }}?page=' + page

						@endif

			}).done(function(data){
				if( data ) {

					//var _url= '{{ URL::to("/") }}/latest?page=' + page;
					//window.history.pushState("", "", _url);

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
</script>


@endsection
