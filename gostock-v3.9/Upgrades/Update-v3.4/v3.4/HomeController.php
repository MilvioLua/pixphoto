<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Models\User;
use App\Models\Images;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Categories;
use App\Models\Query;
use App\Models\Collections;
use App\Helper;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $categories = Categories::where('mode','on')->orderBy('name')->paginate(12);
  		$images     = Query::latestImages();
      $featured   = Query::featuredImages();
      $popularCategories = Categories::withCount('images')->latest('images_count')->has('images')->take(5)->with('images')->get();

      if ($popularCategories->count() != 0) {
        foreach ($popularCategories as $popularCategorie) {

    			$popularCategorieArray[]  = '<a style="color:#FFF;" href="'.url('category', $popularCategorie->slug).'">'.$popularCategorie->name.'</a>';
    		}
        $categoryPopular = implode( ', ', $popularCategorieArray);
      } else {
        $categoryPopular = false;
      }


  		return view(
        'index.home', [
          'categories' => $categories,
          'images' => $images,
          'featured' => $featured,
          'categoryPopular' => $categoryPopular
        ]);

	}// End Method

	public function getVerifyAccount( $confirmation_code )
  {
		if (Auth::guest()
        || Auth::check()
        && Auth::user()->activation_code == $confirmation_code
        && Auth::user()->status == 'pending'
        ) {
		$user = User::where('activation_code', $confirmation_code)->where('status','pending')->first();

		if ($user) {

			$update = User::where('activation_code', $confirmation_code)
			->where('status','pending')
			->update(array('status' => 'active', 'activation_code' => ''));


			Auth::loginUsingId($user->id);

			 return redirect('/')
					->with([
						'success_verify' => true,
					]);
			} else {
			return redirect('/')
					->with([
						'error_verify' => true,
					]);
			}
		}
    else {
			 return redirect('/');
		}
	}// End Method

	public function getSearch()
  {
    $q = request()->get('q');
		$images = Query::searchImages();

		//<--- * If $q is empty or is minus to 1 * ---->
		if ($q == '' || strlen($q) <= 2) {
			return redirect('/');
		}

    if (request()->ajax()) {
            return view('includes.images')->with($images)->render();
        }

		return view('default.search')->with($images);
	}// End Method

	public function latest()
  {
		$images = Query::latestImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

		return view('index.explore', ['images' => $images, 'title' => trans('misc.latest')]);

	}// End Method

	public function featured()
  {
		$images = Query::featuredImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

		return view('index.explore', ['images' => $images, 'title' => trans('misc.featured')]);

	}// End Method


	public function popular()
  {
		$images = Query::popularImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

			return view('index.explore', ['images' => $images, 'title' => trans('misc.popular')]);

	}// End Method

	public function commented()
  {
		$images = Query::commentedImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

		return view('index.explore', ['images' => $images, 'title' => trans('misc.most_commented')]);

	}// End Method

	public function viewed()
  {
		$images = Query::viewedImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

		return view('index.explore', ['images' => $images, 'title' => trans('misc.most_viewed')]);

	}// End Method

	public function downloads()
  {
		$images = Query::downloadsImages();

    if (request()->ajax()) {
            return view('includes.images',['images' => $images])->render();
        }

		return view('index.explore', ['images' => $images, 'title' => trans('misc.most_downloads')]);

	}// End Method

	public function category($slug)
  {
		$images = Query::categoryImages($slug);

    if (request()->ajax()) {
            return view('includes.images')->with($images)->render();
        }

		return view('default.category')->with($images);

	}// End Method

	public function tags($slug)
  {
	 if (strlen($slug) > 1) {
		$settings = AdminSettings::first();

		$images = Query::tagsImages($slug);

    if (request()->ajax()) {
            return view('includes.images')->with($images)->render();
        }

		return view('default.tags-show')->with($images);
		} else {
			abort('404');
		}

	}// End Method

	public function cameras($slug)
  {
    if (strlen($slug) > 3) {
		$settings = AdminSettings::first();

		$images = Query::camerasImages($slug);

    if (request()->ajax()) {
            return view('includes.images')->with($images)->render();
        }

		return view('default.cameras')->with($images);

		} else {
			abort('404');
		}
	}// End Method

	public function colors($slug)
  {
		if (strlen($slug) == 6) {

			$settings = AdminSettings::first();

			$images = Query::colorsImages($slug);

      if (request()->ajax()) {
              return view('includes.images')->with($images)->render();
          }

			return view('default.colors')->with($images);

		} else {
			abort('404');
		}
	}// End Method

	public function collections(Request $request)
  {
		$settings = AdminSettings::first();

		$title       = trans('misc.collections').' - ';

	   $data = Collections::has('collection_images')
	   ->where('type','public')
		->orderBy('id','desc')
		->paginate( $settings->result_request );

		if ($request->input('page') > $data->lastPage()) {
			abort('404');
		}

 		return view('default.collections', [ 'title' => $title, 'data' => $data] );
    }//<--- End Method

    public function premium()
    {
      $settings = AdminSettings::first();

      if ($settings->sell_option == 'off') {
          abort(404);
      }

  		$images = Query::premiumImages();

      if (request()->ajax()) {
              return view('includes.images',['images' => $images])->render();
          }

  		return view('index.explore', ['images' => $images, 'title' => trans('misc.premium')]);

  	}// End Method

}
