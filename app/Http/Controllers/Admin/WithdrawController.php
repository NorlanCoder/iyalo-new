<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Withdraw;
use App\Models\Visit;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index(Request $request){

        $all_cash = Visit::where('visited',true)->where('is_refund',false)->sum('free');

        $cash = Visit::where('visited',true)->where('is_refund',false)->sum('amount') - Visit::where('visited',true)->where('is_refund',false)->sum('free');

        $withdrawal = Withdraw::sum('amount');

        $wallet = $cash - $withdrawal;

        $q = $request->q ? : '';
        $withdraws = Withdraw::where(function ($query) use ($q) {

            $users = User::where(DB::raw('lower(name)') ,'like','%'.strtolower($q).'%')->orwhere(DB::raw('lower(email)') ,'like','%'.strtolower($q).'%')->pluck('id');

            $query->where(DB::raw('lower(reference)'),'like','%'.strtolower($q).'%')
                ->orwhereIn('user_id', $users);

        })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.withdraws.index',compact('withdraws','q','all_cash','cash','withdrawal','wallet'));
    }

    public function check(Withdraw $withdraw){
        
        $withdraw->update([
            'is_confirm' => true,
        ]);

        return back()->with('success','Succès');

    }
}
