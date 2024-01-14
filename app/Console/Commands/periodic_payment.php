<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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


class periodic_payment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periodic_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gateway = Gateways::where("code", "clickpay")->first();
        if ($gateway == null) {
            abort(404);
        }
        
        $activeSubscriptions = Subscriptions::where('status','active')->whereDate('ends_at', '=', Carbon::today()->toDateString())->get();
    
        if($activeSubscriptions){
            foreach($activeSubscriptions as $activeSub) 
            {
            $orderId = Str::random(12);
            
            $response = Http::withHeaders([
            'authorization' => $gateway->sandbox_client_secret,
            'content-type' => 'application/json'
            ])->post('https://secure.clickpay.com.sa/payment/request', [
            'profile_id' => $gateway->sandbox_client_id,
            "tran_type"=> "sale",
            "tran_class"=> "recurring",
            "cart_id"=>$orderId,
            "cart_description"=> $activeSub->name,
            "token"=> $activeSub->token,
            "tran_ref"=> $activeSub->trans_ref,
            "cart_currency"=> "SAR",
            "cart_amount"=> $activeSub->price,
            "callback"=> "https://test.mohamedfathy90.com/api/dashboard/user/payment/clickpay/subscribe/recurring/callback/".$activeSub->id,
        ]);
      
       
            }
        }
    }
}
