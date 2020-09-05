@extends('app')

@section('css')
<link href="{{ asset('public/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('public/plugins/tagsinput/jquery.tagsinput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="container margin-bottom-40 padding-top-40">
	<div class="row">

	<!-- col-md-4-->
		<div class="col-md-4">

			<div class="block-block text-center margin-bottom-10">
        		<img src="{{Storage::url(config('path.thumbnail').$data->thumbnail)}}" style="max-width:280px; border-radius:6px;">
        	</div>

			<a href="{{url('photo',$data->id)}}" class="btn btn-block btn-success btn-lg margin-bottom-10 custom-rounded">{{trans('misc.view_photo')}} <i class="fa fa-long-arrow-right"></i></a>

		<div class="alert alert-warning" role="alert">

			<ul class="padding-zero">
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-warning-sign myicon-right"></i>  {{ trans('conditions.terms') }}</li>
				<li class="margin-bottom-10"><i class="glyphicon glyphicon-info-sign myicon-right"></i>  {{ trans('conditions.sex_content') }}</li>
			</ul>

		</div>
	</div><!-- col-md-4-->

	<!-- col-md-8 -->
	<div class="col-md-8">

@if (Session::has('success_message'))
			<div class="alert alert-success btn-sm alert-fonts" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('success_message') }}
            		</div>
            	@endif

			@include('errors.errors-forms')

    <!-- form start -->
    <form method="POST" action="{{url('update/photo')}}" enctype="multipart/form-data">

    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	<input type="hidden" name="id" value="{{ $data->id }}">

			<div class="panel panel-default padding-20 border-none">

				<div class="panel-body">
                 <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('admin.title') }}</label>
                        <input type="text" value="{{ $data->title }}" name="title" id="title" class="form-control" placeholder="{{ trans('admin.title') }}">
                    </div><!-- /.form-group-->

                   <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.tags') }}</label>
                        <input type="text" value="{{ $data->tags }}" id="tagInput"  name="tags" class="form-control" placeholder="{{ trans('misc.tags') }}">
                      	<p class="help-block">* {{ trans('misc.add_tags_guide') }} ({{trans('misc.maximum_tags', ['limit' => $settings->tags_limit ]) }})</p>
                  </div><!-- /.form-group-->

                  <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.category') }}</label>
                      	<select name="categories_id" class="form-control">

                      	@foreach(  App\Models\Categories::where('mode','on')->orderBy('name')->get() as $category )
                            <option @if( $data->categories_id == $category->id ) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
						@endforeach

                          </select>
                  </div><!-- /.form-group-->

									@if($data->item_for_sale == 'free'
											&& $settings->sell_option == 'on'
                      && $settings->who_can_sell == 'all'
                      || $data->item_for_sale == 'free'
											&& $settings->sell_option == 'on'
                      && $settings->who_can_sell == 'admin'
                      && Auth::user()->role == 'admin'
                      )
									<!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.item_for_sale') }}</label>
                      	<select name="item_for_sale" class="form-control" id="itemForSale">
                            <option value="free">{{ trans('misc.no_free') }}</option>
                            <option value="sale">{{ trans('misc.yes_for_sale') }}</option>
                          </select>
                  </div><!-- /.form-group-->

									<!-- Start Form Group -->
                     <div class="form-group display-none" id="priceBox">
                       <label>({{ $settings->currency_symbol }}) {{ trans('misc.price') }}</label>
                         <input type="number" value="" name="price" class="form-control onlyNumber" id="price" autocomplete="off" placeholder="{{ trans('misc.price') }}">
                         <p class="help-block">* {{ trans('misc.user_gain', ['percentage' => (100 - $settings->fee_commission)]) }}</p>

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
                    <div class="form-group">
                      <label>{{ trans('misc.camera') }}</label>
                        <input type="text" value="{{ $data->camera }}" name="camera" class="form-control" placeholder="{{ trans('misc.camera') }}">
                    </div><!-- /.form-group-->

                  <!-- Start Form Group -->
                    <div class="form-group">
                      <label>{{ trans('misc.exif_data') }}</label>
                        <input type="text" value="{{ $data->exif }}" name="exif" class="form-control" placeholder="{{ trans('misc.exif_data') }}">
                    </div><!-- /.form-group-->

							@if($data->item_for_sale == 'free')
                  <!-- Start Form Group -->
                    <div class="form-group options_free">
                      <label>{{ trans('misc.how_use_image') }}</label>
                      	<select name="how_use_image" class="form-control">
                            <option @if( $data->how_use_image == 'free' ) selected="selected" @endif value="free">{{ trans('misc.use_free') }}</option>
                            <option @if( $data->how_use_image == 'free_personal' ) selected="selected" @endif value="free_personal">{{ trans('misc.use_free_personal') }}</option>
                             <option @if( $data->how_use_image == 'editorial_only' ) selected="selected" @endif value="editorial_only">{{ trans('misc.use_editorial_only') }}</option>
                              <option @if( $data->how_use_image == 'web_only' ) selected="selected" @endif value="web_only">{{ trans('misc.use_web_only') }}</option>

                          </select>
                  </div><!-- /.form-group-->

                  <!-- Start form-group -->
                    <div class="form-group options_free">
                      <label>{{ trans('misc.attribution_required') }}</label>

                      	<div class="radio">
                        <label class="padding-zero">
                          <input type="radio" @if( $data->attribution_required == 'yes' ) checked="checked" @endif name="attribution_required" value="yes">
                          {{ trans('misc.yes') }}
                        </label>
                      </div>

                      <div class="radio">
                        <label class="padding-zero">
                          <input type="radio" @if( $data->attribution_required == 'no' ) checked="checked" @endif name="attribution_required" value="no">
                          {{ trans('misc.no') }}
                        </label>
                      </div>

                    </div><!-- /.form-group -->

									@else

										<!-- Start Form Group -->
	                     <div class="form-group">
	                       <label>({{ $settings->currency_symbol }}) {{ trans('misc.price') }}</label>
	                         <input type="number" value="{{ $data->price }}" name="price" class="form-control onlyNumber" id="price" autocomplete="off" placeholder="{{ trans('misc.price') }}">
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
	                             <li><strong>{{trans('misc.small_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="s-price">{{ $data->price }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
	                             <li><strong>{{trans('misc.medium_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="m-price">{{ $data->price * 2 }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
	                             <li><strong>{{trans('misc.large_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="l-price">{{ $data->price * 3 }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}</li>
	                             <li><strong>{{trans('misc.vector_photo_price')}}</strong> {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="v-price">{{ $data->price * 4 }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }} {{trans('misc.if_included')}}</li>
	                           </ul>
	                           <small>{{trans('misc.price_maximum')}} {{\App\Helper::amountFormat($settings->max_sale_amount)}} | {{trans('misc.price_minimum')}} {{\App\Helper::amountFormat($settings->min_sale_amount)}}</small>
	                         </div>
	                     </div><!-- /.form-group-->

									@endif

                  <div class="form-group">
                      <label>{{ trans('admin.description') }} ({{ trans('misc.optional') }})</label>
                      	<textarea name="description" rows="4" id="description" class="form-control" placeholder="{{ trans('admin.description') }}">{{ $data->description }}</textarea>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-danger display-none" id="dangerAlert">
							<ul class="list-unstyled" id="showErrors"></ul>
						</div><!-- Alert -->

                  <div class="box-footer text-center">
                  	<hr />
                    <button type="submit"class="btn btn-lg btn-success custom-rounded">{{ trans('misc.save_changes') }}</button>
                  </div><!-- /.box-footer -->
                </form>

         	</div>
         </div>

		</div>
		<!-- col-md-8-->

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
			    	$('.filer-input-dragDrop').addClass('hoverClass');
			    	var _filname =  oFile.name;
					var fileName = _filname.substr(0, _filname.lastIndexOf('.'));
			    	$('#title').val(fileName);
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

	$('#itemForSale').on('change', function(){
    if($(this).val() == 'sale') {
			$('#priceBox').slideDown();
      $('.options_free').slideUp();

		} else {
				$('#priceBox').slideUp();
        $('.options_free').slideDown();
		}
});

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
