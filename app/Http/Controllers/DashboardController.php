<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchases;
use App\Models\Deposits;
use App\Models\Downloads;
use App\Models\Withdrawals;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Images;
use App\Helper;
use App\Models\PaymentGateways;

class DashboardController extends Controller
{

  public function __construct(AdminSettings $settings, Request $request) {

    $this->middleware('sellOption');
    $this->settings = $settings::first();
    $this->request = $request;
  }

  // Dashboard
	public function dashboard()
	{
		return view('dashboard.dashboard');
	}//<--- End Method

  public function photos() {

		$query = request()->get('q');
		$sort = request()->get('sort');
		$pagination = 15;

		$data = Images::whereUserId(Auth::user()->id)->orderBy('id','desc')->paginate($pagination);

		// Search
		if( isset( $query ) ) {
		 	$data = Images::where('title', 'LIKE', '%'.$query.'%')
      ->whereUserId(Auth::user()->id)
			->orWhere('tags', 'LIKE', '%'.$query.'%')
      ->whereUserId(Auth::user()->id)
		 	->orderBy('id','desc')->paginate($pagination);
		 }

		// Sort
		if( isset( $sort ) && $sort == 'title' ) {
			$data = Images::whereUserId(Auth::user()->id)->orderBy('title','asc')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'pending' ) {
			$data = Images::whereUserId(Auth::user()->id)->where('status','pending')->paginate($pagination);
		}

		if( isset( $sort ) && $sort == 'downloads' ) {
			$data = Images::join('downloads', 'images.id', '=', 'downloads.images_id')
          ->where('images.user_id', Auth::user()->id)
					->groupBy('downloads.images_id')
					->orderBy( \DB::raw('COUNT(downloads.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		if( isset( $sort ) && $sort == 'likes' ) {
			$data = Images::join('likes', function($join){
				$join->on('likes.images_id', '=', 'images.id')
        ->where('images.user_id', Auth::user()->id)
        ->where('likes.status', '=', '1' );
			})
					->groupBy('likes.images_id')
					->orderBy( \DB::raw('COUNT(likes.images_id)'), 'desc' )
					->select('images.*')
					->paginate( $pagination );
		}

		return view('dashboard.photos', ['data' => $data,'query' => $query, 'sort' => $sort ]);
	}//<--- End Method

  public function sales()
  {
    $data = Purchases::leftJoin('images', function($join) {
  		 $join->on('purchases.images_id', '=', 'images.id');
  	 })
  	 ->where('images.user_id',Auth::user()->id)
  	 ->select('purchases.*')
  	 ->orderBy('purchases.id','DESC')
     ->paginate(30);

		return view('dashboard.sales')->withData($data);
	}//<--- End Method

  public function purchases()
  {
    $data = Purchases::whereUserId(Auth::user()->id)
  	 ->orderBy('id','DESC')
     ->paginate(30);

		return view('dashboard.purchases')->withData($data);
	}//<--- End Method

  public function deposits(){

		$data = Deposits::whereUserId(Auth::user()->id)->orderBy('id', 'desc')->paginate(30);

		return view('dashboard.deposits-history')->withData($data);
	}//<--- End Method

  // Add Funds
	public function addFunds()
	{
    // Stripe Key
    $_stripe = PaymentGateways::where('id', 2)->where('enabled', '1')->select('key')->first();

		return view('dashboard.add-funds')->with(['_stripe' => $_stripe]);
	}//<--- End Method

  public function showWithdrawal()
  {

    $data = Withdrawals::whereUserId(Auth::user()->id)->paginate(20);
    return view('dashboard.withdrawals')->withData($data);

  }//<--- End Method

  public function withdrawal()
  {
    if( Auth::user()->payment_gateway == 'PayPal'
		&& empty(Auth::user()->paypal_account)

		|| Auth::user()->payment_gateway == 'Bank'
		&& empty(  Auth::user()->bank  )

		|| empty(Auth::user()->payment_gateway)

		) {
			\Session::flash('error',trans('misc.configure_withdrawal_method'));
			return redirect('user/dashboard/withdrawals');
		}

    // Verify amount validate
    if(Auth::user()->balance < $this->settings->amount_min_withdrawal) {
      \Session::flash('error',trans('misc.withdraw_not_valid'));
			return redirect('user/dashboard/withdrawals');
    }

      if( Auth::user()->payment_gateway == 'PayPal' ) {
       $_account = Auth::user()->paypal_account;
      } else {
       $_account = Auth::user()->bank;
      }

      $sql               = new Withdrawals;
			$sql->user_id      = Auth::user()->id;
			$sql->amount       = Auth::user()->balance;
			$sql->gateway      = Auth::user()->payment_gateway;
			$sql->account      = $_account;
			$sql->save();

      // Remove Balance the User
      $userBalance = User::find(Auth::user()->id);
      $userBalance->balance = 0;
      $userBalance->save();

			return redirect('user/dashboard/withdrawals');

  }//<--- End Method

    public function withdrawalConfigure()
    {

    if( $this->request->type != 'paypal' && $this->request->type != 'bank' ) {
      \Session::flash('error', trans('misc.error'));
      return redirect('user/dashboard/withdrawals/configure');
      exit;
    }

    // Validate Email Paypal
    if( $this->request->type == 'paypal') {
      $rules = array(
          'email_paypal'  => 'required|email|confirmed',
        );

    $this->validate($this->request, $rules);

    $user = User::find(Auth::user()->id);
    $user->paypal_account = $this->request->email_paypal;
    $user->payment_gateway = 'PayPal';
    $user->save();

    \Session::flash('success', trans('admin.success_update'));
    return redirect('user/dashboard/withdrawals/configure');

    }// Validate Email Paypal

    elseif($this->request->type == 'bank') {

      $rules = array(
          'bank' => 'required',
           );

      $this->validate($this->request, $rules);

       $user = User::find(Auth::user()->id);
       $user->bank = $this->request->bank;
       $user->payment_gateway = 'Bank';
       $user->save();

      \Session::flash('success', trans('admin.success_update'));
      return redirect('user/dashboard/withdrawals/configure');
    }

    }//<--- End Method

    public function withdrawalDelete()
    {

      $withdrawal = Withdrawals::whereId($this->request->id)
      ->whereUserId(Auth::user()->id)
      ->whereStatus('pending')
      ->firstOrFail();

      if(isset($withdrawal)) {

        $withdrawal->delete();

        // Add Balance the User again
        User::find(Auth::user()->id)->increment('balance', $withdrawal->amount);

        return redirect('user/dashboard/withdrawals');

      }// Isset withdrawal

    }//<--- End Method

    // withdrawals configure view
    public function withdrawalsConfigureView()
    {
      return view('dashboard.withdrawals-configure');
    }//<--- End Method

    public function downloads()
    {
      $data = auth()->user()->downloads()
      ->join('images', 'images.id', '=', 'downloads.images_id')
      ->where('images.item_for_sale', 'free')
      ->select('images.id', 'images.title', 'images.token_id', 'images.thumbnail')
      ->addSelect('downloads.date AS dateDownload', 'downloads.size')
      ->groupBy('images.id')
      ->orderBy('downloads.id','DESC')
      ->paginate(30);

  		return view('dashboard.downloads')->withData($data);
  	}//<--- End Method

}
