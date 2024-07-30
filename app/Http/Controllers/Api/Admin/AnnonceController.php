<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Annonce;

class AnnonceController extends Controller
{

    /**
     * List Annonce of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(){
        
        $annonces = Annonce::orderBy('created_at','desc')->paginate();
        return response()->json([
            'success' => true,
            'data' => $annonces
        ], 200);
    }

    /**
     * Add  Annonce of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function create(Request $request){
        
        try {
            $validation = Validator::make($request->all(), [
                'label' => 'required',
                'cover' => 'required|max:10000',
                'describ' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            DB::beginTransaction();

                if($request->file('cover')){
                    $cover = $request->file('cover');
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $cover->move('uploads/annonce', $filename);
                    $cover_url = 'uploads/annonce/'.$filename;
                }

                $annonce = Annonce::create([
                    'label' => $request->label,
                    'cover_url' => $cover_url,
                    'describ' => $request->describ,
                ]);

            DB::commit();

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }


    /**
     * Update  Annonce of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, Annonce $annonce){
        
        try {
            $validation = Validator::make($request->all(), [
                'label' => 'required',
                'cover' => 'max:10000',
                'describ' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            DB::beginTransaction();

                if($request->file('cover')){
                    $cover = $request->file('cover');
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $cover->move('uploads/annonce', $filename);
                    $cover_url = 'uploads/annonce/'.$filename;
                }else
                    $cover_url = $annonce->cover_url;

                $annonce->update([
                    'label' => $request->label,
                    'cover_url' => $cover_url,
                    'describ' => $request->describ,
                ]);

            DB::commit();

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Active/Disactive  Annonce of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function action(Annonce $annonce){
        
        $annonce->update([
            'is_active' => $annone->is_active ? false : true,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Successfull',
        ], 200);
    }

    /**
     * Delete  Annonce of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function destroy(Annonce $annonce){
        
        $annonce->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Successfull',
        ], 200);
    }

}
