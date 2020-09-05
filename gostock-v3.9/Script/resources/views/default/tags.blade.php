@extends('app')

@section('title'){{ trans('misc.tags').' - ' }}@endsection

@section('content')
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('misc.tags') }}</h1>
        <p class="subtitle-site"><strong>{{$settings->title}}</strong></p>
      </div>
    </div>

<div class="container margin-bottom-40">

<!-- Col MD -->
<div class="col-md-12 margin-top-20 margin-bottom-20">

     <?php

	    $_tags = $data[0]->tags;

	    $tags = array_unique( explode(',', $_tags) );

		sort($tags);

		?>

		@foreach ($tags as $query)

		<?php $countTags = App\Models\Images::where( 'tags','LIKE', '%'.$query.'%' )->count(); ?>

			@if( $countTags != 0 )
			<a href="{{ url('tags', str_replace(' ', '_', $query) ) }}" class="btn btn-danger tags font-default btn-sm">
					{{$query}} ({{$countTags}})
				</a>
				@endif
    @endforeach

    @if( $data[0]->count() == 0  )
    	<div class="btn-block text-center">
	    			<i class="icon icon-Tag ico-no-result"></i>
	    		</div>

	    		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{ trans('misc.no_results_found') }}
	    	</h3>
	    	@endif

 </div><!-- /COL MD -->

 </div><!-- container wrap-ui -->

@endsection
