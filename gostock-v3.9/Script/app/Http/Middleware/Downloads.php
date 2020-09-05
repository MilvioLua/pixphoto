<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AdminSettings;
use Illuminate\Contracts\Auth\Guard;

class Downloads {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$settings    = AdminSettings::first();
		$loginUrl    = url('login');

		if ($this->auth->guest() && $settings->downloads == 'users') {

				return redirect()->guest('login')
			->with(array('login_required' => trans('auth.login_download')));

		} elseif($this->auth->check() && url()->previous() == $loginUrl){
			return redirect('/');
		}

		return $next($request);

	}

}
