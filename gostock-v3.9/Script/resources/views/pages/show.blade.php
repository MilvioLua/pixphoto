@extends('app')

@section('title'){{ $response->title.' - ' }}@endsection

@section('content') 
<div class="jumbotron md index-header jumbotron_set jumbotron-cover">
      <div class="container wrap-jumbotron position-relative">
        <h1 class="title-site">{{ $response->title }}</h1>
      </div>
    </div>

<div class="container margin-bottom-40">
	
<!-- Col MD -->
<div class="col-md-12">	
		
	<ol class="breadcrumb bg-none">
          	<li><a href="{{ url('/') }}"><i class="glyphicon glyphicon-home myicon-right"></i></a></li>
          	<li class="active">{{ $response->title }}</li>
          </ol>
	<hr />
     	
     <dl>
     	<dd>
     		<?php echo html_entity_decode($response->content) ?>
     	</dd>
     </dl>	
 </div><!-- /COL MD -->
 
 </div><!-- container wrap-ui -->
@endsection

