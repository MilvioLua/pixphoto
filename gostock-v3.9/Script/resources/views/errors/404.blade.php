<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
     <link rel="shortcut icon" href="{{ url('public/img', $settings->favicon) }}" />

    <title>{{ trans('error.error_404_description') }}</title>

    @include('includes.css_general')

  </head>

  <body id="bg-error">
    <!-- container -->
    <div class="container">
    <!-- row -->
    <div class="row col-pb">

      <div class="col-md-12 text-center">
      	<h1 class="title-error"><span><i class="fa fa-chain-broken"></i></span></h1>
      	<h1 class="title-error">{{ trans('error.error_404') }}</h1>
      	<p class="subtitle-error">{{ trans('error.error_404_description') }}</p>
      	<a href="{{ url('/') }}">{{ trans('error.go_home') }}</a>
     </div><!--/col-md-* -->


  </div><!--************ Row ********************-->


    </div><!--******************** Container ******************-->


    @include('includes.javascript_general')

  </body>
  </html>
