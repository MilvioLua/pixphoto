@extends('dashboard.layout')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">

.position-relative {
    position: relative;
}


.filer-input-dragDrop {
    display: block;
    width: 100%;
    margin: 0 auto 25px auto;
    padding: 50px 0;
    color: #8d9499;
    color: #97A1A8;
    cursor: pointer;
    background: #fff;
    border-radius: 6px;
    border: 2px dashed #C8CBCE;
    text-align: center;
    -webkit-transition: box-shadow 0.3s,
                        border-color 0.3s;
    -moz-transition: box-shadow 0.3s,
                        border-color 0.3s;
    transition: box-shadow 0.3s,
                        border-color 0.3s;
                        overflow: hidden;
}
.filer-input-dragDrop:hover,
.hoverClass {
	border-color: #868686;
}
.filer.dragged .filer-input-dragDrop {
    border-color: #aaa;
    box-shadow: inset 0 0 20px rgba(0,0,0,.08);
}

.filer.dragged .filer-input-dragDrop * {
    pointer-events: none;
}

.filer.dragged .filer-input-icon {
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg);
}

.filer.dragged .filer-input-text,
.filer.dragged .filer-input-choose-btn {
    filter: alpha(opacity=30);
    opacity: 0.3;
}

.filer-input-dragDrop .filer-input-icon {
    font-size: 70px;
    margin-top: -10px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.filer-input-text h3 {
    margin: 0;
    font-size: 18px;
}

.filer-input-text span {
    font-size: 12px;
}

.filer-input-choose-btn {
    display: inline-block;
    padding: 8px 14px;
    outline: none;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    white-space: nowrap;
    font-size: 12px;
    font-weight: bold;
    color: #8d9496;
    border-radius: 3px;
    border: 1px solid #c6c6c6;
    vertical-align: middle;
    background-color: #fff;
    box-shadow: 0px 1px 5px rgba(0,0,0,0.05);
    -webkit-transition: all 0.2s;
    -moz-transition: all 0.2s;
    transition: all 0.2s;
}

.filer-input-choose-btn:hover,
.filer-input-choose-btn:active {
    color: inherit;
}

.filer-input-choose-btn:active {
    background-color: #f5f5f5;
}
.p-box {
  padding: 20px;
}
</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h4>
           {{ trans('admin.admin') }} <i class="fa fa-angle-right margin-separator"></i> {{ trans('bulk_upload.bulk_upload') }}
          </h4>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-xs-12">
              <div class="box p-box">

            <div class="overlay display-none">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

            @if(session('success_upload'))
              <div class="alert alert-success">
      		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      								<span aria-hidden="true">×</span>
      								</button>
      		      <i class="fa fa-check margin-separator"></i>  {{session('success_upload')}}
                <a href="{{url('user/dashboard/photos')}}" style="text-decoration:none;" target="_blank" class="btn btn-sm btn-warning">{{trans('bulk_upload.go_to_photos')}} <i class="fa fa-external-link-square"></i></a>
      		    </div>
            @endif

            @if(session('error_max_upload'))
              <div class="alert alert-danger">
      		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      								<span aria-hidden="true">×</span>
      								</button>
      		      <i class="fa fa-warning margin-separator"></i>  {{session('error_max_upload')}}
      		    </div>
            @endif

            @if(session('error_max_upload_size'))
              <div class="alert alert-danger">
      		    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      								<span aria-hidden="true">×</span>
      								</button>
      		      <i class="fa fa-warning margin-separator"></i>  {{trans('bulk_upload.max_upload_files', ['post_size' => ini_get("post_max_size")."B"] )}}
      		    </div>
            @endif

                @include('errors.errors-forms')

                  <!-- form start -->
                  <form class="form-horizontal" method="POST" action="{{url('panel/admin/bulk-upload')}}" enctype="multipart/form-data" id="formUpload">

                  	<input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="filer-input-dragDrop position-relative" id="draggable">

              			<input type="file" accept="image/*" name="photo[]" id="filePhoto" multiple>

              			<div class="filer-input-inner">
              				<div class="filer-input-icon">
              					<i class="fa fa-cloud-upload"></i>
              					</div>
              					<div class="filer-input-text">
              						<h3 class="margin-bottom-10">{{ trans('misc.click_select_image') }}</h3>
              						<h3>{{ trans('misc.max_size') }}: {{  $settings->min_width_height_image.' - '.App\Helper::formatBytes($settings->file_size_allowed * 1024)}} </h3>
              					</div>
              				</div>
              			</div>

                    <span class="help-block text-center margin-bottom-zero"><strong>{{trans('bulk_upload.notice_bulk_upload')}}</strong></span>
                    <span class="help-block text-center margin-top-zero"><strong>{{trans('bulk_upload.max_files_upload_limit', ['post_size' => ini_get("post_max_size")."B", 'max_file_uploads' => ini_get("max_file_uploads")])}}</strong></span>

                    <!-- Start Box Body -->
                   <div class="box-body">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">{{ trans('misc.tags') }}</label>
                       <div class="col-sm-10">
                         <input type="text" value="{{old('tags')}}" id="tagInput"  name="tags" class="form-control" placeholder="{{ trans('misc.tags') }}">
                       	<p class="help-block">* {{ trans('misc.add_tags_guide') }}</p>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                   <!-- Start Box Body -->
                    <div class="box-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{ trans('misc.category') }}</label>
                        <div class="col-sm-10">
                        	<select name="categories_id" class="form-control">
                        	@foreach(  App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category )
                              <option @if( $category->id == 1 ) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                    <div class="box-body">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{ trans('misc.item_for_sale') }}</label>
                        <div class="col-sm-10">
                          <select name="item_for_sale" class="form-control" id="itemForSale">
                              <<option value="free">{{ trans('misc.no_free') }}</option>
                              <option value="sale">{{ trans('misc.yes_for_sale') }}</option>
                            </select>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                   <div class="box-body display-none" id="priceBox">
                     <div class="form-group">
                       <label class="col-sm-2 control-label">{{ trans('misc.price') }}</label>
                       <div class="col-sm-10">
                         <input type="number" value="" name="price" class="form-control onlyNumber" placeholder="{{ trans('misc.price') }}">
                         <p class="help-block">* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}</p>
                       </div>
                     </div>
                   </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                    <div class="box-body options_free">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{ trans('misc.how_use_image') }}</label>
                        <div class="col-sm-10">
                          <select name="how_use_image" class="form-control">
                              <option value="free">{{ trans('misc.use_free') }}</option>
                              <option value="free_personal">{{ trans('misc.use_free_personal') }}</option>
                               <option value="editorial_only">{{ trans('misc.use_editorial_only') }}</option>
                                <option value="web_only">{{ trans('misc.use_web_only') }}</option>
                            </select>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                    <!-- Start Box Body -->
                    <div class="box-body options_free">
                      <div class="form-group">
                        <label class="col-sm-2 control-label">{{ trans('misc.attribution_required')  }}</label>
                        <div class="col-sm-10">

                        	<div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="attribution_required" value="yes">
                            {{ trans('misc.yes')  }}
                          </label>
                        </div>

                        <div class="radio">
                          <label class="padding-zero">
                            <input type="radio" name="attribution_required" checked value="no">
                           {{ trans('misc.no')  }}
                          </label>
                        </div>
                        </div>
                      </div>
                    </div><!-- /.box-body -->

                  </form>

                </div><!-- /.box -->
              </div>
            </div>



          <!-- Your Page Content Here -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
