<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Images;
use App\Models\Followers;
use App\Models\Like;
use App\Models\ImagesReported;
use App\Models\Stock;
use App\Models\AdminSettings;
use App\Models\Downloads;
use App\Models\Notifications;
use App\Models\Visits;
use App\Models\Collections;
use App\Models\CollectionsImages;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Image;
use App\Models\Purchases;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller {

	 public function __construct( AdminSettings $settings, Request $request) {
		$this->settings = $settings::first();
		$this->request = $request;
	}

	 protected function validator(array $data, $id = null) {

    	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});

		$sizeAllowed = $this->settings->file_size_allowed * 1024;

		$dimensions = explode('x',$this->settings->min_width_height_image);

		if ($this->settings->currency_position == 'right') {
			$currencyPosition =  2;
		} else {
			$currencyPosition =  null;
		}

		$messages = array (
		'photo.required' => trans('misc.please_select_image'),
    "photo.max"   => trans('misc.max_size').' '.Helper::formatBytes( $sizeAllowed, 1 ),
		"price.required_if" => trans('misc.price_required'),
		'price.min' => trans('misc.price_minimum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
		'price.max' => trans('misc.price_maximum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

	);

		// Create Rules
		if ($id == null) {
			return Validator::make($data, [
			 'photo'       => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width='.$dimensions[0].',min_height='.$dimensions[1].'|max:'.$this->settings->file_size_allowed.'',
        	'title'       => 'required|min:3|max:50',
            'description' => 'min:2|max:'.$this->settings->description_length.'',
	        'tags'        => 'required',
					'price' => 'required_if:item_for_sale,==,sale|integer|min:'.$this->settings->min_sale_amount.'|max:'.$this->settings->max_sale_amount.'',
					'file' => 'max:'.$this->settings->file_size_allowed_vector.'',
        ], $messages);

		// Update Rules
		} else {
			return Validator::make($data, [
	        	'title'       => 'required|min:3|max:50',
	            'description' => 'min:2|max:'.$this->settings->description_length.'',
		        'tags'        => 'required',
						'price' => 'required_if:item_for_sale,==,sale|integer|min:'.$this->settings->min_sale_amount.'|max:'.$this->settings->max_sale_amount.''
	        ], $messages);
		}

    }

	 /**
   * Display a listing of the resource.
   *
   * @return Response
   */
	 public function index()
	 {
	 	$data = Images::all();

		return view('admin.images')->withData($data);
	 }

	 /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
	 public function createOld(Request $request) {

		 if(Auth::guest()) {
		 	return response()->json([
			        'session_null' => true,
			        'success' => false,
			    ]);
		 }

		// PATHS
		$temp            = 'public/temp/';
	  $path_preview    = 'public/uploads/preview/';
		$path_thumbnail  = 'public/uploads/thumbnail/';
		$path_small      = 'public/uploads/small/';
		$path_medium     = 'public/uploads/medium/';
		$path_large      = 'public/uploads/large/';
		$watermarkSource = 'public/img/watermark.png';
		$pathFileVector  = 'public/uploads/files/';

		 $input = $request->all();

	   $validator = $this->validator($input);

		 if ($validator->fails()) {
 			 return response()->json([
 					 'success' => false,
 					 'errors' => $validator->getMessageBag()->toArray(),
 			 ]);
 	 } //<-- Validator

			$vectorFile = '';

			// File Vector
			if($request->hasFile('file')) {

			$extensionVector = strtolower($request->file('file')->getClientOriginalExtension());
			$fileVector      = strtolower(Auth::user()->id.time().str_random(40).'.'.$extensionVector);
			$sizeFileVector  = Helper::formatBytes($request->file('file')->getSize(), 1);

			$valid_formats = ['ai', 'psd', 'eps', 'svg'];

			if(!in_array($extensionVector, $valid_formats)) {
 	        return response()->json([
 			        'success' => false,
 			        'errors' => ['error_file' => trans('misc.file_validation', ['values' => 'AI, EPS, PSD, SVG'])],
 			    ]);
			}

			if($extensionVector == 'ai') {
				$mime = ['application/illustrator', 'application/postscript', 'application/vnd.adobe.illustrator', 'application/pdf'];

			} elseif ($extensionVector == 'eps') {
				$mime = ['application/postscript', 'image/x-eps', 'application/pdf', 'application/octet-stream'];

			} elseif ($extensionVector == 'psd') {
				$mime = ['application/photoshop', 'application/x-photoshop', 'image/photoshop', 'image/psd', 'image/vnd.adobe.photoshop', 'image/x-photoshop', 'image/x-psd'];

			} elseif ($extensionVector == 'svg') {
				$mime = ['image/svg+xml'];
			}

			if(!in_array($request->file('file')->getMimeType(), $mime)) {
 	        return response()->json([
 			        'success' => false,
 			        'errors' => ['error_file' => trans('misc.file_validation', ['values' => 'AI, EPS, PSD, SVG'])],
 			    ]);
			}


			if($request->file('file')->move($temp, $fileVector)) {
				//======= Copy Folder Large and Delete...
				if ( \File::exists($temp.$fileVector) ) {
						\File::copy($temp.$fileVector, $pathFileVector.$fileVector);
						\File::delete($temp.$fileVector);

						$vectorFile = 'yes';
				}//<--- IF FILE EXISTS
			}
		}

	    //<--- HASFILE PHOTO
	    if($request->hasFile('photo') )	{

		$extension       = $request->file('photo')->getClientOriginalExtension();
		$originalName    = Helper::fileNameOriginal($request->file('photo')->getClientOriginalName());
		$type_mime_img   = $request->file('photo')->getMimeType();
		$sizeFile        = $request->file('photo')->getSize();
		$large           = strtolower( Auth::user()->id.time().str_random(100).'.'.$extension );
		$medium          = strtolower( Auth::user()->id.time().str_random(100).'.'.$extension );
		$small           = strtolower( Auth::user()->id.time().str_random(100).'.'.$extension );
		$preview         = strtolower( str_slug( $request->title, '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );
		$thumbnail       = strtolower( str_slug( $request->title, '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );

		if($request->file('photo')->move($temp, $large) ) {

				set_time_limit(0);

				 $original = $temp.$large;
				 $width    = Helper::getWidth( $original );
				 $height   = Helper::getHeight( $original );

				if ( $width > $height ) {

					if( $width > 1280) : $_scale = 1280; else: $_scale = 900; endif;

					// PREVIEW
					$scale    = 850 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scale, $temp.$preview, $request->rotation );

					// Medium
					$scaleM   = $_scale / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleM, $temp.$medium, $request->rotation );

					// Small
					$scaleS   = 640 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleS, $temp.$small, $request->rotation );

					// Thumbnail
					$scaleT   = 280 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleT, $temp.$thumbnail, $request->rotation );

				} else {

					if( $width > 1280) : $_scale = 960; else: $_scale = 800; endif;

					// PREVIEW
					$scale    = 480 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scale, $temp.$preview, $request->rotation );

					// Medium
					$scaleM   = $_scale / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleM, $temp.$medium, $request->rotation );

					// Small
					$scaleS   = 480 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleS, $temp.$small, $request->rotation );

					// Thumbnail
					$scaleT   = 190 / $width;
					$uploaded = Helper::resizeImage( $original, $width, $height, $scaleT, $temp.$thumbnail, $request->rotation );

				}

				// Add Watermark on Images
				if($this->settings->show_watermark == '1') {
					Helper::watermark($temp.$preview, $watermarkSource);
				}

			}// End File

		} //<----- HASFILE PHOTO

		 if( !empty( $request->description ) ) {
					$description = Helper::checkTextDb($request->description);
				} else {
					$description = '';
				}

		// Exif Read Data
		$exif_data = @exif_read_data($temp.$large, 0, true);

		if( isset($exif_data['EXIF']['ISOSpeedRatings'][0]) ) {
			$ISO = 'ISO '.$exif_data['EXIF']['ISOSpeedRatings'][0];
		}

		if( isset($exif_data['EXIF']['ExposureTime']) ) {
			$ExposureTime = $exif_data['EXIF']['ExposureTime'].'s';
		}

		if( isset($exif_data['EXIF']['FocalLength']) ) {
			$FocalLength = round($exif_data['EXIF']['FocalLength'], 1).'mm';
		}

		if( isset($exif_data['COMPUTED']['ApertureFNumber']) ) {
			$ApertureFNumber = $exif_data['COMPUTED']['ApertureFNumber'];
		}

		if( !isset($FocalLength) ) {
			$FocalLength = '';
		}

		if( !isset($ExposureTime) ) {
			$ExposureTime = '';
		}

		if( !isset($ISO) ) {
			$ISO = '';
		}

		if( !isset($ApertureFNumber) ) {
			$ApertureFNumber = '';
		}

		$exif = $FocalLength.' '.$ApertureFNumber.' '.$ExposureTime. ' '.$ISO;

		if( isset($exif_data['IFD0']['Model']) ) {
			$camera = $exif_data['IFD0']['Model'];
		} else {
			$camera = '';
		}

		//=========== Colors
		$palette = Palette::fromFilename( url('public/temp/').'/'.$preview );

		$extractor = new ColorExtractor($palette);

		// it defines an extract method which return the most “representative” colors
		$colors = $extractor->extract(5);

		// $palette is an iterator on colors sorted by pixel count
		foreach($colors as $color) {

			$_color[]  = trim(Color::fromIntToHex($color), '#') ;
		}

           $colors_image = implode( ',', $_color);

		if( $this->settings->auto_approve_images == 'on' ) {
			$status = 'active';
		} else {
			$status = 'pending';
		}

		$token_id = str_random(200);

		$sql = new Images;
		$sql->thumbnail            = $thumbnail;
		$sql->preview              = $preview;
		$sql->title                = trim($request->title);
		$sql->description          = trim($description);
		$sql->categories_id        = $request->categories_id;
		$sql->user_id              = Auth::user()->id;
		$sql->status               = $status;
		$sql->token_id             = $token_id;
		$sql->tags                 = strtolower($request->tags);
		$sql->extension            = strtolower($extension);
		$sql->colors               = $colors_image;
		$sql->exif                 = trim($exif);
		$sql->camera               = $camera;
		$sql->how_use_image        = $request->how_use_image;
		$sql->attribution_required = $request->attribution_required;
		$sql->original_name        = $originalName;
		$sql->price                = $request->price ? $request->price : 0;
		$sql->item_for_sale        = $request->item_for_sale ? $request->item_for_sale: 'free';
		$sql->vector               = $vectorFile;

		$sql->save();

		// ID INSERT
		$imageID = $sql->id;

		// Save Vector DB
		if($request->hasFile('file')) {
			$stockVector             = new Stock;
			$stockVector->images_id  = $imageID;
			$stockVector->name       = $fileVector;
			$stockVector->type       = 'vector';
			$stockVector->extension  = $extensionVector;
			$stockVector->resolution = '';
			$stockVector->size       = $sizeFileVector;
			$stockVector->token      = $token_id;
			$stockVector->save();
		}


		// INSERT STOCK IMAGES

		$lResolution = list($w, $h) = getimagesize($temp.$large);
		$lSize     = Helper::formatBytes(filesize($temp.$large), 1);

		$mResolution = list($_w, $_h) = getimagesize($temp.$medium);
		$mSize     = Helper::formatBytes(filesize($temp.$medium), 1);

		$smallResolution = list($__w, $__h) = getimagesize($temp.$small);
		$smallSize       = Helper::formatBytes(filesize($temp.$small), 1);



	$stockImages = [
			['name' => $large, 'type' => 'large', 'resolution' => $w.'x'.$h, 'size' => $lSize ],
			['name' => $medium, 'type' => 'medium', 'resolution' => $_w.'x'.$_h, 'size' => $mSize ],
			['name' => $small, 'type' => 'small', 'resolution' => $__w.'x'.$__h, 'size' => $smallSize ],
		];

		foreach ($stockImages as $key) {

			$stock             = new Stock;
			$stock->images_id  = $imageID;
			$stock->name       = $key['name'];
			$stock->type       = $key['type'];
			$stock->extension  = $extension;
			$stock->resolution = $key['resolution'];
			$stock->size       = $key['size'];
			$stock->token      = $token_id;
			$stock->save();

		}

		 	\File::copy($temp.$preview, $path_preview.$preview);
			\File::delete($temp.$preview);

			\File::copy($temp.$thumbnail, $path_thumbnail.$thumbnail);
			\File::delete($temp.$thumbnail);

			\File::copy($temp.$small, $path_small.$small);
			\File::delete($temp.$small);

			\File::copy($temp.$medium, $path_medium.$medium);
			\File::delete($temp.$medium );

			\File::copy($temp.$large, $path_large.$large);
			\File::delete($temp.$large);

		//\Session::flash('success_message',trans('admin.success_add'));

		return response()->json([
			        'success' => true,
			        'target' => url('photo',$imageID),
			    ]);

	}//<--- End Method

	/**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function show($id, $slug = null ) {

		$response = Images::findOrFail($id);

		if( Auth::check() && $response->user_id != Auth::user()->id && $response->status == 'pending' && Auth::user()->role != 'admin' ) {
			abort(404);
		} else if(Auth::guest() && $response->status == 'pending'){
			abort(404);
		}

		$uri = $this->request->path();

		if( str_slug( $response->title ) == '' ) {

				$slugUrl  = '';
			} else {
				$slugUrl  = '/'.str_slug( $response->title );
			}

			$url_image = 'photo/'.$response->id.$slugUrl;

			//<<<-- * Redirect the user real page * -->>>
			$uriImage     =  $this->request->path();
			$uriCanonical = $url_image;

			if( $uriImage != $uriCanonical ) {
				return redirect($uriCanonical);
			}

			//<--------- * Visits * ---------->
			$user_IP = request()->ip();
			$date = time();

			if( Auth::check() ) {
				// SELECT IF YOU REGISTERED AND VISITED THE PUBLICATION
				$visitCheckUser = $response->visits()->where('user_id',Auth::user()->id)->first();

				if( !$visitCheckUser && Auth::user()->id != $response->user()->id ) {
					$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = Auth::user()->id;
					$visit->ip       = $user_IP;
					$visit->save();
				}

			} else {

				// IF YOU SELECT "UNREGISTERED" ALREADY VISITED THE PUBLICATION
				$visitCheckGuest = $response->visits()->where('user_id',0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if( $visitCheckGuest )	{
				  $dateGuest = strtotime( $visitCheckGuest->date  ) + ( 7200 ); // 2 Hours

			}

				if( empty( $visitCheckGuest->ip )  ) {
				   	$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   } else if( $dateGuest < $date ) {
			   		$visit = new Visits;
					$visit->images_id = $response->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   }

			}//<--------- * Visits * ---------->

			if (Auth::check()) {

				// FOLLOW ACTIVE
			 	$followActive = Followers::where( 'follower', Auth::user()->id )
			 	->where('following',$response->user()->id)
				->where('status', '1')
			 	->first();

	       if ($followActive) {
	       	  $textFollow   = trans('users.following');
						$icoFollow    = '-ok';
						$activeFollow = 'btnFollowActive';
	       } else {
	       		$textFollow   = trans('users.follow');
						$icoFollow    = '-plus';
						$activeFollow = '';
	       }

				   // LIKE ACTIVE
				   $likeActive = Like::where( 'user_id', Auth::user()->id )
				   ->where('images_id',$response->id)
				   ->where('status','1')
				   ->first();

			       if ($likeActive) {
			       	  $textLike   = trans('misc.unlike');
							  $icoLike    = 'fa fa-heart';
							  $statusLike = 'active';
			       } else {
			       		$textLike   = trans('misc.like');
							  $icoLike    = 'fa fa-heart-o';
								$statusLike = '';
			       }

				   // ADD TO COLLECTION
				   $collections = Collections::where('user_id',Auth::user()->id)->orderBy('id','asc')->get();

			 }//<<<<---- *** END AUTH ***

				// All Images resolutions
				$stockImages = $response->stock;

				$resolution = explode('x', Helper::resolutionPreview($stockImages[2]->resolution));
				$previewWidth = $resolution[0];
				$previewHeight = $resolution[1];

				// Similar Photos
				$arrayTags  = explode(",",$response->tags);
				$countTags = count( $arrayTags );

				$images = Images::where('categories_id',$response->categories_id)
				->whereStatus('active')
				->where(function($query) use ($arrayTags, $countTags){
					for( $k = 0; $k < $countTags; ++$k ){
						 $query->orWhere('tags', 'LIKE', '%'.$arrayTags[$k].'%');
					}
				} )
				->where('id', '<>',$response->id)
				->orderByRaw('RAND()')
				->take(5)
				->get();

				// Comments
				$comments_sql = $response->comments()->where('status','1')->orderBy('date', 'desc')->paginate(10);


    	return view('images.show')->with([
				 'response' => $response,
				 'textFollow' => $textFollow ?? null,
				 'icoFollow' => $icoFollow ?? null,
				 'activeFollow' => $activeFollow ?? null,
				 'textLike'   => $textLike ?? null,
				 'icoLike' => $icoLike ?? null,
				 'statusLike' => $statusLike ?? null,
				 'collections' => $collections ?? null,
				 'stockImages' => $stockImages,
				 'previewWidth' => $previewWidth,
				 'previewHeight' => $previewHeight,
				 'images' => $images,
				 'comments_sql' => $comments_sql
			]
			);

	}//<--- End Method

	/**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
	public function edit($id) {

		$data = Images::findOrFail($id);

		if( $data->user_id != Auth::user()->id ) {
			abort('404');
		}

    	return view('images.edit')->withData($data);

	}//<--- End Method

	/**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function update(Request $request) {

    $image = Images::findOrFail($request->id);

	if( $image->user_id != Auth::user()->id ) {
		return redirect('/');
	}

	$input = $request->all();

	$input['tags'] = Helper::cleanStr($input['tags']);

	if(strlen($input['tags']) == 1) {
		return redirect()->back()
				->withErrors(trans('validation.required', ['attribute' => trans('misc.tags')]));
	}

	if($image->item_for_sale == 'sale' || $request->item_for_sale == 'sale') {
		$input['item_for_sale'] = 'sale';
	} else {
		$input['item_for_sale'] = 'free';
	}

	     $validator = $this->validator($input, $request->id);

			 if ($validator->fails()) {
          return redirect()->back()
 						 ->withErrors($validator)
 						 ->withInput();
 					 }

    $image->fill($input)->save();

    \Session::flash('success_message', trans('admin.success_update'));

    return redirect('edit/photo/'.$image->id);

	}//<--- End Method


	/**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
	public function destroy(Request $request){

	  $image = Images::find($request->id);

	  if ($image->user_id != Auth::user()->id) {
			return redirect('/');
		}

		// Delete Notification
		$notifications = Notifications::where('destination',$request->id)
			->where('type', '2')
			->orWhere('destination',$request->id)
			->where('type', '3')
			->orWhere('destination',$request->id)
			->where('type', '4')
			->get();

		if(  isset( $notifications ) ){
			foreach($notifications as $notification){
				$notification->delete();
			}
		}

		// Collections Images
	$collectionsImages = CollectionsImages::where('images_id', '=', $request->id)->get();
	 if( isset( $collectionsImages ) ){
			foreach($collectionsImages as $collectionsImage){
				$collectionsImage->delete();
			}
		}

		// Images Reported
		$imagesReporteds = ImagesReported::where('image_id', '=', $request->id)->get();
		 if( isset( $imagesReporteds ) ){
				foreach($imagesReporteds as $imagesReported){
					$imagesReported->delete();
				}
			}

		//<---- ALL RESOLUTIONS IMAGES
		$stocks = Stock::where('images_id', '=', $request->id)->get();

		foreach($stocks as $stock) {

			// Delete Stock
			Storage::delete(config('path.uploads').$stock->type.'/'.$stock->name);

			// Delete Stock Vector
			Storage::delete(config('path.files').$stock->name);

			$stock->delete();

		}//<--- End foreach

		// Delete preview
		Storage::delete(config('path.preview').$image->preview);

		// Delete thumbnail
		Storage::delete(config('path.thumbnail').$image->thumbnail);

		$image->delete();

      return redirect(Auth::user()->username);

	}//<--- End Method

	public function download($token_id) {

		$type = $this->request->type;

		$image = Images::where('token_id', $token_id)->where('item_for_sale', 'free')->firstOrFail();

		if(isset($image)) {
			$getImage = Stock::where('images_id',$image->id)->where('type','=',$type)->firstOrFail();
		}

		if(isset($getImage)) {

			// Download Check User
			$user_IP = request()->ip();
			$date = time();

			if(Auth::check()){

			$downloadCheckUser = $image->downloads()->where('user_id', Auth::user()->id)->first();

				if( !$downloadCheckUser && Auth::user()->id != $image->user()->id ) {
							$download            = new Downloads;
							$download->images_id = $image->id;
							$download->user_id   = Auth::user()->id;
							$download->ip        = $user_IP;
							$download->save();
				}
			}// Auth check

			else {

				// IF YOU SELECT "UNREGISTERED" ALREADY DOWNLOAD THE IMAGE
				$downloadCheckUser = $image->downloads()->where('user_id', 0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if( $downloadCheckUser )	{
				  $dateGuest = strtotime( $downloadCheckUser->date  ) + ( 7200 ); // 2 Hours

			}

				if( empty( $downloadCheckUser->ip )  ) {
				   	$download            = new Downloads;
					$download->images_id = $image->id;
					$download->user_id   = 0;
					$download->ip        = $user_IP;
					$download->save();
			   } else if( $dateGuest < $date ) {
			   		$download            = new Downloads;
					$download->images_id = $image->id;
					$download->user_id   = 0;
					$download->ip        = $user_IP;
					$download->save();
			   }

			}//<--------- * Visits * ---------->
			//<<<<---/ Download Check User

			if($type != 'vector') {
				$pathFile = config('path.uploads').$type.'/'.$getImage->name;
				$resolution = $getImage->resolution;
			} else {
				$pathFile = config('path.files').$getImage->name;
				$resolution = trans('misc.vector_graphic');
			}

			$headers = [
				'Content-Type:' => ' image/'.$image->extension,
				'Cache-Control' => 'no-cache, no-store, must-revalidate',
				'Pragma' => 'no-cache',
				'Expires' => '0'
			];

			return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);
		}
	}//<--- End Method

	public function report(Request $request){

		$data = ImagesReported::firstOrNew(['user_id' => Auth::user()->id, 'image_id' => $request->id]);

		if( $data->exists ) {
			\Session::flash('noty_error','error');
			return redirect()->back();
		} else {

			$data->reason = $request->reason;
			$data->save();
			\Session::flash('noty_success','success');
			return redirect()->back();
		}

	}//<--- End Method

	public function purchase($token_id) {

		$type = strtolower($this->request->type);
		$license = strtolower($this->request->license);
		$urlDashboardUser = url('user/dashboard/purchases');

		if(url()->previous() == $urlDashboardUser && !$this->request->downloadAgain) {
			abort(404);
		}

		$image = Images::where('token_id', $token_id)->firstOrFail();

		// Validate Licenses and Type
		$licensesArray = ['regular', 'extended'];
		$typeArray     = ['small', 'medium', 'large', 'vector'];

		// License
		if (! in_array($license, $licensesArray) && Auth::user()->id != $image->user()->id) {
			 abort(404);
		}

		// Type
		if (! in_array($type, $typeArray) && Auth::user()->id != $image->user()->id) {
			 abort(404);
		}

		if ($license == 'extended') {
			 $image->price = ($image->price*10);
		}

		switch($type) {
		case 'small':
			$priceItem   = $image->price;
			break;
		case 'medium':
			$priceItem   = ($image->price * 2);
			break;
		case 'large':
			$priceItem   = ($image->price * 3);
			break;
		case 'vector':
				$priceItem  = ($image->price * 4);
				break;
			}

		if($this->settings->sell_option == 'off' && Auth::user()->id != $image->user()->id) {
			return back()->withPurchaseNotAllowed(trans('misc.purchase_not_allowed'));
		}

		if(isset($image)) {
			$getImage = Stock::where('images_id', $image->id)->where('type', '=', $type)->firstOrFail();
		}

		// Download image from the user's Dashboard
		if ($this->request->downloadAgain) {
			return $this->downloadAgain($image, $getImage);
		}

		if(isset($getImage)) {

			if(Auth::user()->funds < $priceItem && Auth::user()->id != $image->user()->id) {
				return back()->withErrorPurchase(trans('misc.not_enough_funds'));
			}

			// Verify Purchase of the User
			//$verifyPurchaseUser = $image->purchases()->where('user_id', Auth::user()->id)->where('type', '=', $type)->first();

			// Earnings Net Seller
			$earningNetSeller = $priceItem - ($priceItem * $this->settings->fee_commission / 100);

			// Earnings Net Admin
			$earningNetAdmin = ($priceItem - $earningNetSeller);

			if(Auth::user()->id != $image->user()->id) {

						$user_IP = request()->ip();

						$purchase                     = new Purchases;
						$purchase->images_id          = $image->id;
						$purchase->user_id            = Auth::user()->id;
						$purchase->price              = $priceItem;
						$purchase->earning_net_seller = $earningNetSeller;
						$purchase->earning_net_admin  = $earningNetAdmin;
						$purchase->type               = $type;
						$purchase->license            = $license;
						$purchase->order_id	          = substr(strtolower( md5( microtime() . mt_rand( 1000, 9999 ) ) ), 0, 15 );
						$purchase->purchase_code      = implode( '-', str_split( substr( strtolower( md5( time() . mt_rand( 1000, 9999 ) ) ), 0, 27 ), 5 ) );
						$purchase->save();

						// Download
						$download            = new Downloads;
						$download->images_id = $image->id;
						$download->user_id   = Auth::user()->id;
						$download->ip        = $user_IP;
						$download->save();

						// Send Notification //destination, author, type, target
							Notifications::send($image->user()->id, Auth::user()->id, '5', $image->id);

						//Subtract user funds
						User::find(Auth::user()->id)->decrement('funds', $priceItem);

						//Add user balance
						User::find($image->user()->id)->increment('balance', $earningNetSeller);
			}
			//<<<<---/  Verify Purchase of the User

			if($type != 'vector') {
				$pathFile = config('path.uploads').$type.'/'.$getImage->name;
				$resolution = $getImage->resolution;
			} else {
				$pathFile = config('path.files').$getImage->name;
				$resolution = trans('misc.vector_graphic');
			}

			$headers = [
				'Content-Type:' => ' image/'.$image->extension,
				'Cache-Control' => 'no-cache, no-store, must-revalidate',
				'Pragma' => 'no-cache',
				'Expires' => '0'
			];

			return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);
		}// $getImage

	}//<--- End Method

	public function downloadAgain($image, $getImage)
	{

		$verifyPurchaseUserAgain = $image->purchases()
				->where('user_id', Auth::user()->id)
				->where('images_id', $image->id)
				->where('type', '=', $this->request->type)
				->where('license', '=', $this->request->license)
				->first();

				if(!$verifyPurchaseUserAgain) {
					abort(404);
				}

				if($this->request->type != 'vector') {
					$pathFile = config('path.uploads').$this->request->type.'/'.$getImage->name;
					$resolution = $getImage->resolution;
				} else {
					$pathFile = config('path.files').$getImage->name;
					$resolution = trans('misc.vector_graphic');
				}

				$headers = [
					'Content-Type:' => ' image/'.$image->extension,
					'Cache-Control' => 'no-cache, no-store, must-revalidate',
					'Pragma' => 'no-cache',
					'Expires' => '0'
				];

				return Storage::download($pathFile, $image->title.' - '.$resolution.'.'.$getImage->extension, $headers);

	}//<--- End Method

	public function create()
	{
		if(Auth::guest()) {
		 return response()->json([
						 'session_null' => true,
						 'success' => false,
				 ]);
		}

		//======= EXIF DATA
		$exif_data  = @exif_read_data($this->request->file('photo'), 0, true);
		if (isset($exif_data['COMPUTED']['ApertureFNumber'])) : $ApertureFNumber = $exif_data['COMPUTED']['ApertureFNumber']; else: $ApertureFNumber = ''; endif;

		if (isset($exif_data['EXIF']['ISOSpeedRatings'][0]))
			: $ISO = 'ISO '.$exif_data['EXIF']['ISOSpeedRatings'][0];
			elseif(!isset($exif_data['EXIF']['ISOSpeedRatings'][0]) && isset($exif_data['EXIF']['ISOSpeedRatings']))
			: $ISO = 'ISO '.$exif_data['EXIF']['ISOSpeedRatings'];
		else: $ISO = '';
	endif;

		if (isset($exif_data['EXIF']['ExposureTime'])) : $ExposureTime = $exif_data['EXIF']['ExposureTime']; else: $ExposureTime = ''; endif;
		if (isset($exif_data['EXIF']['FocalLength'])) : $FocalLength = $exif_data['EXIF']['FocalLength']; else: $FocalLength = ''; endif;
		if (isset($exif_data['IFD0']['Model'])) : $camera = $exif_data['IFD0']['Model']; else: $camera = ''; endif;
		$exif = $FocalLength.' '.$ApertureFNumber.' '.$ExposureTime. ' '.$ISO;
		//dd($exif_data);

		$pathFiles      = config('path.files');
		$pathLarge      = config('path.large');
		$pathPreview    = config('path.preview');
		$pathMedium     = config('path.medium');
		$pathSmall      = config('path.small');
		$pathThumbnail  = config('path.thumbnail');
		$watermarkSource = url('public/img/watermark.png');

		$input = $this->request->all();

		$input['tags'] = Helper::cleanStr($input['tags']);
		$tags = $input['tags'];

		if(strlen($tags) == 1) {
			return response()->json([
					'success' => false,
					'errors' => ['error' => trans('validation.required', ['attribute' => trans('misc.tags')])],
			]);
		}

		$validator = $this->validator($input);

		if ($validator->fails()) {
			return response()->json([
					'success' => false,
					'errors' => $validator->getMessageBag()->toArray(),
			]);
	} //<-- Validator

		 $vectorFile = '';

		 // File Vector
		 if ($this->request->hasFile('file')) {

			 $file           = $this->request->file('file');
			 $extensionVector = strtolower($file->getClientOriginalExtension());
			 $fileVector      = strtolower(Auth::user()->id.time().str_random(40).'.'.$extensionVector);
			 $sizeFileVector  = Helper::formatBytes($file->getSize(), 1);

		 $valid_formats = ['ai', 'psd', 'eps', 'svg'];

		 if (! in_array($extensionVector, $valid_formats)) {
				 return response()->json([
						 'success' => false,
						 'errors' => ['error_file' => trans('misc.file_validation', ['values' => 'AI, EPS, PSD, SVG'])],
				 ]);
		 }

		 if ($extensionVector == 'ai') {
			 $mime = ['application/illustrator', 'application/postscript', 'application/vnd.adobe.illustrator', 'application/pdf'];

		 } elseif ($extensionVector == 'eps') {
			 $mime = ['application/postscript', 'image/x-eps', 'application/pdf', 'application/octet-stream'];

		 } elseif ($extensionVector == 'psd') {
			 $mime = ['application/photoshop', 'application/x-photoshop', 'image/photoshop', 'image/psd', 'image/vnd.adobe.photoshop', 'image/x-photoshop', 'image/x-psd'];

		 } elseif ($extensionVector == 'svg') {
			 $mime = ['image/svg+xml'];
		 }

		 if (! in_array($file->getMimeType(), $mime)) {
				 return response()->json([
						 'success' => false,
						 'errors' => ['error_file' => trans('misc.file_validation', ['values' => 'AI, EPS, PSD, SVG'])],
				 ]);
		 }

		 $vectorFile = 'yes';

	 }

		$photo          = $this->request->file('photo');
		$fileSizeLarge  = Helper::formatBytes($photo->getSize(), 1);
		$extension      = $photo->getClientOriginalExtension();
		$originalName   = Helper::fileNameOriginal($photo->getClientOriginalName());
		$widthHeight    = getimagesize($photo);
		$large          = strtolower(Auth::user()->id.time().str_random(100).'.'.$extension );
		$medium         = strtolower(Auth::user()->id.time().str_random(100).'.'.$extension );
		$small          = strtolower(Auth::user()->id.time().str_random(100).'.'.$extension );
		$preview        = strtolower(str_slug($this->request->title, '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );
		$thumbnail      = strtolower(str_slug($this->request->title, '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );

		$watermark   = Image::make($watermarkSource);
		$x = 0;
		ini_set('memory_limit', '512M');

				 $width    = $widthHeight[0];
				 $height   = $widthHeight[1];

				if ($width > $height) {

					if ($width > 1280) : $_scale = 1280; else: $_scale = 900; endif;
							$previewWidth = 850 / $width;
							$mediumWidth = $_scale / $width;
							$smallWidth = 640 / $width;
							$thumbnailWidth = 280 / $width;
				} else {

					if ($width > 1280) : $_scale = 960; else: $_scale = 800; endif;
							$previewWidth = 480 / $width;
							$mediumWidth = $_scale / $width;
							$smallWidth = 480 / $width;
							$thumbnailWidth = 190 / $width;
				}

					//======== PREVIEW
					$scale    = $previewWidth;
					$widthPreview = ceil($width * $scale);

					$imgPreview  = Image::make($photo)->resize($widthPreview, null, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode($extension);

					if ($this->settings->show_watermark == '1') {
						// Insert Watermark
						while ($x < $imgPreview->width()) {
						    $y = 0;

						    while($y < $imgPreview->height()) {
						        $imgPreview->insert($watermarkSource, 'top-left', $x, $y);
						        $y += $watermark->height();
						    }

						    $x += $watermark->width();
						}
						$imgPreview->save($preview)->destroy();

						if (\File::exists($preview)) {
							\File::delete($preview);
						}
					}

					//======== Medium
					$scaleM  = $mediumWidth;
					$widthMedium = ceil($width * $scaleM);

					$imgMedium  = Image::make($photo)->resize($widthMedium, null, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode($extension);

					//======== Small
					$scaleSmall  = $smallWidth;
					$widthSmall = ceil($width * $scaleSmall);

					$imgSmall  = Image::make($photo)->resize($widthSmall, null, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode($extension);

					//======== Thumbnail
					$scaleThumbnail  = $thumbnailWidth;
					$widthThumbnail = ceil($width * $scaleThumbnail);

					$imgThumbnail  = Image::make($photo)->resize($widthThumbnail, null, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode($extension);


		//======== Large Image
		$photo->storePubliclyAs($pathLarge, $large);

		//========  Preview Image
		Storage::put($pathPreview.$preview, $imgPreview, 'public');
		//Storage::move($preview, $pathPreview.$preview);
		$url = Storage::url($pathPreview.$preview);

		//======== Medium Image
		Storage::put($pathMedium.$medium, $imgMedium, 'public');
		//Storage::move($medium, $pathMedium.$medium);
		$urlMedium = Storage::url($pathMedium.$medium);

		//======== Small Image
		Storage::put($pathSmall.$small, $imgSmall, 'public');
		//Storage::move($small, $pathSmall.$small);
		$urlSmall = Storage::url($pathSmall.$small);

		//======== Thumbnail Image
		Storage::put($pathThumbnail.$thumbnail, $imgThumbnail, 'public');
		//Storage::move($thumbnail, $pathThumbnail.$thumbnail);

		//=========== Colors
		$palette   = Palette::fromFilename($urlSmall);
		$extractor = new ColorExtractor($palette);

		// it defines an extract method which return the most “representative” colors
		$colors = $extractor->extract(5);

		// $palette is an iterator on colors sorted by pixel count
		foreach($colors as $color) {

			$_color[]  = trim(Color::fromIntToHex($color), '#') ;
		}

		$colors_image = implode( ',', $_color);

		if (! empty($this->request->description)) {
				 $description = Helper::checkTextDb($this->request->description);
			 } else {
				 $description = '';
			 }

		if ($this->settings->auto_approve_images == 'on') {
			$status = 'active';
		} else {
			$status = 'pending';
		}

		$token_id = str_random(200);

		$sql = new Images;
		$sql->thumbnail            = $thumbnail;
		$sql->preview              = $preview;
		$sql->title                = trim($this->request->title);
		$sql->description          = trim($description);
		$sql->categories_id        = $this->request->categories_id;
		$sql->user_id              = Auth::user()->id;
		$sql->status               = $status;
		$sql->token_id             = $token_id;
		$sql->tags                 = strtolower($tags);
		$sql->extension            = strtolower($extension);
		$sql->colors               = $colors_image;
		$sql->exif                 = trim($exif);
		$sql->camera               = $camera;
		$sql->how_use_image        = $this->request->how_use_image;
		$sql->attribution_required = $this->request->attribution_required;
		$sql->original_name        = $originalName;
		$sql->price                = $this->request->price ? $this->request->price : 0;
		$sql->item_for_sale        = $this->request->item_for_sale ? $this->request->item_for_sale : 'free';
		$sql->vector               = $vectorFile;
		$sql->save();

		// ID INSERT
		$imageID = $sql->id;

		// Save Vector DB
		if($this->request->hasFile('file')) {

				$file->storePubliclyAs($pathFiles, $fileVector);

				$stockVector             = new Stock;
				$stockVector->images_id  = $imageID;
				$stockVector->name       = $fileVector;
				$stockVector->type       = 'vector';
				$stockVector->extension  = $extensionVector;
				$stockVector->resolution = '';
				$stockVector->size       = $sizeFileVector;
				$stockVector->token      = $token_id;
				$stockVector->save();
		}

		// INSERT STOCK IMAGES
		$lResolution = list($w, $h) = $widthHeight;
		$lSize       = $fileSizeLarge;

		$mResolution = list($_w, $_h) = getimagesize($urlMedium);
		$mSize      = Helper::getFileSize($urlMedium);

		$smallResolution = list($__w, $__h) = getimagesize($urlSmall);
		$smallSize       = Helper::getFileSize($urlSmall);

	$stockImages = [
			['name' => $large, 'type' => 'large', 'resolution' => $w.'x'.$h, 'size' => $lSize ],
			['name' => $medium, 'type' => 'medium', 'resolution' => $_w.'x'.$_h, 'size' => $mSize ],
			['name' => $small, 'type' => 'small', 'resolution' => $__w.'x'.$__h, 'size' => $smallSize ],
		];

		foreach ($stockImages as $key) {
			$stock             = new Stock;
			$stock->images_id  = $imageID;
			$stock->name       = $key['name'];
			$stock->type       = $key['type'];
			$stock->extension  = $extension;
			$stock->resolution = $key['resolution'];
			$stock->size       = $key['size'];
			$stock->token      = $token_id;
			$stock->save();

		}

		return response()->json([
			        'success' => true,
			        'target' => url('photo', $imageID),
			    ]);
	}

}
