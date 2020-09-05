@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('misc.payment_settings') }}

          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	 @if(Session::has('success_message'))
		    <div class="alert alert-success">
		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
								</button>
		       <i class="fa fa-check margin-separator"></i> {{ Session::get('success_message') }}
		    </div>
		@endif

        	<div class="content">

        		<div class="row">

        	<div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><strong>{{ trans('misc.payment_settings') }}</strong></h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/payments') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.currency_code') }}</label>
                    <div class="col-sm-10">
                      <input type="text" value="{{ $settings->currency_code }}" name="currency_code" class="form-control" placeholder="{{ trans('misc.currency_code') }}">
                    </div>
                  </div>
                </div><!-- /.box-body -->

                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.currency_symbol') }}</label>
                    <div class="col-sm-10">
                      <input type="text" value="{{ $settings->currency_symbol }}" name="currency_symbol" class="form-control" placeholder="{{ trans('misc.currency_symbol') }}">
                      <p class="help-block">{{ trans('misc.notice_currency') }}</p>
                    </div>
                  </div>
                </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.fee_commission') }} {{ trans('misc.exclusive_author') }}</label>
                      <div class="col-sm-10">
                      	<select name="fee_commission" class="form-control">
                          @for ($i=1; $i <= 95; ++$i)
                            <option @if( $settings->fee_commission == $i ) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                            @endfor
                            </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.fee_commission') }} {{ trans('misc.non_exclusive_author') }}</label>
                      <div class="col-sm-10">
                      	<select name="fee_commission_non_exclusive" class="form-control">
                          @for ($i=1; $i <= 95; ++$i)
                            <option @if( $settings->fee_commission_non_exclusive == $i ) selected="selected" @endif value="{{$i}}">{{$i}}%</option>
                            @endfor
                            </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.min_sale_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->min_sale_amount }}" name="min_sale_amount" class="form-control onlyNumber" placeholder="{{ trans('misc.min_sale_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.max_sale_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->max_sale_amount }}" name="max_sale_amount" class="form-control onlyNumber" placeholder="{{ trans('misc.max_sale_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.min_deposits_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->min_deposits_amount }}" name="min_deposits_amount" class="form-control onlyNumber" placeholder="{{ trans('misc.min_deposits_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.max_deposits_amount') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->max_deposits_amount }}" name="max_deposits_amount" class="form-control onlyNumber" placeholder="{{ trans('misc.max_deposits_amount') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.amount_min_withdrawal') }}</label>
                      <div class="col-sm-10">
                        <input type="number" min="1" autocomplete="off" value="{{ $settings->amount_min_withdrawal }}" name="amount_min_withdrawal" class="form-control onlyNumber" placeholder="{{ trans('misc.amount_min_withdrawal') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                 <div class="box-body">
                   <div class="form-group">
                     <label class="col-sm-2 control-label">{{ trans('misc.currency_position') }}</label>
                     <div class="col-sm-10">
                       <select name="currency_position" class="form-control">
                         <option @if( $settings->currency_position == 'left' ) selected="selected" @endif value="left">{{$settings->currency_symbol}}99 - {{trans('misc.left')}}</option>
                         <option @if( $settings->currency_position == 'right' ) selected="selected" @endif value="right">99{{$settings->currency_symbol}} {{trans('misc.right')}}</option>
                         </select>
                     </div>
                   </div>
                 </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.decimal_format') }}</label>
                    <div class="col-sm-10">
                      <select name="decimal_format" class="form-control">
                        <option @if( $settings->decimal_format == 'dot' ) selected="selected" @endif value="dot">1,999.95</option>
                        <option @if( $settings->decimal_format == 'comma' ) selected="selected" @endif value="comma">1.999,95</option>
                        </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->

               <div class="box-footer">
                 <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
               </div><!-- /.box-footer -->
               </form>

              </div><!-- /.row -->

        	</div><!-- /.content -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- icheck -->
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		//Flat red color scheme for iCheck
        $('input[type="radio"]').iCheck({
          radioClass: 'iradio_flat-red'
        });

        $('input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_square-red',
    	    radioClass: 'iradio_square-red'
	  });

    $(document).ready(function() {

        $(".onlyNumber").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

    });

	</script>


@endsection