@endsection

@section('javascript')

  <script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.js') }}" type="text/javascript"></script>

  <script type="text/javascript">

  $("#tagInput").tagsInput({

   'delimiter': [','],   // Or a string with a single delimiter. Ex: ';'
   'width':'auto',
   'height':'auto',
     'removeWithBackspace' : true,
     'minChars' : 3,
     'maxChars' : 25,
     'defaultText':'{{ trans("misc.add_tag") }}',
});

  //================== START FILE IMAGE FILE READER
  $("#filePhoto").on('change', function(){

    if(window.File && window.FileReader && window.FileList && window.Blob){
  		if($(this).val()){ //check empty input filed
  			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
  			if($(this)[0].files.length === 0){return}


  			var oFile = $(this)[0].files[0];
  			var fsize = $(this)[0].files[0].size; //get file size
  			var ftype = $(this)[0].files[0].type; // get file type


  			if(!rFilter.test(oFile.type)) {
  				$('#filePhoto').val('');
  				alert("{{ trans('misc.formats_available') }}");
  				return false;
  			}

  			var allowed_file_size = {{$settings->file_size_allowed * 1024}};

  			if(fsize>allowed_file_size){
  				$('#filePhoto').val('');
  				alert("{{trans('misc.max_size').': '.App\Helper::formatBytes($settings->file_size_allowed * 1024)}}");
  				return false;
  			}
  		<?php $dimensions = explode('x',$settings->min_width_height_image); ?>

  			oFReader.onload = function (e) {

  				var image = new Image();
  			    image.src = oFReader.result;

  				image.onload = function() {

  			    	if( image.width < {{ $dimensions[0] }}) {
  			    		$('#filePhoto').val('');
  			    		alert("{{trans('misc.width_min',['data' => $dimensions[0]])}}");
  			    		return false;
  			    	}

  			    	if( image.height < {{ $dimensions[1] }} ) {
  			    		$('#filePhoto').val('');
  			    		alert("{{trans('misc.height_min',['data' => $dimensions[1]])}}");
  			    		return false;
  			    	}

              $('.overlay').removeClass('display-none');
              $('#formUpload').submit();

  			    };// <<--- image.onload
          }

             oFReader.readAsDataURL($(this)[0].files[0]);

  		}
  	} else{
  		alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
  		return false;
  	}

  });

    //Flat red color scheme for iCheck
    $('input[type="radio"]').iCheck({
      radioClass: 'iradio_flat-red'
    });

    $('#itemForSale').on('change', function(){
      if($(this).val() == 'sale') {
  			$('#priceBox').slideDown();
        $('.options_free').slideUp();

  		} else {
  				$('#priceBox').slideUp();
          $('.options_free').slideDown();
  		}
  });

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

</script>

@endsection
