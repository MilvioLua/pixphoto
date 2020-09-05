@foreach( $data as $collection )

<?php $image = $collection->collection_images()->take(1)->first();

 if( $collection->collection_images()->count() != 0 ) {
 	$imageCollection = $image->images()->thumbnail;
 } else {
 	$imageCollection = 'img-collection.jpg';
 }

 ?>

<!-- Start Item -->
<a href="{{ url($collection->user()->username.'/collection', $collection->id) }}" class="item hovercard" data-w="{{App\Helper::getWidth('public/uploads/thumbnail/'.$imageCollection)}}" data-h="{{App\Helper::getHeight('public/uploads/thumbnail/'.$imageCollection)}}">
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

		<img src="{{ Storage::url(config('path.thumbnail').$imageCollection) }}" />
</a><!-- End Item -->
@endforeach
