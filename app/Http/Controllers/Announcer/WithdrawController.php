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
     * History Withdraw of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(){
        
        $withdraws = Withdraw::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $withdraws
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
