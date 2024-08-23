<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\Calendar;
use App\Models\Category;
use Illuminate\Http\Request;

class PropertyController extends Controller
{

    public function index(Request $request){
        $q = $request->q ? : '';
        $properties = Property::where(function ($query) use ($q) {

            $users = User::where(DB::raw('lower(name)') ,'like','%'.strtolower($q).'%')->orwhere(DB::raw('lower(email)') ,'like','%'.strtolower($q).'%')->pluck('id');
            $categories = Category::where(DB::raw('lower(label)') ,'like','%'.strtolower($q).'%')->pluck('id');
                $query->where(DB::raw('lower(label)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(frequency)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(city)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(country)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(district)'),'like','%'.strtolower($q).'%')
                    ->orwhere(DB::raw('lower(description)'),'like','%'.strtolower($q).'%')
                    ->orwhereIn('user_id', $users)
                    ->orwhereIn('category_id', $categories);

            })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.properties.index',compact('properties','q'));
    }

 
    public function action(Property $property){
        
        $property->update([
            'status' => $property->status ? false : true,
        ]);

        return back()->with('success','Succès');

    }

}
