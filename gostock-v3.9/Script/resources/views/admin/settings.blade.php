@extends('admin.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{ trans('admin.admin') }}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.general_settings') }}

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
                  <h3 class="box-title">{{ trans('admin.general_settings') }}</h3>
                </div><!-- /.box-header -->



                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/settings') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

					@include('errors.errors-forms')

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.name_site') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->title }}" name="title" class="form-control" placeholder="{{ trans('admin.title') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.welcome_text') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->welcome_text }}" name="welcome_text" class="form-control" placeholder="{{ trans('admin.welcome_text') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.welcome_subtitle') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->welcome_subtitle }}" name="welcome_subtitle" class="form-control" placeholder="{{ trans('admin.welcome_subtitle') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.keywords') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->keywords }}" id="tagInput" name="keywords" class="form-control select2">
                        <p class="help-block">* {{ trans('misc.add_tags_guide') }}</p>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.description') }}</label>
                      <div class="col-sm-10">

                      	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('admin.description') }}">{{ $settings->description }}</textarea>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.email_no_reply') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->email_no_reply }}" name="email_no_reply" class="form-control" placeholder="{{ trans('admin.email_no_reply') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.email_admin') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->email_admin }}" name="email_admin" class="form-control" placeholder="{{ trans('admin.email_admin') }}">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.link_terms') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_terms }}" name="link_terms" class="form-control" placeholder="https://yousite.com/page/terms">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.link_privacy') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_privacy }}" name="link_privacy" class="form-control" placeholder="https://yousite.com/page/privacy">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.link_license') }}</label>
                      <div class="col-sm-10">
                        <input type="text" value="{{ $settings->link_license }}" name="link_license" class="form-control" placeholder="https://yousite.com/page/license">
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.sell_option') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="sell_option" @if( $settings->sell_option == 'on' ) checked="checked" @endif value="on">
                          On <em>({{ trans('misc.members_can_sell') }})</em>
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="sell_option" @if( $settings->sell_option == 'off' ) checked="checked" @endif value="off">
                          Off <em>({{ trans('misc.members_cant_sell') }})</em>
                        </label>
                      </div>

                        <p class="help-block text-bold">* {{ trans('misc.notice_sell_option') }}</p>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.who_can_sell') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="who_can_sell" @if ($settings->who_can_sell == 'all') checked="checked" @endif value="all">
                            {{ trans('misc.all_members') }}
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="who_can_sell" @if ($settings->who_can_sell == 'admin') checked="checked" @endif value="admin">
                          {{ trans('misc.only_admin') }}
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.who_can_upload') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="who_can_upload" @if ($settings->who_can_upload == 'all') checked="checked" @endif value="all">
                            {{ trans('misc.all_members') }}
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="who_can_upload" @if ($settings->who_can_upload == 'admin') checked="checked" @endif value="admin">
                          {{ trans('misc.only_admin') }}
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.allow_free_photos') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="free_photo_upload" @if ($settings->free_photo_upload == 'on') checked="checked" @endif value="on">
                            On
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="free_photo_upload" @if ($settings->free_photo_upload == 'off') checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.show_counter') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="show_counter" @if ($settings->show_counter == 'on') checked="checked" @endif value="on">
                            On
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_counter" @if ($settings->show_counter == 'off') checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.show_categories_index') }}</label>
                      <div class="col-sm-10">

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="show_categories_index" @if ($settings->show_categories_index == 'on') checked="checked" @endif value="on">
                            On
                          </label>
                        </div>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_categories_index" @if ($settings->show_categories_index == 'off') checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.google_ads_index') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="google_ads_index" @if( $settings->google_ads_index == 'on' ) checked="checked" @endif value="on" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="google_ads_index" @if( $settings->google_ads_index == 'off' ) checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Captcha</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="captcha" @if( $settings->captcha == 'on' ) checked="checked" @endif value="on" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="captcha" @if( $settings->captcha == 'off' ) checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.new_registrations') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="registration_active" @if( $settings->registration_active == 1 ) checked="checked" @endif value="1" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="registration_active" @if( $settings->registration_active == 0 ) checked="checked" @endif value="0">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.email_verification') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="email_verification" @if( $settings->email_verification == 1 ) checked="checked" @endif value="1" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="email_verification" @if( $settings->email_verification == 0 ) checked="checked" @endif value="0">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.facebook_login') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="facebook_login" @if( $settings->facebook_login == 'on' ) checked="checked" @endif value="on" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="facebook_login" @if( $settings->facebook_login == 'off' ) checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.twitter_login') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="twitter_login" @if( $settings->twitter_login == 'on' ) checked="checked" @endif value="on" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="twitter_login" @if( $settings->twitter_login == 'off' ) checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.show_images_index') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_images_index" @if( $settings->show_images_index == 'latest' ) checked="checked" @endif value="latest" checked>
                          {{ trans('misc.latest') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_images_index" @if( $settings->show_images_index == 'featured' ) checked="checked" @endif value="featured">
                          {{ trans('misc.featured') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_images_index" @if( $settings->show_images_index == 'both' ) checked="checked" @endif value="both">
                          {{ trans('admin.both') }}
                        </label>
                      </div>

                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.show_watermark') }}</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_watermark" @if( $settings->show_watermark == '1' ) checked="checked" @endif value="1" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="show_watermark" @if( $settings->show_watermark == '0' ) checked="checked" @endif value="0">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Lightbox</label>
                      <div class="col-sm-10">

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="lightbox" @if( $settings->lightbox == 'on' ) checked="checked" @endif value="on" checked>
                          On
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="lightbox" @if( $settings->lightbox == 'off' ) checked="checked" @endif value="off">
                          Off
                        </label>
                      </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-success">{{ trans('admin.save') }}</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>

        		</div><!-- /.row -->

        	</div><!-- /.content -->

          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

	<!-- icheck -->
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		//Flat red color scheme for iCheck
        $('input[type="radio"]').iCheck({
          radioClass: 'iradio_flat-red'
        });

        $("#tagInput").tagsInput({

		 'delimiter': [','],   // Or a string with a single delimiter. Ex: ';'
		 'width':'auto',
		 'height':'auto',
	     'removeWithBackspace' : true,
	     'minChars' : 3,
	     'maxChars' : 25,
	     'defaultText':'{{ trans("misc.add_tag") }}',
	     /*onChange: function() {
         	var input = $(this).siblings('.tagsinput');
         	var maxLen = 4;

			if( input.children('span.tag').length >= maxLen){
			        input.children('div').hide();
			    }
			    else{
			        input.children('div').show();
			    }
			},*/
	});

	</script>


@endsection
