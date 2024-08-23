<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\Visit;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('role','visitor')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(name)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.clients.index',compact('users','q'));
    }
 
    public function action(User $user){
        
        $user->update([
            'status' => $user->status ? false : true,
        ]);

        return back()->with('success','Succès');
    }

    public function visits(User $user){
        
        $visits = Visit::where('user_id',$user->id)->orderBy('created_at','desc')->paginate(500);

        return view('admin.file.clients.visits',compact('visits','user'));

    }
}
