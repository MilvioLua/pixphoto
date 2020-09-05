@extends('admin.layout')

@section('css')
<link href="{{{ asset('public/plugins/iCheck/all.css') }}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
            {{{ trans('admin.admin') }}}
            	<i class="fa fa-angle-right margin-separator"></i>
            		{{{ trans('misc.theme') }}}
          </h4>

        </section>

        <!-- Main content -->
        <section class="content">

        	<div class="content">

        		<div class="row">

        	<div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">{{{ trans('misc.theme') }}}</h3>
                </div><!-- /.box-header -->

                <!-- form start -->
                <form class="form-horizontal" method="post" action="{{{ url('panel/admin/theme') }}}" enctype="multipart/form-data">

                	<input type="hidden" name="_token" value="{{{ csrf_token() }}}">

                  @if(session('success_message'))
                    <div class="box-body">
          		    <div class="alert alert-success">
          		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          								<span aria-hidden="true">×</span>
          								</button>
          		        <i class="fa fa-check margin-separator"></i> {{ session('success_message') }}
          		    </div>
                  </div>
          		@endif

              @if(session('error_max_upload_size'))
                <div class="alert alert-danger">
        		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        								<span aria-hidden="true">×</span>
        								</button>
        		      <i class="fa fa-warning margin-separator"></i>  {{trans('misc.max_upload_files', ['post_size' => ini_get("post_max_size")."B"] )}}
        		    </div>
              @endif

					@include('errors.errors-forms')


                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.logo') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->logo)}}" style="width:150px">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="logo" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('misc.recommended_size') }} 150x50 px (PNG)</p>

              <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer" id="fileContainerLogo">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Favicon</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->favicon)}}">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="favicon" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('misc.recommended_size') }} 48x48 px (PNG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.index_image_top') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->image_header)}}" style="width:200px">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="index_image_top" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('misc.recommended_size') }} 1280x850 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ trans('misc.index_image_bottom') }}</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->image_bottom)}}" style="width:200px">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="index_image_bottom" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>

                      <p class="help-block">{{ trans('misc.recommended_size') }} 1280x850 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Watermark</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->watermark)}}" style="width:300px; background-color: #000;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="watermark" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 700px (PNG)</p>
                          <p class="help-block">** {{ trans('misc.clean_cache_browser') }}</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>

                  </div><!-- /.box-body -->


                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Header color page</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->header_colors)}}" style="width:200px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="header_colors" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 1280x850 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Header cameras page</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('/public/img', $settings->header_cameras)}}" style="width:200px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="header_cameras" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 1280x850 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Avatar default</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{Storage::url(config('path.avatar').$settings->avatar)}}" style="width:180px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="avatar" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 250x250 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Cover User</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{Storage::url(config('path.cover').$settings->cover)}}" style="width:200px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="cover" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 1280x850 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Image default in categories</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('public/img-category', $settings->img_category)}}" style="width:250px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="img_category" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 457x357 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <!-- Start Box Body -->
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Image default in collections</label>
                      <div class="col-sm-10">

                        <div class="btn-block margin-bottom-10">
                          <img src="{{url('public/img', $settings->img_collection)}}" style="width:250px;">
                        </div>

                      	<div class="btn btn-info box-file">
                      		<input type="file" accept="image/*" name="img_collection" class="filePhoto" />
                      		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i>
                          <span class="text-file">{{ trans('misc.choose_image') }}</span>
                      		</div>
                          <p class="help-block">{{ trans('misc.recommended_size') }} 280x160 px (JPG)</p>

                      <div class="btn-default btn-lg btn-border btn-block pull-left text-left display-none fileContainer">
        					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
        					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image btn pull-right" title="{{ trans('misc.delete') }}"></i>
        					     </div>
                      </div>
                    </div>
                  </div><!-- /.box-body -->

                  <hr>
                  <div class="box-footer">
                    <a href="{{{ url('panel/admin/categories') }}}" class="btn btn-default">{{{ trans('admin.cancel') }}}</a>
                    <button type="submit" class="btn btn-success pull-right">{{{ trans('admin.save') }}}</button>
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
	<script src="{{{ asset('public/plugins/iCheck/icheck.min.js') }}}" type="text/javascript"></script>

	<script type="text/javascript">
		//Flat red color scheme for iCheck
        $('input[type="radio"]').iCheck({
          radioClass: 'iradio_flat-red'
        });

  $(".filePhoto").on('change', function(){

    var element = $(this);

    element.parents('.box-file').find('.text-file').html('{{trans('misc.choose_image')}}');

  	var loaded = false;
  	if(window.File && window.FileReader && window.FileList && window.Blob){
      // Check empty input filed
  		if($(this).val()) {

  			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
  			if($(this)[0].files.length === 0){return}

  			var oFile = $(this)[0].files[0];
        var fsize = $(this)[0].files[0].size; //get file size
  			var ftype = $(this)[0].files[0].type; // get file type

        // Validate formats
        if(!rFilter.test(oFile.type)) {
  				element.val('');
  				alert("{{ trans('misc.formats_available') }}");
  				return false;
  			}

        // Validate Size
        if(!rFilter.test(oFile.type)) {
  				element.val('');
  				alert("{{ trans('misc.formats_available') }}");
  				return false;
  			}

  			oFReader.onload = function (e) {

  				var image = new Image();
          image.src = oFReader.result;

  				image.onload = function() {

            element.parents('.box-body').find('.fileContainer').removeClass('display-none');
            element.parents('.box-body').find('.file-name-file').html(oFile.name);
          };// <<--- image.onload
        }
          oFReader.readAsDataURL($(this)[0].files[0]);
  		}// Check empty input filed
  	}// window File
  });
  // END UPLOAD PHOTO

  $('.delete-image').click(function(){
        var element = $(this);

        element.parents('.box-body').find('.fileContainer').addClass('display-none');
        element.parents('.box-body').find('.file-name-file').html('');
        element.parents('.box-body').find('.filePhoto').val('');
  });

	</script>


@endsection
