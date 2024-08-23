<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index() {
        $categories = Category::all();

        return view('admin.file.categories.index',compact('categories'));
    }

    public function create(Request $request){

        $validation = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'label' => 'required',
            ]) ;
        }

        $category = Category::create([
            'label' => $request->label,
        ]);

        return back()->with('success','Succès');

    }

    public function update(Request $request, Category $category){

        $validation = Validator::make($request->all(), [
            'label' => 'required',
        ]);

        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'label' => 'required',
            ]) ;
        }

        $category->update([
            'label' => $request->label,
        ]);

        return back()->with('success','Succès');

    }

    public function action(Category $category){
        
        $category->update([
            'status' => $category->status ? false : true,
        ]);

        return back()->with('success','Succès');
    }

}
