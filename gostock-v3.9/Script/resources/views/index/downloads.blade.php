@extends('app')

@section('title'){{ trans('misc.most_downloads').' - ' }}@endsection

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
			    	{{ $images->links() }}
			    	</div>
			    	@endif

	  </div><!-- Image Flex -->



	  @else
	  <div class="btn-block text-center">
	    			<i class="icon icon-Picture ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('misc.no_results_found') }}
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
					url: '{{ url("/") }}/ajax/downloads?page=' + page


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
</script>


@endsection
