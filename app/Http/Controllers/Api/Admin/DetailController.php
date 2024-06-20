<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Property;
use App\Models\Category;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    /**
     * Property
     *
     * All Properties
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function properties(){

        $properties = Property::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $properties
        ], 200);
    }


    /**
     * Categories
     *
     * All Properties
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function categories(){
        
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * Withdraws
     *
     * All Withdraws
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function withdraws(){

        $withdraws = Withdraw::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $withdraws
        ], 200);
    }

    /**
     * Activate Withdraw
     *
     * Validation Withdraw of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function valide_withdrawal(Withdraw $withdraw){
        
        $withdraw->update([
           'is_confirm' => true, 
        ]);

        return response()->json([
            'success' => true,
            'data' => $withdraw
        ], 200);
    }
}
