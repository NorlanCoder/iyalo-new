<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Property;


class ProfilController extends Controller
{
    /**
     * My Info
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function getinfo(){
    
        return response()->json([
            'status' => 200,
            'data' => auth()->user(),
        ], 200);
    }

    /**
     * All Properties
     *
     * @unauthenticated
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function all_properties(Request $request){

        try {

            $validation = Validator::make($request->all(), [
                'category_id' => '',
                'room' => '',
                'bathroom' => '',
                'min_price' => '',
                'max_price' => '',
                'search' => '',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $properties = Property::where('status',true)
                ->where(DB::raw('lower(country)'),'like',['%'.mb_strtolower($request->search).'%'])
                ->where(DB::raw('lower(city)'),'like',['%'.mb_strtolower($request->search).'%'])
                ->where('room','>=',$request->room ?: 0 )
                ->where('bathroom','>=',$request->bathroom ?: 0)
                ->whereBetween('price',[$request->min_price ?: 0,$request->max_price ?: 999999999]);
                
            if($request->category_id)
                $properties = $properties->where('category_id',$request->category_id)->orderBy('created_at','desc')->paginate(10);
            else 
                $properties = $properties->orderBy('created_at','desc')->paginate(10);
    

                
            return response()->json([
                'status' => 200,
                'data' => $properties,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }

     
    /**
     * Update Info
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update_info(Request $request){

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'birthday' => 'required',
        ]);

        if(auth()->user()->phone != $request->phone){
            $validation = Validator::make($request->all(), [
                'phone' => 'required|unique:users',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "status" => 400,
                    "errors" => $validation->errors()
                ], 400);
            }
        }
        if(auth()->user()->email != $request->email){
            $validation = Validator::make($request->all(), [
                'email' => 'required|unique:users',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "status" => 400,
                    "errors" => $validation->errors()
                ], 400);
            }
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'birthday' => 'required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }
        
        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Successfull',
        ], 200);
    }

    /**
     * Update Password
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update_pass(Request $request){

        $validation = Validator::make($request->all(), [
            'older' => 'required|min:6',
            'password' => 'required|min:6',
            'confirm' => 'required|same:password|min:6'
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }

        if(Hash::check($request->older,$user->password))
            auth()->user()->password = Hash::make($request->password);
        else
            return response()->json([
                "status" => 400,
                "errors" => "Revoir l'ancien mot de passe"
            ], 400);

        auth()->user()->save();

        return response()->json([
            'status' => 200,
            'message' => 'Mot de passe modifié avec succès',
        ], 200);
    }

    /**
     * Became Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function became_announcer(Request $request){

        $validation = Validator::make($request->all(), [
            'adress' => 'required',
            'card' => 'required|max:10000',
            'logo' => 'required|max:10000'
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }

        DB::beginTransaction();

            if($request->file('card')){
                $card = $request->file('card');
                $extension = $cover->getClientOriginalExtension();
                $extension = $cover->getClientOriginalName();
                $filename = time().'-'.$extension;
                $file->move('uploads/card', $filename);
                $card_url = 'uploads/card/'.$filename;
            }

            if($request->file('logo')){
                $logo = $request->file('logo');
                $extension = $cover->getClientOriginalExtension();
                $extension = $cover->getClientOriginalName();
                $filename = time().'-'.$extension;
                $file->move('uploads/logo', $filename);
                $logo_url = 'uploads/logo/'.$filename;
            }

            auth()->user()->update([
                'role' => 'announcer',
                'adress' => $request->adress,
                'card_url' => $card_url,
                'logo' => $logo_url
            ]);

            auth()->user()->tokens()->delete();
        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Vous êtes maintenant un Annonceur',
        ], 200);
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur déconnecté avec succès'       
        ]);
    }
}
