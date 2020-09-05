@extends('app')

@section('title'){{ $title }}@endsection


@section('content') 
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site title-sm">{{ trans('users.followers') }} ({{number_format($data->total())}})</h1>
        <p class="subtitle-site">
        	<strong>
        		<a href="{{url($user->username)}}" class="back-profile-link">
        			<i class="fa fa-arrow-left myicon-right"></i> {{trans('misc.back_profile')}} {{$user->username}}
        		</a>
        		</strong>
        	</p>
      </div>
    </div>

<div class="container margin-bottom-40">
	
<!-- Col MD -->
<div class="col-md-12 margin-bottom-20 dataResult" id="dataResult">	
		
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
			    	{{ $data->links() }}
			    	</div>	
			    	@endif
			    	
 </div><!-- /COL MD -->
  
 </div><!-- container wrap-ui -->

@endsection