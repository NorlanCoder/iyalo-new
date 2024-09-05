<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Note;
use App\Models\User;
use App\Models\Visit;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;

use DevRaeph\PDFPasswordProtect\Facade\PDFPasswordProtect;

class DashboardController extends Controller
{
    public function index(){
        $client = User::where('role','visitor')->count();
        $announcer = User::where('role','announcer')->count();

        $visit = Visit::count();
        $iyalo = Visit::where('visited',true)->sum('free');

        $recentes = Visit::where('visited',true)->orderBy('created_at','desc')->limit(6)->get();
        
        // Générer les 10 dernières dates
        $dates = collect(range(0, 9))
        ->map(function ($day) {
            return Carbon::now()->subDays($day)->toDateString();
        })
        ->reverse();

        //CLient
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')->whereBetween('created_at', [Carbon::now()->subDays(9), Carbon::now()])->groupBy('date')->where('role','visitor')->get()->keyBy('date');
        $users = $dates->map(function ($date) use ($users) {
            return isset($users[$date]->total) ? $users[$date]->total : 0;
        });

        //Visites
        $visits = Visit::selectRaw('DATE(created_at) as date, SUM(amount) as total')->whereBetween('created_at', [Carbon::now()->subDays(9), Carbon::now()])->groupBy('date')->get()->keyBy('date');
        $visits = $dates->map(function ($date) use ($visits) {
            return isset($visits[$date]->total) ? intval($visits[$date]->total) : 0;
        });

        $a = $b = $c = array();
        for($i = 0; $i < 10; $i++){
            $a = array_merge($a,array($users[$i]));
            $b = array_merge($b,array($visits[$i]));
        }
        $users = json_encode($a);
        $visits = json_encode($b);
        // dd($visits);

        return view('admin.file.dashboard', compact('client','announcer','visit','iyalo','recentes','users','visits'));
    }

    
    public function profil(){
        return view('admin.file.profil');
    }

    public function update_info(Request $request, $id){

        // dd($request->input());
        $user = User::find($id);
        if($user->email != $request->email){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'email' => 'required|email|max:255|unique:users',
            ]) ;
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required',
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'name' => 'required|max:255',
                'phone' => 'required',
            ]) ;
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success','Modification réalisée avec succès');
    }

    public function update_pass(Request $request,$id){
        //return $request->input();
        $user=User::find($id);
        $validation = Validator::make($request->all(), [
            'older' => 'required|min:6',
            'password' => 'required|min:6',
            'confirm' => 'required|same:password|min:6'
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'older' => 'required|min:6',
                'password' => 'required|min:6',
                'confirm' => 'required|same:password|min:6'
            ]) ;
        }
        if(Hash::check($request->older,$user->password))
            $user->password = Hash::make($request->password);
        else
            return back()->with('danger',"Revoir l'ancien mot de passe");
        $user->save();
        return back()->with('success',"Mot de passe modifié avec succès");
    }

    public function notes(Request $request){
        $q = $request->q ?: '';
        $notes = Note::where(function ($query) use ($q) {

            $users = User::where(DB::raw('lower(name)') ,'like','%'.strtolower($q).'%')->orwhere(DB::raw('lower(email)') ,'like','%'.strtolower($q).'%')->pluck('id');
            $categories = Category::where(DB::raw('lower(label)') ,'like','%'.strtolower($q).'%')->pluck('id');
            $properties = Property::where(DB::raw('lower(label)') ,'like','%'.strtolower($q).'%')->orwhereIn('category_id',$categories)->orwhereIn('user_id',$users)->pluck('id');

            $query->where(DB::raw('lower(comment)'),'like','%'.strtolower($q).'%')
                ->orwhereIn('user_id', $users)
                ->orwhereIn('property_id', $properties);

        })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.notes',compact('notes','q'));
    
    }
}
