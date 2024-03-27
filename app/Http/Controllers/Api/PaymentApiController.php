<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentPlans;
use App\Models\Setting;
use App\Models\Subscriptions as SubscriptionsModel;
use App\Models\User;
use App\Models\UserOrder;
use App\Models\YokassaSubscriptions as YokassaSubscriptionsModel;
use Illuminate\Support\Facades\Log;

class PaymentApiController extends Controller {


    /**
     * Get subscription plan of current user
     *
     * @OA\Get(
     *      path="/api/payment/",
     *      operationId="getCurrentPlan",
     *      tags={"Payments"},
     *      summary="Get subscription plan of current user",
     *      description="Get subscription plan details of current user.",
     *      security={{ "passport": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
    */
    public function getCurrentPlan(Request $request) {

        $userId=Auth::user()->id;
        $planId = "";

        // Get current active subscription
        $activeSub = SubscriptionsModel::where([['status', '=', 'active'], ['user_id', '=', $userId]])->orWhere([['status', '=', 'trialing'], ['user_id', '=', $userId]])->first();
        if($activeSub != null){
            $planId = $activeSub->plan_id;
        }else{
            $activeSub = YokassaSubscriptionsModel::where([['subscription_status', '=', 'active'],['user_id','=', $userId]])->first();
            if($activeSub != null) {
                $planId = $activeSub->plan_id;
            }
        }

        if($planId != ""){
            $plan = PaymentPlans::where([['id', '=', $planId]])->first();
            return response()->json($plan);
        }

        return response()->json(["message"=>"No active subscription found."], 200);

    }



    public function cancelActiveSubscription(Request $request) {

    }




    /**
     * Get all plans
     *
     * @OA\Get(
     *      path="/api/payment/plans/{plan_id?}",
     *      operationId="plans",
     *      tags={"Payments"},
     *      summary="Get all plans.",
     *      description="Get all plans.",
     *      security={{ "passport": {} }},
     *      @OA\Parameter(
     *          name="plan_id",
     *          description="Id of plan to get details of.",
     *          in="path",
     *          required=false,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * )
    */
    public function plans(Request $request, $plan_id = null) {

        if($request->has('plan_id') || $plan_id != null){
            $id = $request->plan_id != null ? $request->plan_id : $plan_id;
            $plan = PaymentPlans::where([['id', '=', $id]])->first();
            return response()->json($plan);
        }

        $plans = PaymentPlans::all();
        return response()->json($plans);

    }


    /**
     * Get all orders
     *
     * @OA\Get(
     *      path="/api/payment/orders/{order_id?}",
     *      operationId="orders",
     *      tags={"Payments"},
     *      summary="Get all orders.",
     *      description="Get all orders. If order_id is provided, then it will return details of that order.",
     *      security={{ "passport": {} }},
     *      @OA\Parameter(
     *          name="order_id",
     *          description="Id of order to get details of.",
     *          in="path",
     *          required=false,
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Permission Denied",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Order not found",
     *      ),
     * )
    */
    public function orders(Request $request, $order_id = null) {

        $user = Auth::user();

        if($request->has('order_id') || $order_id != null){
            $id = $request->order_id != null ? $request->order_id : $order_id;
            $order = UserOrder::where([['id', '=', $id]])->first();
            if($order == null){
                return response()->json(["message"=>"Order not found."], 404);
            }
            
            if($order->user_id != $user->id && $user->type != "admin" && $order->plan_id != null){
                return response()->json(["message"=>"User does not have permission."], 403);
            }

            return response()->json($order);
        }


        //$list = $user->orders;
        $list = UserOrder::where([['user_id', '=', $user->id], ['plan_id', '!=', null]])->orderBy("created_at", "desc")->get();
        return response()->json($list);

    }


}