<?php

	// Images
	$authUser = App\Models\Images::whereUserId(1)->get();

	// Total
	$total_images = App\Models\Images::whereUserId(Auth::User()->id)->count();
	$images       = App\Models\Images::whereUserId(Auth::User()->id)->orderBy('id','DESC')->take(5)->get();

	$_purchases = App\Models\Purchases::leftJoin('images', function($join) {
		 $join->on('purchases.images_id', '=', 'images.id');
	 })
	 ->where('images.user_id', Auth::user()->id)
	 ->select('purchases.*')
	 ->addSelect('images.id')
	 ->addSelect('images.title')
	 ->orderBy('purchases.id','DESC');

	$total_raised_funds = $_purchases->sum('purchases.earning_net_seller');

	// Statistics of the month

	// Today
	$stat_revenue_today = App\Models\Purchases::leftJoin('images', function($join) {
		 $join->on('purchases.images_id', '=', 'images.id');
	 })
	 ->where('images.user_id', Auth::user()->id)
	 ->where('purchases.date', '>=', date('Y-m-d H:i:s', strtotime('today')))
	 ->sum('purchases.earning_net_seller');

	 // Week
 	$stat_revenue_week = App\Models\Purchases::leftJoin('images', function($join) {
 		 $join->on('purchases.images_id', '=', 'images.id');
 	 })
 	 ->where('images.user_id', Auth::user()->id)
 	 //->where('purchases.date', '>=', date('Y-m-d H:i:s', strtotime('week')))
	 ->whereBetween('purchases.date', [
        Carbon\Carbon::parse()->startOfWeek(),
        Carbon\Carbon::parse()->endOfWeek(),
    ])
 	 ->sum('purchases.earning_net_seller');

	 // Month
 	$stat_revenue_month = App\Models\Purchases::leftJoin('images', function($join) {
 		 $join->on('purchases.images_id', '=', 'images.id');
 	 })
 	 ->where('images.user_id', Auth::user()->id)
 	 ->whereBetween('purchases.date', [
        Carbon\Carbon::parse()->startOfMonth(),
        Carbon\Carbon::parse()->endOfMonth(),
    ])
 	 ->sum('purchases.earning_net_seller');

?>
@extends('dashboard.layout')

