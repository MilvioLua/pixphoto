@foreach( $data as $collection )

<?php $image = $collection->collection_images()->take(1)->first();

 if( $collection->collection_images()->count() != 0 ) {
 	$imageCollection = Storage::url(config('path.thumbnail').$image->images()->thumbnail);

  // Width and Height Large
  $imageLarge = App\Models\Stock::whereImagesId($image->images()->id)->whereType('large')->pluck('resolution')->first();
  $resolution = explode('x', App\Helper::resolutionPreview($imageLarge, true));
  $newWidth = $resolution[0];
  $newHeight = $resolution[1];

 } else {
 	$imageCollection = url('public/img', $settings->img_collection);
  $newWidth = 280;
  $newHeight = 160;
 }
 ?>

<!-- Start Item -->
<a data-w="{{$newWidth}}" data-h="{{$newHeight}}" href="{{ url($collection->user()->username.'/collection', $collection->id) }}" class="item hovercard">
	<!-- hover-content -->
	<span class="hover-content">
			<h5 class="text-overflow title-hover-content" title="{{$collection->title}}">
			 @if( $collection->type == 'private' ) <span class="label label-default">{{trans('misc.private')}}</span>	 @endif {{$collection->title}}
				</h5>

			<h5 class="text-overflow author-label mg-bottom-xs" title="{{$collection->user()->username}}">
				<img src="{{ Storage::url(config('path.avatar').$collection->user()->avatar) }}" alt="User" class="img-circle" style="width: 20px; height: 20px; display: inline-block; margin-right: 5px;">
				<em>{{$collection->user()->username}}</em>
				</h5>
				<span class="timeAgo btn-block date-color text-overflow" data="{{ date('c', strtotime( $collection->created_at )) }}"></span>

			<span class="sub-hover">
				<span class="myicon-right"><i class="icon icon-Picture myicon-right"></i> {{$collection->collection_images()->count()}}</span>
			</span><!-- Span Out -->
	</span><!-- hover-content -->

		<img src="{{ $imageCollection }}" />
</a><!-- End Item -->
@endforeach
