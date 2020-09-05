@extends('admin.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
           <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('misc.withdrawals') }} #{{$data->id}}
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="row">
            <div class="col-xs-12">
              <div class="box">

              	<div class="box-body">
              		<dl class="dl-horizontal">

					  <!-- start -->
					  <dt>ID</dt>
					  <dd>{{$data->id}}</dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('admin.user') }}</dt>
					  <dd><a href="{{url($data->user()->username)}}" target="_blank">{{ $data->user()->username }} <i class="fa fa-external-link-square"></i></a></dd>
					  <!-- ./end -->

					@if( $data->gateway == 'PayPal' )
					  <!-- start -->
					  <dt>{{ trans('admin.paypal_account') }}</dt>
					  <dd>{{$data->account}}</dd>
					  <!-- ./end -->

					  @else
					   <!-- start -->
					  <dt>{{ trans('misc.bank_details') }}</dt>
					  <dd>{!!App\Helper::checkText($data->account)!!}</dd>
					  <!-- ./end -->

					  @endif

					  <!-- start -->
					  <dt>{{ trans('misc.amount') }}</dt>
					  <dd><strong class="text-success">{{\App\Helper::amountFormatDecimal($data->amount) }}</strong></dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('misc.payment_gateway') }}</dt>
					  <dd>{{$data->gateway}}</dd>
					  <!-- ./end -->


					  <!-- start -->
					  <dt>{{ trans('admin.date') }}</dt>
					  <dd>{{date('d M, Y', strtotime($data->date))}}</dd>
					  <!-- ./end -->

					  <!-- start -->
					  <dt>{{ trans('admin.status') }}</dt>
					  <dd>
					  	@if( $data->status == 'paid' )
                      	<span class="label label-success">{{trans('misc.paid')}}</span>
                      	@else
                      	<span class="label label-warning">{{trans('misc.pending_to_pay')}}</span>
                      	@endif
					  </dd>
					  <!-- ./end -->

					@if( $data->status == 'paid' )
					  <!-- start -->
					  <dt>{{ trans('misc.date_paid') }}</dt>
					  <dd>
					  	{{date('d M, y', strtotime($data->date_paid))}}
					  </dd>
					  <!-- ./end -->
					  @endif



					</dl>
              	</div><!-- box body -->

              	<div class="box-footer">
                  	 <a href="{{ url('panel/admin/withdrawals') }}" class="btn btn-default">{{ trans('auth.back') }}</a>

                  @if( $data->status == 'pending' )

                {!! Form::open([
			            'method' => 'POST',
			            'url' => "panel/admin/withdrawals/paid/$data->id",
			            'class' => 'displayInline'
				        ]) !!}

	            	{!! Form::submit(trans('misc.mark_paid'), ['class' => 'btn btn-success pull-right myicon-right']) !!}
	        	{!! Form::close() !!}

	        	@endif

                  </div><!-- /.box-footer -->

              </div><!-- box -->
            </div><!-- col -->
         </div><!-- row -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection
