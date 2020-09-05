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

            		<i class="fa fa-angle-right margin-separator"></i>
            		{{ trans('admin.limits') }}

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
                  <h3 class="box-title">{{ trans('admin.limits') }}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ url('panel/admin/settings/limits') }}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.auto_approve_images') }}</label>
                      <div class="col-sm-10">
                      	<select name="auto_approve_images" class="form-control">
                            <option @if( $settings->auto_approve_images == 'on' ) selected="selected" @endif value="on">{{ trans('misc.yes') }}</option>
						  	<option @if( $settings->auto_approve_images == 'off' ) selected="selected" @endif value="off">{{ trans('misc.no') }}</option>

                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.who_can_download') }}</label>
                      <div class="col-sm-10">
                      	<select name="downloads" class="form-control">
                            <option @if( $settings->downloads == 'all' ) selected="selected" @endif value="all">{{ trans('admin.everyone_download') }}</option>
						  	<option @if( $settings->downloads == 'users' ) selected="selected" @endif value="users">{{ trans('admin.only_users') }}</option>

                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

				<!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.limit_upload_user') }}</label>
                      <div class="col-sm-10">
                      	<select name="limit_upload_user" class="form-control">
                            <option @if( $settings->limit_upload_user == 0 ) selected="selected" @endif value="0">{{ trans('admin.unlimited') }}</option>
						  	<option @if( $settings->limit_upload_user == 1 ) selected="selected" @endif value="1">1</option>
						  	<option @if( $settings->limit_upload_user == 2 ) selected="selected" @endif value="2">2</option>
						  	<option @if( $settings->limit_upload_user == 3 ) selected="selected" @endif value="3">3</option>
						  	<option @if( $settings->limit_upload_user == 4 ) selected="selected" @endif value="4">4</option>
						  	<option @if( $settings->limit_upload_user == 5 ) selected="selected" @endif value="5">5</option>
						  	<option @if( $settings->limit_upload_user == 10 ) selected="selected" @endif value="10">10</option>
						  	<option @if( $settings->limit_upload_user == 15 ) selected="selected" @endif value="15">15</option>
						  	<option @if( $settings->limit_upload_user == 20 ) selected="selected" @endif value="20">20</option>
						  	<option @if( $settings->limit_upload_user == 25 ) selected="selected" @endif value="25">25</option>
						  	<option @if( $settings->limit_upload_user == 30 ) selected="selected" @endif value="30">30</option>
						  	<option @if( $settings->limit_upload_user == 40 ) selected="selected" @endif value="40">40</option>
						  	<option @if( $settings->limit_upload_user == 50 ) selected="selected" @endif value="50">50</option>
						  	<option @if( $settings->limit_upload_user == 100 ) selected="selected" @endif value="100">100</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                <div class="box-body">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ trans('misc.daily_limit_downloads') }}</label>
                    <div class="col-sm-10">
                    	<select name="daily_limit_downloads" class="form-control">
                          <option @if( $settings->daily_limit_downloads == 0 ) selected="selected" @endif value="0">{{ trans('admin.unlimited') }}</option>
          						  	<option @if( $settings->daily_limit_downloads == 10 ) selected="selected" @endif value="10">10</option>
          						  	<option @if( $settings->daily_limit_downloads == 15 ) selected="selected" @endif value="15">15</option>
          						  	<option @if( $settings->daily_limit_downloads == 20 ) selected="selected" @endif value="20">20</option>
          						  	<option @if( $settings->daily_limit_downloads == 25 ) selected="selected" @endif value="25">25</option>
          						  	<option @if( $settings->daily_limit_downloads == 30 ) selected="selected" @endif value="30">30</option>
          						  	<option @if( $settings->daily_limit_downloads == 40 ) selected="selected" @endif value="40">40</option>
          						  	<option @if( $settings->daily_limit_downloads == 50 ) selected="selected" @endif value="50">50</option>
          						  	<option @if( $settings->daily_limit_downloads == 100 ) selected="selected" @endif value="100">100</option>
                          <option @if( $settings->daily_limit_downloads == 150 ) selected="selected" @endif value="150">150</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                 <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.result_request_images') }}</label>
                      <div class="col-sm-10">
                      	<select name="result_request" class="form-control">
                            <option @if( $settings->result_request == 12 ) selected="selected" @endif value="12">12</option>
						  	<option @if( $settings->result_request == 24 ) selected="selected" @endif value="24">24</option>
						  	<option @if( $settings->result_request == 36 ) selected="selected" @endif value="36">36</option>
						  	<option @if( $settings->result_request == 48 ) selected="selected" @endif value="48">48</option>
						  	<option @if( $settings->result_request == 60 ) selected="selected" @endif value="60">60</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">{{ trans('misc.title_length') }}</label>
                       <div class="col-sm-10">
                       	<select name="title_length" class="form-control">
                             <option @if( $settings->title_length == 50 ) selected="selected" @endif value="50">50</option>
 						  	<option @if( $settings->title_length == 100 ) selected="selected" @endif value="100">100</option>
 						  	<option @if( $settings->title_length == 150 ) selected="selected" @endif value="150">150</option>
 						  	<option @if( $settings->title_length == 200 ) selected="selected" @endif value="200">200</option>
                           </select>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.description_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="description_length" class="form-control">
                      		<option @if( $settings->description_length == 140 ) selected="selected" @endif value="140">140</option>
                            <option @if( $settings->description_length == 160 ) selected="selected" @endif value="160">160</option>
						  	<option @if( $settings->description_length == 180 ) selected="selected" @endif value="180">180</option>
						  	<option @if( $settings->description_length == 250 ) selected="selected" @endif value="250">250</option>
						  	<option @if( $settings->description_length == 500 ) selected="selected" @endif value="500">500</option>
						  	<option @if( $settings->description_length == 1000 ) selected="selected" @endif value="1000">1000</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.message_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="message_length" class="form-control">
                            <option @if( $settings->message_length == 160 ) selected="selected" @endif value="160">160</option>
						  	<option @if( $settings->message_length == 180 ) selected="selected" @endif value="180">180</option>
						  	<option @if( $settings->message_length == 250 ) selected="selected" @endif value="250">250</option>
						  	<option @if( $settings->message_length == 500 ) selected="selected" @endif value="500">500</option>
						  	<option @if( $settings->message_length == 1000 ) selected="selected" @endif value="1000">1000</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.comment_length') }}</label>
                      <div class="col-sm-10">
                      	<select name="comment_length" class="form-control">
                            <option @if( $settings->message_length == 160 ) selected="selected" @endif value="160">160</option>
						  	<option @if( $settings->message_length == 180 ) selected="selected" @endif value="180">180</option>
						  	<option @if( $settings->message_length == 250 ) selected="selected" @endif value="250">250</option>
						  	<option @if( $settings->message_length == 500 ) selected="selected" @endif value="500">500</option>
						  	<option @if( $settings->message_length == 1000 ) selected="selected" @endif value="1000">1000</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.file_size_allowed') }}</label>
                      <div class="col-sm-10">
                      	<select name="file_size_allowed" class="form-control">
                            <option @if( $settings->file_size_allowed == 1024 ) selected="selected" @endif value="1024">1 MB</option>
						  	<option @if( $settings->file_size_allowed == 2048 ) selected="selected" @endif value="2048">2 MB</option>
						  	<option @if( $settings->file_size_allowed == 3072 ) selected="selected" @endif value="3072">3 MB</option>
						  	<option @if( $settings->file_size_allowed == 4096 ) selected="selected" @endif value="4096">4 MB</option>
						  	<option @if( $settings->file_size_allowed == 5120 ) selected="selected" @endif value="5120">5 MB</option>
						  	<option @if( $settings->file_size_allowed == 10240 ) selected="selected" @endif value="10240">10 MB</option>
                <option @if( $settings->file_size_allowed == 15360 ) selected="selected" @endif value="15360">15 MB</option>
                <option @if( $settings->file_size_allowed == 20480 ) selected="selected" @endif value="20480">20 MB</option>
                <option @if( $settings->file_size_allowed == 30720 ) selected="selected" @endif value="30720">30 MB</option>
                <option @if( $settings->file_size_allowed == 40960 ) selected="selected" @endif value="40960">40 MB</option>
                <option @if( $settings->file_size_allowed == 51200 ) selected="selected" @endif value="51200">50 MB</option>
                <option @if( $settings->file_size_allowed == 102400 ) selected="selected" @endif value="102400">100 MB</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong></span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group margin-zero">
                      <label class="col-sm-2 control-label">{{ trans('admin.file_size_allowed_vector') }}</label>
                      <div class="col-sm-10">
                      	<select name="file_size_allowed_vector" class="form-control">
                            <option @if( $settings->file_size_allowed_vector == 1024 ) selected="selected" @endif value="1024">1 MB</option>
						  	<option @if( $settings->file_size_allowed_vector == 2048 ) selected="selected" @endif value="2048">2 MB</option>
						  	<option @if( $settings->file_size_allowed_vector == 3072 ) selected="selected" @endif value="3072">3 MB</option>
						  	<option @if( $settings->file_size_allowed_vector == 4096 ) selected="selected" @endif value="4096">4 MB</option>
						  	<option @if( $settings->file_size_allowed_vector == 5120 ) selected="selected" @endif value="5120">5 MB</option>
						  	<option @if( $settings->file_size_allowed_vector == 10240 ) selected="selected" @endif value="10240">10 MB</option>
                <option @if( $settings->file_size_allowed_vector == 15360 ) selected="selected" @endif value="15360">15 MB</option>
                <option @if( $settings->file_size_allowed_vector == 20480 ) selected="selected" @endif value="20480">20 MB</option>
                <option @if( $settings->file_size_allowed_vector == 30720 ) selected="selected" @endif value="30720">30 MB</option>
                <option @if( $settings->file_size_allowed_vector == 40960 ) selected="selected" @endif value="40960">40 MB</option>
                <option @if( $settings->file_size_allowed_vector == 51200 ) selected="selected" @endif value="51200">50 MB</option>
                <option @if( $settings->file_size_allowed_vector == 102400 ) selected="selected" @endif value="102400">100 MB</option>
                          </select>
                          <span class="help-block ">{{ trans('admin.upload_max_filesize_info') }} <strong><?php echo str_replace('M', 'MB', ini_get('upload_max_filesize')) ?></strong></span>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.min_width_height_image') }}</label>
                      <div class="col-sm-10">
                      	<select name="min_width_height_image" class="form-control">
                          <option @if( $settings->min_width_height_image == '1024x768' ) selected="selected" @endif value="1024x768">1024x768</option>
                          <option @if( $settings->min_width_height_image == '1280x720' ) selected="selected" @endif value="1280x720">1280x720</option>
                <option @if( $settings->min_width_height_image == '1600x900' ) selected="selected" @endif value="1600x900">1600x900</option>
						  	<option @if( $settings->min_width_height_image == '1680x1050' ) selected="selected" @endif value="1680x1050">1680x1050</option>
						  	<option @if( $settings->min_width_height_image == '1600x1200' ) selected="selected" @endif value="1600x1200">1600x1200</option>
						  	<option @if( $settings->min_width_height_image == '1920x850' ) selected="selected" @endif value="1920x850">1920x850</option>
						  	<option @if( $settings->min_width_height_image == '1920x1080' ) selected="selected" @endif value="1920x1080">1920x1080</option>
						  	<option @if( $settings->min_width_height_image == '1920x1200' ) selected="selected" @endif value="1920x1200">1920x1200</option>
                          </select>
                      </div>
                    </div>
                  </div><!-- /.box-body -->


                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('admin.tags_limit') }}</label>
                      <div class="col-sm-10">
                      	<select name="tags_limit" class="form-control">
                            <option @if( $settings->tags_limit == 3 ) selected="selected" @endif value="3">3</option>
                            <option @if( $settings->tags_limit == 4 ) selected="selected" @endif value="4">4</option>
            						  	<option @if( $settings->tags_limit == 5 ) selected="selected" @endif value="5">5</option>
            						  	<option @if( $settings->tags_limit == 6 ) selected="selected" @endif value="6">6</option>
            						  	<option @if( $settings->tags_limit == 7 ) selected="selected" @endif value="7">7</option>
            						  	<option @if( $settings->tags_limit == 8 ) selected="selected" @endif value="8">8</option>
            						  	<option @if( $settings->tags_limit == 9 ) selected="selected" @endif value="9">9</option>
            						  	<option @if( $settings->tags_limit == 10 ) selected="selected" @endif value="10">10</option>
            						  	<option @if( $settings->tags_limit == 15 ) selected="selected" @endif value="15">15</option>
            						  	<option @if( $settings->tags_limit == 20 ) selected="selected" @endif value="20">20</option>
                            <option @if( $settings->tags_limit == 30 ) selected="selected" @endif value="30">30</option>
                            <option @if( $settings->tags_limit == 40 ) selected="selected" @endif value="40">40</option>
                            <option @if( $settings->tags_limit == 50 ) selected="selected" @endif value="50">50</option>
                            <option @if( $settings->tags_limit == 100 ) selected="selected" @endif value="100">100</option>
                            <option @if( $settings->tags_limit == 150 ) selected="selected" @endif value="150">150</option>
                            <option @if( $settings->tags_limit == 200 ) selected="selected" @endif value="200">200</option>
                          </select>
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
