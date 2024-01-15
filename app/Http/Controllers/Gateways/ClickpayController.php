<?php
namespace App\Http\Controllers\Gateways;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Currency;
use App\Models\CustomSettings;
use App\Models\GatewayProducts;
use App\Models\Gateways;
use App\Models\OldGatewayProducts;
use App\Models\PaymentPlans;
use App\Models\Setting;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use App\Models\HowitWorks;
use App\Models\User;
use App\Models\UserAffiliate;
use App\Models\UserOrder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Jobs\SendPurchaseMail;
use App\Jobs\SendSubsciptionMail;
use App\Jobs\SendSubscriptioncancelMail;
use App\Jobs\SendSubsciptionRenewSuccessMail;
use App\Jobs\SendSubsciptionRenewFailureMail;



class ClickpayController extends Controller
{
    
    public static function prepaid($planId, $plan)
    {    
        $gateway = Gateways::where("code", "clickpay")->first();
        if ($gateway == null) {
            abort(404);
        }
        
        $currency = Currency::where('id', $gateway->currency)->first()->code;
        $plan = PaymentPlans::where('id', $planId)->first();
        $user = Auth::user();
        $orderId = Str::random(12);

        Session::put('plan_id', $planId);

        $response = Http::withHeaders([
            'authorization' => $gateway->sandbox_client_secret,
            'content-type' => 'application/json'
            ])->post('https://secure.clickpay.com.sa/payment/request', [
            'profile_id' => $gateway->sandbox_client_id,
            "tran_type"=> "sale",
            "tran_class"=> "ecom" ,
            "paypage_lang"=> "ar",
            "hide_shipping"=> true,
            "cart_id"=>$orderId,
            "cart_description"=> $plan->name,
            "cart_currency"=> "SAR",
            "cart_amount"=> $plan->price,
            "return"=> route('dashboard.user.payment.clickpay.prepaid.callback'),
            "customer_details"=> [
                "name"=> '',
                "email"=> $user->email,
                "street1"=> "address",
                "city"=> "Riyadh",
                "state"=> "Riyadh",
                "country"=> "SA",
                'zip'=>'1234',
                "ip"=> "10.0.0.10"
            ] , 
           
        ]);
      
        $response = json_decode($response, true);
        return redirect($response['redirect_url']);
        

  }

  
  public function prepaidCallback(Request $request){
    
    $user = Auth::user();
    $plan = PaymentPlans::where('id', session('plan_id'))->first();

    if($request->respStatus === "A"){
         // save checkout to orders
            $payment = new UserOrder();
            $payment->plan_id = $plan->id;
            $payment->order_id = $request->cartId;
            $payment->type = 'prepaid';
            $payment->user_id = $user->id;
            $payment->payment_type = 'clickpay';
            $payment->price = $plan->price;
            $payment->status = 'Success';
            $payment->country = $user->country ?? 'Unknown';
            $payment->save();
            
        $plan->total_words == -1? ($user->remaining_words = -1) : ($user->remaining_words += $plan->total_words);
        $plan->total_images == -1? ($user->remaining_images = -1) : ($user->remaining_images += $plan->total_images);
        
        createActivity($user->id, __('Purchased'), $plan->name.' '. __('Token Pack'), null);

        $user->save();
        
        Session::forget('plan_id');
        
        dispatch(new SendPurchaseMail($user));

        return redirect()->route('dashboard.'.auth()->user()->type.'.index')->with(['message' => __('Thank you for your purchase. Enjoy your remaining words and images.'), 'type' => 'success']); 
    }
        else{
            
        return redirect()->route('dashboard.'.auth()->user()->type.'.index')->with(['message' => $request->respMessage, 'type' => 'error']);        
            
        }
  
      
  }
  
  
  
  
  public static function subscribe ($planId, $plan){
      
       $gateway = Gateways::where("code", "clickpay")->first();
        if ($gateway == null) {
            abort(404);
        }
        
        $currency = Currency::where('id', $gateway->currency)->first()->code;
        $plan = PaymentPlans::where('id', $planId)->first();
        $user = Auth::user();
        $orderId = Str::random(12);

        Session::put('plan_id' , $planId);

        $response = Http::withHeaders([
            'authorization' => $gateway->sandbox_client_secret,
            'content-type' => 'application/json'
            ])->post('https://secure.clickpay.com.sa/payment/request', [
            'profile_id' => $gateway->sandbox_client_id,
            "tran_type"=> "sale",
            "tran_class"=> "ecom" ,
            "paypage_lang"=> "ar",
            "hide_shipping"=> true,
            "cart_id"=>$orderId,
            "cart_description"=> $plan->name,
            "tokenise"=> "2",
            "cart_currency"=> "SAR",
            "cart_amount"=> $plan->price,
            "return"=> route('dashboard.user.payment.clickpay.subscribe.callback'),
            "customer_details"=> [
                "name"=> '',
                "email"=> $user->email,
                "street1"=> "address",
                "city"=> "Riyadh",
                "state"=> "Riyadh",
                "country"=> "SA",
                'zip'=>'1234',
                "ip"=> "10.0.0.10"
            ] , 
           
        ]);
      
        $response = json_decode($response, true);
        return redirect($response['redirect_url']);
  
  }
  
