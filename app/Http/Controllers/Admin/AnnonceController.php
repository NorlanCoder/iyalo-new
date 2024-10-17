<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Note;
use App\Models\Annonce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    
    public function index(Request $request) {
        $q = $request->q ?: '';
        $annonces = Annonce::where(function ($query) use ($q) {
                $query->where(DB::raw('lower(title)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(type)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(adresse)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(description)'),'like','%'.strtolower($q).'%');
            })->orderBy('id','desc')->paginate(10);

        return view('admin.file.annonces.index',compact('annonces'));
    }

    public function create(Request $request){

        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required',
            'type' => 'required',
            'adresse' => 'required',
            'description' => 'required',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $validation->validate($request, [
                'title' => 'required',
                'image' => 'required',
                'type' => 'required',
                'adresse' => 'required',
                'description' => 'required',
            ]) ;
        }

        if($request->file('image')){
            $cover = $request->file('image');
            $extension = $cover->getClientOriginalName();
            $filename = time().'-'.$extension;
            $cover->move('uploads/annonces', $filename);
            $image = 'uploads/annonces/'.$filename;
        }else
            $image = null;

        $annonce = Annonce::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'type' => $request->type,
            'adresse' => $request->adresse,
            'description' => $request->description,
            'image' => $image,
        ]);

        return back()->with('success','Succès');

    }

    public function update(Request $request, Annonce $annonce){

        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'image' => '',
            'type' => 'required',
            'adresse' => 'required',
            'description' => 'required',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $validation->validate($request, [
                'title' => 'required',
                'image' => '',
                'type' => 'required',
                'adresse' => 'required',
                'description' => 'required',
            ]) ;
        }

        if($request->file('image')){
            $cover = $request->file('image');
            $extension = $cover->getClientOriginalName();
            $filename = time().'-'.$extension;
            $cover->move('uploads/categories', $filename);
            $image = 'uploads/categories/'.$filename;
            $annonce->image ? unlink(public_path($annonce->image)) : '' ;
        }else
            $image = $annonce->image;

        $annonce->update([
            'title' => $request->title,
            'type' => $request->type,
            'adresse' => $request->adresse,
            'description' => $request->description,
            'image' => $image,
        ]);

        return back()->with('success','Succès');

    }

    public function action(Annonce $annonce){

        $annonce->update([
            'active' => $annonce->active ? false : true,
        ]);

        return back()->with('success','Succès');
    }

}
