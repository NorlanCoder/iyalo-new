<?php

namespace App\Http\Controllers\Announcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Withdraw;

class WithdrawController extends Controller
{

    /**
     * Bilan Global
     * 
     * Recap des différentes bilans
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function bilan(){
        $properties = Property::where('user_id',auth()->user()->id)->pluck('id');
        $visits = Visit::whereIn('property_id',$properties)->count();

        $cash = Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('amount') - Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('free');

        $withdrawal = Withdraw::where('user_id',auth()->user()->id)->sum('amount');
        $wallet = $cash - $withdrawal;

        return response()->json([
            'status' => 200,
            'properties' => count($properties),
            'visits' => $visits,
            'all_cash' => $cash,
            'wallet' => $wallet,
        ]);
    }

    /**
     * History Withdraw  of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(){
        $properties = Property::where('user_id',auth()->user()->id)->pluck('id');

        $cash = Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('amount') - Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('free');

        $withdrawal = Withdraw::where('user_id',auth()->user()->id)->sum('amount');
        $wallet = $cash - $withdrawal;

        $withdraws = Withdraw::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(20);

        return response()->json([
            'status' => 200,
            'cash' => $cash,
            'withdrawal' => $withdrawal,
            'wallet' => $wallet,
            'data' => $withdraws,
        ]);
    }

    /**
     * History Checkout of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function history(){
        $properties = Property::where('user_id',auth()->user()->id)->pluck('id');

        $cash = Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('amount') - Visit::where('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('free');

        $pending = Visit::whereIn('property_id',$properties)->where('visited',false)->where('is_refund',false)->sum('amount') - Visit::where('property_id',$properties)->where('visited',false)->where('is_refund',false)->sum('free');

        $all_cash = $cash + $pending;

        $checkout = Visit::whereIn('property_id',$properties)->where('is_refund',false)->paginate(20);

        return response()->json([
            'status' => 200,
            'cash' => $cash,
            'pending' => $pending,
            'all_cash' => $all_cash,
            'data' => $withdraws,
        ]);
    }


    /**
     * Make Withdraw of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function create(Request $request){
        try {

            $validation = Validator::make($request->all(), [
                'amount'  => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            if($request->amount > auth()->user()->solde)
                return response()->json([
                    "message" => 'Fonds Inssufissant',
                    "status" => 400,
                ], 400);
            
            DB::beginTransaction();
                $withdraw = Withdraw::create([
                    "amount" => $request->amount,
                    "user_id" => auth()->user()->id,
                    'reference' => time(),
                ]);

                auth()->user()->update([
                    'solde' => auth()->user()->solde - $request->amount,
                ]);

            DB::commit();

            return response()->json([
                "message" => 'Succès',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }
}