    public function subscribeCallback(Request $request){
    
    $user = Auth::user();
    $plan = PaymentPlans::where('id', session('plan_id'))->first();
    $existSubscription = Subscriptions::where('user_id',$user->id)->first();
    
    if($request->respStatus === "A"){
         // save checkout to orders
            $payment = new UserOrder();
            $payment->plan_id = $plan->id;
            $payment->order_id = $request->cartId;
            $payment->type = 'subscribe';
            $payment->user_id = $user->id;
            $payment->payment_type = 'clickpay';
            $payment->price = $plan->price;
            $payment->status = 'Success';
            $payment->country = $user->country ?? 'Unknown';
            $payment->save();
            
            if($plan->frequency == 'monthly'){
                $ends_at = \Carbon\Carbon::now()->addMonth();
            }
            else{
                $ends_at = \Carbon\Carbon::now()->addYear();
            }
            
        if(!$existSubscription)    {
            $subscription = new Subscriptions();
            $subscription->user_id = $user->id;
            $subscription->name = $plan->name;
            $subscription->status = 'active';
            $subscription->price = $plan->price;
            $subscription->quantity = 1;
            $subscription->token = $request->token;
            $subscription->trans_ref = $request->tranRef;
            $subscription->ends_at = $ends_at;
            $subscription->plan_id = $plan->id;
            $subscription->paid_with = 'clickpay';
            $subscription->save();
        }
        
        else{
            $existSubscription -> update([
            "name" => $plan->name ,
            "status" => 'active' ,
            "price" => $plan->price ,
            "token" => $request->token,
            "trans_ref" => $request->tranRef,
            "ends_at" => $ends_at ,
            "plan_id" => $plan->id ,
            ]);
        }
        
            
        $plan->total_words == -1? ($user->remaining_words = -1) : ($user->remaining_words += $plan->total_words);
        $plan->total_images == -1? ($user->remaining_images = -1) : ($user->remaining_images += $plan->total_images);
        
        createActivity($user->id, __('Subscribed'), $plan->name.' '. __('Plan'), null);

        $user->save();
        
        Session::forget('plan_id');
        
        dispatch(new SendSubsciptionMail($user));


        return redirect()->route('dashboard.'.auth()->user()->type.'.index')->with(['message' => __('Thank you for your purchase. Enjoy your remaining words and images.'), 'type' => 'success']); 
    }
        else{
            
        return redirect()->route('dashboard.'.auth()->user()->type.'.index')->with(['message' => $request->respMessage, 'type' => 'error']);        
            
        }
  
      
  }
  
  
  public static function subscribeCancel (){
       
        $user = Auth::user();
        $activeSub = Subscriptions::where([['status', '=', 'active'], ['user_id', '=', $user->id]])->first();
        $plan = PaymentPlans::where('id', $activeSub->plan_id)->first();

        $activeSub->status = "cancelled";
        $activeSub->ends_at = \Carbon\Carbon::now();
        $activeSub->save();
                
        
        $recent_words = $user->remaining_words - $plan->total_words;
                $recent_images = $user->remaining_images - $plan->total_images;
                $user->remaining_words = $recent_words < 0 ? 0 : $recent_words;
                $user->remaining_images = $recent_images < 0 ? 0 : $recent_images;
                $user->save();

        createActivity($user->id, 'Cancelled', 'Subscription plan', null);
        
        dispatch(new SendSubscriptioncancelMail($user));
        
        return back()->with(['message' => 'Your subscription is cancelled succesfully.', 'type' => 'success']);

    
  }
  
  
  public static function getSubscriptionStatus (){
      $gateway = Gateways::where("code", "clickpay")->first();
        if ($gateway == null) {
            return null;
        }

        
        $userId=Auth::user()->id;
        // Get current active subscription
        $activeSub = Subscriptions::where([['status', '=', 'active'], ['user_id', '=', $userId]])->first();
        
            if ($activeSub->status == 'active'){
                return true;
            }else{
                
                return false;
            }
        
  }
  
  
  public static function getSubscriptionDaysLeft(){
      $userId=Auth::user()->id;
      $activeSub = Subscriptions::where([['status', '=', 'active'], ['user_id', '=', $userId]])->first();
      return \Carbon\Carbon::now()->diffInDays($activeSub->ends_at);
  }
  
  
  public function recurringCallback(Request $request , $id){
      $request = json_decode($request->getContent(), true);
      $activSub = Subscriptions::find($id);
      $user = User::find($activSub->user_id);
      $plan = PaymentPlans::find($activSub->plan_id);
      if($request['payment_result']['response_status'] === "A"){
            $payment = new UserOrder();
            $payment->plan_id = $plan->id;
            $payment->order_id = $request['cart_id'];
            $payment->type = 'renew';
            $payment->user_id = $user->id;
            $payment->payment_type = 'clickpay';
            $payment->price = $activSub->price;
            $payment->status = 'Success';
            $payment->country = $user->country ?? 'Unknown';
            $payment->save();
            
            if($plan->frequency == 'monthly'){
                $ends_at = \Carbon\Carbon::now()->addMonth();
            }
            else{
                $ends_at = \Carbon\Carbon::now()->addYear();
            }
            
            $activSub->update([
                'ends_at' => $ends_at 
                ]);
            
          
        $plan->total_words == -1? ($user->remaining_words = -1) : ($user->remaining_words += $plan->total_words);
        $plan->total_images == -1? ($user->remaining_images = -1) : ($user->remaining_images += $plan->total_images);
        
        createActivity($user->id, __('renewed Subscription of '), $plan->name.' '. __('Plan'), null);
        
        dispatch(new SendSubsciptionRenewSuccessMail($user));

        $user->save();          
      }
      
      else{
          $activSub->update([
                'status' => 'inactive' 
                ]);
        
        dispatch(new SendSubsciptionRenewFailureMail($user));
      }
  }
  
    
}