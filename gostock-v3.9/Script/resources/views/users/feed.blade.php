@extends('app')

@section('title'){{ $title }}@endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('misc.feed') }}</h1>
        <p class="subtitle-site"><strong>{{ trans('misc.following_feed') }}</strong></p>
      </div>
    </div>

<div class="container-fluid margin-bottom-40">

<!-- Col MD -->
<div class="col-md-12 margin-top-20 margin-bottom-20">

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

 </div><!-- /COL MD -->

 </div><!-- container wrap-ui -->

@endsection

@section('javascript')

<script type="text/javascript">

 $('#imagesFlex').flexImages({ rowHeight: 320 });

</script>
@endsection
