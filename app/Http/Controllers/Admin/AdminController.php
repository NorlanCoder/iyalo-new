<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request){
        $q = $request->q ? : '';
        $users = User::where('id','!=',auth()->user()->id)->where('role','admin')      
            ->where(function ($query) use ($q) {
                $query->where(DB::raw('lower(name)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(email)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(phone)'),'like','%'.strtolower($q).'%');
            })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.admins.index',compact('users','q'));
    }

    public function create(Request $request){
    
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required|unique:users',
            ]) ;
        }

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'admin',
            'password' => Hash::make('azertyui'),
        ]);

        DB::commit();

        return back()->with('success','Administrateur bien ajouter');
    
    }

    public function update(Request $request, User $user){

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if($user->phone != $request->phone){
            $validation = Validator::make($request->all(), [
                'phone' => 'required|unique:users',
            ]);
    
            if ($validation->fails()){
                Session::flash('danger', "Erreur dans le formulaire");
                $this->validate($request, [
                    'phone' => 'required|unique:users',
                ]) ;
            }
        }
        if($user->email != $request->email){
            $validation = Validator::make($request->all(), [
                'email' => 'required|unique:users',
            ]);
    
            if ($validation->fails()){
                Session::flash('danger', "Erreur dans le formulaire");
                $this->validate($request, [           
                    'email' => 'required|unique:users',
                ]) ;
            }
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [           
                'name' => 'required|unique:users',
            ]) ;
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success','Administrateur bien modifier');

    }

 
    public function action(User $user){
        
        $user->update([
            'status' => $user->status ? false : true,
        ]);

        return back()->with('success','Succès');

    }

}
