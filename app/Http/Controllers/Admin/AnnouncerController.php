<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\Withdraw;
use App\Models\Visit;
use Illuminate\Http\Request;

class AnnouncerController extends Controller
{

    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','announcer')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(name)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.announcers.index',compact('users','q'));
    }
 
    public function action(User $user){
        
        $user->update([
            'status' => $user->status ? false : true,
        ]);

        return back()->with('success','Succès');
    }

    public function properties(User $user){
        
        $properties = Property::where('user_id',$user->id)->orderBy('created_at','desc')->paginate(500);

        return view('admin.file.announcers.properties',compact('properties','user'));
    }

    public function visits(User $user){
        
        $properties = Property::where('user_id',$user->id)->pluck('id');

        $visits = Visit::whereIn('property_id',$properties)->orderBy('created_at','desc')->paginate(500);
        $cash = Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('amount') - Visit::wherein('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('free');
        $pending = Visit::whereIn('property_id',$properties)->where('visited',false)->where('is_refund',false)->sum('amount') - Visit::wherein('property_id',$properties)->where('visited',false)->where('is_refund',false)->sum('free');

        $all_cash = $cash + $pending;

        return view('admin.file.announcers.visits',compact('visits','cash','pending','all_cash','user'));

    }

    public function wallets(User $user){

        $properties = Property::where('user_id',$user->id)->pluck('id');

        $cash = Visit::whereIn('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('amount') - Visit::wherein('property_id',$properties)->where('visited',true)->where('is_refund',false)->sum('free');

        $withdrawal = Withdraw::where('user_id',$user->id)->sum('amount');
        $wallet = $cash - $withdrawal;

        $withdraws = Withdraw::where('user_id',$user->id)->orderBy('created_at','desc')->paginate(500);

        return view('admin.file.announcers.wallets',compact('withdraws','cash','withdrawal','wallet','user'));
    }

    public function percent(Request $request, User $user){
        $validation = Validator::make($request->all(), [
            'percent' => 'required',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'percent' => 'required',
            ]) ;
        }

        $user->update([
           'free' => $request->percent, 
        ]);

        return back()->with('success','Information bien modifiée');
    }

}
