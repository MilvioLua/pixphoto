@extends('app')

@section('title'){{ trans('users.upload').' - ' }}@endsection

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <?php

    $date = date('Y-m-d', strtotime('today'));
	$imagesUploads = App\Models\Images::where('user_id',Auth::user()->id)->whereRaw("DATE(date) = '".$date."'")->count();

     ?>

<div class="container margin-bottom-40 padding-top-40">
	<div class="row">

    @if( Auth::user()->status == 'active' )

@if ($settings->limit_upload_user == 0 || $imagesUploads < $settings->limit_upload_user || Auth::user()->role == 'admin')

	<!-- col-md-12 -->
	<div class="col-md-12">

    <div class="wrap-center center-block">

    <div class="alert alert-warning" role="alert">

			<ul class="padding-zero">
				<?php if( $settings->limit_upload_user == 0 ) {
					$limit = strtolower(trans('admin.unlimited'));
				} else {
					$limit = $settings->limit_upload_user;
				} ?>
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-warning-sign myicon-right"></i>  {{ trans('conditions.terms') }}</li>
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-info-sign myicon-right"></i>  {{ trans('conditions.upload_max', ['limit' => $limit ]) }}</li>
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-info-sign myicon-right"></i>  {{ trans('conditions.sex_content') }}</li>
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-info-sign myicon-right"></i>  {{ trans('conditions.own_images') }}</li>
			</ul>

		</div>

