<?php

namespace App\Http\Controllers\Announcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(){

        $properties = Property::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $properties
        ], 200);
    }

    public function show(Property $property){
        
        return response()->json([
            'success' => true,
            'data' => $property
        ], 200);
    }

    public function create(Request $request){
    
    }
}