@section('css')
<link href="{{ asset('public/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            {{ trans('admin.dashboard') }}
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('dashboard') }}"><i class="fa fa-dashboard"></i> {{ trans('admin.home') }}</a></li>
            <li class="active">{{ trans('admin.dashboard') }}</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $_purchases->count() }}</h3>
                  <p>{{ trans('misc.total_sales') }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-cart"></i>
                </div>
              </div>
            </div><!-- ./col -->

        <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ \App\Helper::amountFormatDecimal($total_raised_funds) }} <sup style="font-size: 12px">{{$settings->currency_code}}</sup></h3>
                  <p>{{ trans('misc.total_earnings') }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-social-usd"></i>
                </div>
              </div>
            </div><!-- ./col -->

						<div class="col-lg-3">
		              <!-- small box -->
		              <div class="small-box bg-yellow">
		                <div class="inner">
		                  <h3>{{ \App\Helper::amountFormatDecimal(Auth::user()->balance) }} <sup style="font-size: 12px">{{$settings->currency_code}}</sup></h3>
		                  <p>{{ trans('misc.balance') }} ({{trans('misc.sales')}})</p>
		                </div>
		                <div class="icon">
		                  <i class="ion ion-cash"></i>
		                </div>
		              </div>
		            </div><!-- ./col -->

            <div class="col-lg-3">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>{{ \App\Helper::formatNumber( $total_images ) }}</h3>
                  <p>{{ trans_choice('misc.photos_plural', $total_images) }}</p>
                </div>
                <div class="icon">
                  <i class="ion ion-images"></i>
                </div>
              </div>
            </div><!-- ./col -->
          </div>

        <div class="row">

					<section class="col-md-12">
						<div class="box">
							<div class="box-header with-border text-center">
              <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> {{trans('misc.statistics_of_the_month')}}</h3>
            </div>
							<div class="box-footer">
	              <div class="row">
	                <div class="col-sm-4 col-xs-12">
	                  <div class="description-block border-right">
	                    <h2 class="{{$stat_revenue_today > 0 ? 'text-green' : 'text-red' }}">{{ \App\Helper::amountFormatDecimal($stat_revenue_today) }}</h2>
	                    <span class="description-text text-black">{{trans('misc.revenue_today')}}</span>
	                  </div>
	                  <!-- /.description-block -->
	                </div>
	                <!-- /.col -->
	                <div class="col-sm-4 col-xs-12">
	                  <div class="description-block border-right">
	                    <h2 class="{{$stat_revenue_week > 0 ? 'text-green' : 'text-red' }}">{{ \App\Helper::amountFormatDecimal($stat_revenue_week) }}</h2>
	                    <span class="description-text text-black">{{trans('misc.revenue_week')}}</span>
	                  </div>
	                  <!-- /.description-block -->
	                </div>
	                <!-- /.col -->
									<div class="col-sm-4 col-xs-12">
	                  <div class="description-block">
	                    <h2 class="{{$stat_revenue_month > 0 ? 'text-green' : 'text-red' }}">{{ \App\Helper::amountFormatDecimal($stat_revenue_month) }}</h2>
	                    <span class="description-text text-black">{{trans('misc.revenue_month')}}</span>
	                  </div>
	                  <!-- /.description-block -->
	                </div>
	                <!-- /.col -->
	              </div>
	              <!-- /.row -->
	            </div><!-- /.box-footer -->
						</div><!-- /.box -->
					</section>

			<section class="col-md-7">
			  <div class="nav-tabs-custom">
			    <ul class="nav nav-tabs pull-right ui-sortable-handle">
			        <li class="pull-left header"><i class="ion ion-ios-cart"></i> {{ trans('misc.sales_last_30_days') }}</li>
			    </ul>
			    <div class="tab-content">
			        <div class="tab-pane active">
			          <div class="chart" id="chart1"></div>
			        </div>
			    </div>
			</div>
		  </section>

			<section class="col-md-5">

          <div class="box box-solid bg-teal-gradient">
            <div class="box-header">
              <i class="fa fa-money"></i>

              <h3 class="box-title">{{ trans('misc.earnings_raised_last') }}</h3>
            </div>
            <div class="box-body border-radius-none">
              <div class="chart" id="line-chart"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
		  </section>

        </div><!-- ./row -->

        <div class="row">


					<div class="col-md-6">
						<div class="box box-primary">
							 <div class="box-header with-border">
								 <h3 class="box-title">{{ trans('misc.recent_sales') }}</h3>
								 <div class="box-tools pull-right">
								 </div>
							 </div><!-- /.box-header -->

							 @if( $_purchases->count() != 0 )
							 <div class="box-body">

								 <ul class="products-list product-list-in-box">

								@foreach( $_purchases->take(5)->get() as $purchase )

									 <li class="item">
										 <div class="product-img">
											 <img src="{{ Storage::url(config('path.thumbnail').$purchase->images()->thumbnail) }}" style="height: auto !important;" />
										 </div>
										 <div class="product-info">
											 <a href="{{ url('photo',$purchase->images_id) }}" target="_blank" class="product-title">{{ $purchase->images()->title }}
												 <span class="label label-success pull-right">{{App\Helper::amountFormat($purchase->price)}}</span>
												 </a>
											 <span class="product-description">
												 {{ trans('misc.buyer') }} {{ $purchase->user()->username }} / {{ date('d M, Y', strtotime($purchase->date)) }}
											 </span>
										 </div>
									 </li><!-- /.item -->
									 @endforeach
								 </ul>
							 </div><!-- /.box-body -->

							 <div class="box-footer text-center">
								 <a href="{{ url('user/dashboard/sales') }}" class="uppercase">{{ trans('misc.view_all') }}</a>
							 </div><!-- /.box-footer -->

							 @else
								<div class="box-body">
								 <h5>{{ trans('admin.no_result') }}</h5>
									</div><!-- /.box-body -->

							 @endif

						 </div>
					 </div>



           <div class="col-md-6">
             <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">@lang('misc.recent_photos')</h3>
                  <div class="box-tools pull-right">
                  </div>
                </div><!-- /.box-header -->

                @if( $total_images != 0 )
                <div class="box-body">

                  <ul class="products-list product-list-in-box">

                 @foreach( $images as $image )
                 <?php
                switch (  $image->status ) {
							  case 'active':
								  $color_status = 'success';
								  $txt_status = trans('misc.active');
								  break;

							case 'pending':
								  $color_status = 'warning';
								  $txt_status = trans('admin.pending');
								  break;
						  }

							if($image->finalized == 1) {
								$color_status = 'default';
								$txt_status = trans('misc.finalized');
							}
                       ?>
                    <li class="item">
                      <div class="product-img">
                        <img src="{{ Storage::url(config('path.thumbnail').$image->thumbnail) }}" style="height: auto !important;" />
                      </div>
                      <div class="product-info">
                        <a href="{{ url('photo',$image->id) }}" target="_blank" class="product-title">{{ $image->title }}
                        	<span class="label label-{{ $color_status }} pull-right">{{ $txt_status }}</span>
                        	</a>
                        <span class="product-description">
                          {{ date('d M, Y', strtotime($image->date)) }}
                        </span>
                      </div>
                    </li><!-- /.item -->
                    @endforeach
                  </ul>
                </div><!-- /.box-body -->

                <div class="box-footer text-center">
                  <a href="{{ url('user/dashboard/photos') }}" class="uppercase">{{ trans('misc.view_all') }}</a>
                </div><!-- /.box-footer -->

                @else
                 <div class="box-body">
                	<h5>{{ trans('admin.no_result') }}</h5>
                	 </div><!-- /.box-body -->

                @endif

              </div>
            </div>


        </div><!-- ./row -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- Morris -->
	<script src="{{ asset('public/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/morris/morris.min.js')}}" type="text/javascript"></script>

	<!-- knob -->

	<script type="text/javascript">

var IndexToMonth = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

//** Charts
new Morris.Area({
  // ID of the element in which to draw the chart.
  element: 'chart1',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    <?php
    for ( $i=0; $i < 30; ++$i) {

		$date = date('Y-m-d', strtotime('today - '.$i.' days'));

		$_purchases = App\Models\Purchases::leftJoin('images', function($join) {
			 $join->on('purchases.images_id', '=', 'images.id');
		 })
		 ->where('images.user_id',Auth::user()->id)
		 ->whereRaw("DATE(purchases.date) = '".$date."'")->count();

		//print_r(DB::getQueryLog());
    ?>

    { days: '<?php echo $date; ?>', value: <?php echo $_purchases ?> },

    <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'days',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['{{ trans("misc.sales") }}'],
  pointFillColors: ['#FF5500'],
  lineColors: ['#DDD'],
  hideHover: 'auto',
  gridIntegers: true,
  resize: true,
  xLabelFormat: function (x) {
                  var month = IndexToMonth[ x.getMonth() ];
                  var year = x.getFullYear();
                  var day = x.getDate();
                  return  day +' ' + month;
                  //return  year + ' '+ day +' ' + month;
              },
          dateFormat: function (x) {
                  var month = IndexToMonth[ new Date(x).getMonth() ];
                  var year = new Date(x).getFullYear();
                  var day = new Date(x).getDate();
                  return day +' ' + month;
                  //return year + ' '+ day +' ' + month;
              },
});// <------------ MORRIS

var line = new Morris.Line({
    element          : 'line-chart',
    resize           : true,
		xLabelFormat: function (x) {
	                  var month = IndexToMonth[ x.getMonth() ];
	                  var year = x.getFullYear();
	                  var day = x.getDate();
	                  return  day +' ' + month;
	                  //return  year + ' '+ day +' ' + month;
	              },
	          dateFormat: function (x) {
	                  var month = IndexToMonth[ new Date(x).getMonth() ];
	                  var year = new Date(x).getFullYear();
	                  var day = new Date(x).getDate();
	                  return day +' ' + month;
	                  //return year + ' '+ day +' ' + month;
	              },
    data             : [
			<?php
	    for ($i=0; $i < 30; ++$i) {

			$date = date('Y-m-d', strtotime('today - '.$i.' days'));

			$__purchases = App\Models\Purchases::leftJoin('images', function($join) {
				 $join->on('purchases.images_id', '=', 'images.id');
			 })
			 ->where('images.user_id',Auth::user()->id)
			 ->whereRaw("DATE(purchases.date) = '".$date."'")->sum('purchases.earning_net_seller');

			//print_r(DB::getQueryLog());
	    ?>

	    { days: '<?php echo $date; ?>', item1: <?php echo $__purchases ?> },

	    <?php } ?>

    ],
    xkey             : 'days',
    ykeys            : ['item1'],
    labels           : ['{{ trans("misc.earnings") }} ({{$settings->currency_symbol}})'],
    lineColors       : ['#efefef'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    pointStrokeColors: ['#efefef'],
    gridLineColor    : '#efefef',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10
  });
  </script>
@endsection