@include('errors.errors-forms')
    <!-- form start -->
    <form method="POST" action="{{ url('upload') }}" enctype="multipart/form-data" id="formUpload" files="true">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<div class="filer-input-dragDrop position-relative" id="draggable">

			<input type="file" accept="image/*" name="photo" id="filePhoto">

			<!-- previewPhoto -->
			<div class="previewPhoto"></div><!-- previewPhoto -->

      <div class="btn btn-danger btn-sm btn-remove-photo display-none" id="removePhoto">
        <i class="icon icon-Delete myicon-right"></i> {{trans('misc.delete')}}
        </div>

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

			<div class="panel panel-default padding-20 border-none">

				<div class="panel-body">
                 <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('admin.title') }}</label>
                        <input type="text" value="{{ old('title') }}" name="title" id="title" class="form-control" placeholder="{{ trans('admin.title') }}">
                    </div><!-- /.form-group-->

                   <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.tags') }}</label>
                        <input type="text" value="{{ old('tags') }}" id="tagInput"  name="tags" class="form-control" placeholder="{{ trans('misc.tags') }}">
                      	<p class="help-block">* {{ trans('misc.add_tags_guide') }} ({{trans('misc.maximum_tags', ['limit' => $settings->tags_limit ]) }})</p>
                  </div><!-- /.form-group-->

                  <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.category') }}</label>
                      	<select name="categories_id" class="form-control">

                      	@foreach(  App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category )
                            <option value="{{$category->id}}">{{ $category->name }}</option>
						@endforeach

                          </select>
                  </div><!-- /.form-group-->

                  @if ($settings->sell_option == 'on'
                      && $settings->who_can_sell == 'all'
                      || $settings->sell_option == 'on'
                      && $settings->who_can_sell == 'admin'
                      && Auth::user()->role == 'admin'
                      )

                  @if ($settings->free_photo_upload == 'on')
                    <!-- Start Form Group -->
                      <div class="form-group">
                        <label>{{ trans('misc.item_for_sale') }}</label>
                        	<select name="item_for_sale" class="form-control" id="itemForSale">
                              <option value="free">{{ trans('misc.no_free') }}</option>
                              <option value="sale">{{ trans('misc.yes_for_sale') }}</option>
                            </select>
                    </div><!-- /.form-group-->
                  @else
                    <input type="hidden" name="item_for_sale" value="sale">
                  @endif

                  <!-- Start Form Group -->
                     <div class="form-group @if ($settings->free_photo_upload == 'on') display-none @endif" id="priceBox">
                       <label>({{ $settings->currency_symbol }}) {{ trans('misc.price') }}</label>
                         <input type="number" value="" name="price" class="form-control onlyNumber" autocomplete="off" id="price" placeholder="{{ trans('misc.price') }}">
                         <p class="help-block">
                           @if (Auth::user()->author_exclusive == 'yes')
                           * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}
                         @else
                           * {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission_non_exclusive)]) }}
                           @endif
                         </p>

                         <div class="alert alert-success">
                           <h4>{{trans('misc.price_formats')}}</h4>
                           <ul class="list-unstyled">
                             <li><strong>{{trans('misc.small_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="s-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
                             <li><strong>{{trans('misc.medium_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="m-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
                             <li><strong>{{trans('misc.large_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="l-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
                             <li><strong>{{trans('misc.vector_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="v-price">0</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }} {{trans('misc.if_included')}}</li>
                           </ul>
                           <small>{{trans('misc.price_maximum')}} {{\App\Helper::amountFormat($settings->max_sale_amount)}} | {{trans('misc.price_minimum')}} {{\App\Helper::amountFormat($settings->min_sale_amount)}}</small>
                         </div>
                     </div><!-- /.form-group-->

                     @endif

                  <!-- Start Form Group -->
                    <div class="form-group options_free @if ($settings->free_photo_upload == 'off') display-none @endif">
                      <label>{{ trans('misc.how_use_image') }}</label>
                      	<select name="how_use_image" class="form-control">
                            <option value="free">{{ trans('misc.use_free') }}</option>
                            <option value="free_personal">{{ trans('misc.use_free_personal') }}</option>
                             <option value="editorial_only">{{ trans('misc.use_editorial_only') }}</option>
                              <option value="web_only">{{ trans('misc.use_web_only') }}</option>
                          </select>
                  </div><!-- /.form-group-->

                  <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.type_image') }}</label>
                      	<select name="type_image" class="form-control" id="typeImage">
                            <option value="image">{{ trans('misc.image') }}</option>
                            <option value="vector">{{ trans('misc.type_vector_graphic') }}</option>
                          </select>

                          <span class="btn-block display-none" id="vector">
                            <button type="button" class="btn btn-default btn-block" id="upload_file" style="margin-top: 10px;border-style: dashed;padding: 12px;">
                            <i class="fa fa-cloud-upload myicon-right"></i> {{trans('misc.select_vector')}}
                            </button>

                              <input type="file" name="file" id="uploadFile" style="visibility: hidden;">
                          </span>
                  </div><!-- /.form-group-->
                  <small class="help-block" id="fileDocument"></small>

                  <!-- Start form-group -->
                    <div class="form-group options_free @if ($settings->free_photo_upload == 'off') display-none @endif">
                      <label>{{ trans('misc.attribution_required') }}</label>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="attribution_required" value="yes">
                          {{ trans('misc.yes') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" name="attribution_required" checked="checked" value="no">
                          {{ trans('misc.no') }}
                        </label>
                      </div>

                    </div><!-- /.form-group -->


                  <div class="form-group">
                      <label>{{ trans('admin.description') }} ({{ trans('misc.optional') }})</label>
                      	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('admin.description') }}">{{ old('description') }}</textarea>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-danger display-none" id="dangerAlert">
							<ul class="list-unstyled" id="showErrors"></ul>
						</div><!-- Alert -->

                  <div class="box-footer text-center">
                  	<hr />
                    <button type="submit" id="upload" class="btn btn-lg btn-success custom-rounded" data-msg-processing="{{trans('misc.processing')}}" data-error="{{trans('misc.error')}}" data-msg-error="{{trans('misc.err_internet_disconnected')}}">
                      <i class="fa fa-cloud-upload myicon-right"></i> {{ trans('users.upload') }}
                    </button>
                  </div><!-- /.box-footer -->
                </form>

         	</div>
         </div>

         </div><!-- wrap-center -->

		</div>
		<!-- col-md-12-->

		@else

		<div class="btn-block text-center margin-top-40">
	    			<i class="icon-warning ico-no-result"></i>
	    		</div>

		<h3 class="margin-top-none text-center no-result no-result-mg">
	    		{{trans('misc.limit_uploads_user')}}
	    	</h3>

		@endif


@else
    <div class="btn-block text-center margin-top-40">
  	    			<i class="icon-warning ico-no-result"></i>
  	    		</div>

  	   <h3 class="margin-top-none text-center no-result no-result-mg">
  	    	{{trans('misc.confirm_email')}} <strong>{{Auth::user()->email}}</strong>
  	    	</h3>
          @endif
          {{-- Verify User Active --}}

	</div><!-- row -->
</div><!-- container -->
@endsection

@section('javascript')
	<script src="{{ asset('public/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.js') }}" type="text/javascript"></script>

	<script type="text/javascript">

	//Flat red color scheme for iCheck
        $('input[type="radio"]').iCheck({
          radioClass: 'iradio_flat-red'
        });

        function replaceString(string) {
        	return string.replace(/[\-\_\.\+]/ig,' ')
        }

$('#removePhoto').click(function(){
	 	$('#filePhoto').val('');
	 	$('#title').val('');
	 	$('.previewPhoto').css({backgroundImage: 'none'}).hide();
	 	$('.filer-input-dragDrop').removeClass('hoverClass');
    $(this).hide();
	 });

//================== START FILE IMAGE FILE READER
$("#filePhoto").on('change', function(){

	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
			if($(this)[0].files.length === 0){return}


			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type


			if(!rFilter.test(oFile.type)) {
				$('#filePhoto').val('');
				$('.popout').addClass('popout-error').html("{{ trans('misc.formats_available') }}").fadeIn(500).delay(5000).fadeOut();
				return false;
			}

			var allowed_file_size = {{$settings->file_size_allowed * 1024}};

			if(fsize>allowed_file_size){
				$('#filePhoto').val('');
				$('.popout').addClass('popout-error').html("{{trans('misc.max_size').': '.App\Helper::formatBytes($settings->file_size_allowed * 1024)}}").fadeIn(500).delay(5000).fadeOut();
				return false;
			}
		<?php $dimensions = explode('x',$settings->min_width_height_image); ?>

			oFReader.onload = function (e) {

				var image = new Image();
			    image.src = oFReader.result;

				image.onload = function() {

			    	if( image.width < {{ $dimensions[0] }}) {
			    		$('#filePhoto').val('');
			    		$('.popout').addClass('popout-error').html("{{trans('misc.width_min',['data' => $dimensions[0]])}}").fadeIn(500).delay(5000).fadeOut();
			    		return false;
			    	}

			    	if( image.height < {{ $dimensions[1] }} ) {
			    		$('#filePhoto').val('');
			    		$('.popout').addClass('popout-error').html("{{trans('misc.height_min',['data' => $dimensions[1]])}}").fadeIn(500).delay(5000).fadeOut();
			    		return false;
			    	}

            $('.previewPhoto').css({backgroundImage: 'url('+e.target.result+')'}).show();
            $('#removePhoto').show();
			    	$('.filer-input-dragDrop').addClass('hoverClass');
			    	var _filname =  oFile.name;
					  var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
			    	$('#title').val(replaceString(fileName));
			    };// <<--- image.onload


           }

           oFReader.readAsDataURL($(this)[0].files[0]);

		}
	} else{
		$('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(5000).fadeOut();
		return false;
	}
});

		$('input[type="file"]').attr('title', window.URL ? ' ' : '');

		$("#tagInput").tagsInput({

		 'delimiter': [','],   // Or a string with a single delimiter. Ex: ';'
		 'width':'auto',
		 'height':'auto',
	     'removeWithBackspace' : true,
	     'minChars' : 2,
	     'maxChars' : 50,
	     'defaultText':'{{ trans("misc.add_tag") }}',
	     onChange: function() {
         	var input = $(this).siblings('.tagsinput');
         	var maxLen = {{$settings->tags_limit}};

			if( input.children('span.tag').length >= maxLen){
			        input.children('div').hide();
			    }
			    else{
			        input.children('div').show();
			    }
			},
	});

  $('#itemForSale').on('change', function() {
    if($(this).val() == 'sale') {
			$('#priceBox').slideDown();
      $('.options_free').slideUp();

		} else {
				$('#priceBox').slideUp();
        $('.options_free').slideDown();
		}
});

$('#typeImage').on('change', function(){
  if($(this).val() == 'vector') {
    $('#vector').slideDown();
  } else {
      $('#vector').slideUp('fast');
      $('#uploadFile').val('');
      $('#fileDocument').html('');
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

$(document).on('click','#deleteFile',function () {
    $('#uploadFile').val('');
    $('#fileDocument').html('');
});

//================== START FILE - FILE READER
$("#uploadFile").change(function() {

	$('#fileDocument').html('');

	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			if($(this)[0].files.length === 0){return}

			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type

			var allowed_file_size = {{$settings->file_size_allowed_vector * 1024}};

			if(fsize>allowed_file_size){
				$('.popout').addClass('popout-error').html("{{trans('misc.max_size_vector').': '.App\Helper::formatBytes($settings->file_size_allowed_vector * 1024)}}").fadeIn(500).delay(4000).fadeOut();
        $(this).val('');
				return false;
			}

			$('#fileDocument').html('<i class="fa fa-paperclip"></i> <strong class="text-info"><em>' + oFile.name + '</em></strong> - <a href="javascript:void(0);" id="deleteFile" class="text-danger">{{trans('misc.delete')}}</a>');

		}
	} else{
		alert('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.');
		return false;
	}
});
//================== END FILE - FILE READER ==============>

$('#price').on('keyup', function() {

  var valueOriginal = $('.onlyNumber').val();
  var value = parseFloat($('.onlyNumber').val());
  var element = $(this).val();

  if (element != '') {

    if (valueOriginal >= {{$settings->min_sale_amount}} && valueOriginal <= {{$settings->max_sale_amount}}) {
      var amountSmall = value;
    } else {
      amountSmall = 0;
    }
      var amountMedium = (amountSmall * 2);
      var amountLarge = (amountSmall * 3);
      var amountVector = (amountSmall * 4);


      $('#s-price').html(amountSmall);
      $('#m-price').html(amountMedium);
      $('#l-price').html(amountLarge);
      $('#v-price').html(amountVector);

  }

  if (valueOriginal == '') {
    $('#s-price').html('0');
    $('#m-price').html('0');
    $('#l-price').html('0');
    $('#v-price').html('0');
  }
});
</script>


@endsection
