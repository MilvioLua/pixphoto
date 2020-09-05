<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\AdminSettings;
use App\Models\User;
use App\Models\Stock;
use App\Models\Images;
use App\Helper;
use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;
use Illuminate\Support\Facades\Validator;
use Image;

trait Upload {

  public function __construct(AdminSettings $settings, Request $request) {
   $this->settings = $settings::first();
   $this->request = $request;
 }

 protected function validator(array $data, $type)
 {
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

  if ($type == 'bulk') {
    $max_lenght_title = 255;
  } else {
    $max_lenght_title = $this->settings->title_length;
  }

  $messages = array (
  'photo.required' => trans('misc.please_select_image'),
  "photo.max"   => trans('misc.max_size').' '.Helper::formatBytes( $sizeAllowed, 1 ),
  "price.required_if" => trans('misc.price_required'),
  'price.min' => trans('misc.price_minimum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),
  'price.max' => trans('misc.price_maximum_sale'.$currencyPosition, ['symbol' => $this->settings->currency_symbol, 'code' => $this->settings->currency_code]),

);

  // Create Rules
  return Validator::make($data, [
   'photo'       => 'required|mimes:jpg,gif,png,jpe,jpeg|dimensions:min_width='.$dimensions[0].',min_height='.$dimensions[1].'|max:'.$this->settings->file_size_allowed.'',
      'title'       => 'required|min:3|max:'.$max_lenght_title.'',
        'description' => 'min:2|max:'.$this->settings->description_length.'',
      'tags'        => 'required',
      'price' => 'required_if:item_for_sale,==,sale|integer|min:'.$this->settings->min_sale_amount.'|max:'.$this->settings->max_sale_amount.'',
      'file' => 'max:'.$this->settings->file_size_allowed_vector.'',
    ], $messages);
  }

// Store Image
 public function upload($type)
 {

   if ($this->settings->who_can_upload == 'admin' && Auth::user()->role != 'admin') {
     return response()->json([
         'success' => false,
         'errors' => ['error' => trans('misc.error_upload')],
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
   $watermarkSource = url('public/img', $this->settings->watermark);

   $input = $this->request->all();

   if (! $this->request->price) {
     $price = 0;
   } else {
     $price = $input['price'];
   }

   // Bulk Upload
   if ($type == 'bulk') {

     $_type = true;
     $replace = ['+','-','_','.','*'];
     $input['title']  = str_replace($replace, ' ', Helper::fileNameOriginal($this->request->file('photo')->getClientOriginalName()));

     $tags = explode(' ', $input['title']);

     if ($this->request->tags == '') {
		 	   $input['tags'] = $tags[0];
		 }

     // Set price min
     if ($this->request->item_for_sale == 'sale'
          && $this->request->price == ''
          || $this->request->item_for_sale == 'sale'
          && $this->request->price < $this->settings->min_sale_amount
        ) {
		 	   $price = $this->settings->min_sale_amount;
         $input['price'] = $this->settings->min_sale_amount;
		 } else if($this->request->item_for_sale == 'sale'
      && $this->request->price == ''
      || $this->request->item_for_sale == 'sale'
      && $this->request->price > $this->settings->max_sale_amount) {
       $price = $this->settings->max_sale_amount;
       $input['price'] = $this->settings->max_sale_amount;
     }
   }

   $input['tags'] = Helper::cleanStr($input['tags']);
   $tags = $input['tags'];

   if (strlen($tags) == 1) {
     return response()->json([
         'success' => false,
         'errors' => ['error' => trans('validation.required', ['attribute' => trans('misc.tags')])],
     ]);
   }

   $validator = $this->validator($input, $type);

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
   $preview        = strtolower(str_slug($input['title'], '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );
   $thumbnail      = strtolower(str_slug($input['title'], '-').'-'.Auth::user()->id.time().str_random(10).'.'.$extension );

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
   $url = Storage::url($pathPreview.$preview);

   //======== Medium Image
   Storage::put($pathMedium.$medium, $imgMedium, 'public');
   $urlMedium = Storage::url($pathMedium.$medium);

   //======== Small Image
   Storage::put($pathSmall.$small, $imgSmall, 'public');
   $urlSmall = Storage::url($pathSmall.$small);

   //======== Thumbnail Image
   Storage::put($pathThumbnail.$thumbnail, $imgThumbnail, 'public');

   //=========== Colors
   $palette   = Palette::fromFilename($urlSmall);
   $extractor = new ColorExtractor($palette);

   // it defines an extract method which return the most “representative” colors
   $colors = $extractor->extract(5);

   // $palette is an iterator on colors sorted by pixel count
   foreach ($colors as $color) {

     $_color[] = trim(Color::fromIntToHex($color), '#') ;
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
   $sql->title                = trim($input['title']);
   $sql->description          = trim($description);
   $sql->categories_id        = $this->request->categories_id;
   $sql->user_id              = Auth::user()->id;
   $sql->status               = $status;
   $sql->token_id             = $token_id;
   $sql->tags                 = mb_strtolower($tags);
   $sql->extension            = strtolower($extension);
   $sql->colors               = $colors_image;
   $sql->exif                 = trim($exif);
   $sql->camera               = $camera;
   $sql->how_use_image        = $this->request->how_use_image;
   $sql->attribution_required = $this->request->attribution_required;
   $sql->original_name        = $originalName;
   $sql->price                = $price;
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

   if ($type == 'normal') {
     return response()->json([
 			        'success' => true,
 			        'target' => url('photo', $imageID),
 			    ]);
   } else {
     return 'success';
   }
  }
}
