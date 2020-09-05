<?php $date = Carbon\Carbon::yesterday()->format('Y-m-d'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
         <loc>{{ url('/') }}</loc>
         <lastmod>{{$date}}</lastmod>
         <priority>0.8</priority>
   </url>
   
	@foreach( App\Models\Pages::all() as $page )
	<url>
         <loc>{{ url('page',$page->slug) }}</loc>
         <lastmod>{{$date}}</lastmod>
         <priority>0.8</priority>
   </url>
 @endforeach
   
	@foreach( App\Models\User::where('status','active')->get() as $user )
	<url>
         <loc>{{ url($user->username) }}</loc>
         <lastmod>{{$date}}</lastmod>
         <priority>0.8</priority>
   </url>
   @endforeach
   
   @foreach( App\Models\Images::where('status','active')->get() as $response )
   <?php  if( str_slug( $response->title ) == '' ) {
				$slugUrl  = '';
			} else {
				$slugUrl  = '/'.str_slug( $response->title );
			} ?>
	<url>
         <loc>{{ url('photo',$response->id).$slugUrl }}</loc>
         <lastmod>{{ date('Y-m-d', strtotime($response->date) ) }}</lastmod>
         <priority>0.8</priority>
   </url>
 @endforeach
   
</urlset>