	@foreach( $comments_sql as $comment )
	 <div class="col-thumb">
	      <div class="media media-comments position-relative" id="comment{{$comment->id}}">
			<span class="pull-left">
				<a href="{{url($comment->user()->username)}}">
				<img width="50" height="50" class="media-object img-circle" src="{{Storage::url(config('path.avatar').$comment->user()->avatar)}}">
			</a>
			</span>
			<div class="media-body media-body-comments">
				<h4 class="media-heading col-thumb">
					<a class="text-decoration-none class-montserrat" href="{{url($comment->user()->username)}}">@if( $comment->user()->name != '' ) {{e($comment->user()->name)}} @else {{$comment->user()->username}} @endif</a>
						</h4>
				<p class="comments-p mentions-links">{!! App\Helper::checkText( $comment->reply ) !!}</p>
				<div class="btn-block margin-top-5">
					<small class="timeAgo small-comment" data="{{ date('c', strtotime( $comment->date )) }}"></small>

@if(Auth::check())

<?php

$comment_like = App\Models\CommentsLikes::where('user_id', '=', Auth::user()->id)
->where('comment_id', '=', $comment->id)
->where('status','1')->first();

 if( isset( $comment_like ) ) {
	$activeLike = 'fa fa-heart';
 } else {
	$activeLike = 'fa fa-heart-o';
	}
?>


	<span class="likeComment" data-id="{{$comment->id}}">
		<i class="{{$activeLike}} myicon-right"></i>
	</span>

	@if( $comment->user_id == Auth::user()->id )
	<span class="deleteComment" data-id="{{$comment->id}}" title="{{trans('misc.delete')}}">
		<i class="fa fa-trash-o"></i>
	</span>
	@endif

	@endif
	{{-- End AuthCheck --}}

	<small data-id="{{$comment->id}}" data-toggle="collapse" href="#collapse{{$comment->id}}" aria-expanded="false" style="cursor:pointer;" class="pull-right comments-likes like-small @if( $comment->total_likes()->count() == 0 ) display-none @endif">
		<span class="fa fa-heart myicon-right"></span>
		<span class="count">{{ $comment->total_likes()->count() }}</span>
	</small>

    			</div>
    		</div>
  		 </div><!-- media -->

  	<ul class="list-inline collapse text-right" id="collapse{{$comment->id}}">
	   	<li>{{ trans('misc.loading') }}</li>
	 </ul>

	</div><!-- media box -->

		  @endforeach

		  @if( $comments_sql->count() != 0  )
			    <div style="width: 100%">
			    	{{ $comments_sql->links() }}
			    	</div>
			    	@endif
