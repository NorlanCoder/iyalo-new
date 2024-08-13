<?php

namespace App\Http\Controllers\Api\Visiteur;

use App\Http\Controllers\Controller;
use App\Models\Favory;
use App\Models\Property;
use App\Models\User;
use App\Models\Note;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //Favoris
    /**
     * List User Favoris of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function listfavoris(Request $request, $id) {
        try {

            // Trouver le visiteur par son ID
            $user = User::find($id);

            if(empty($user)) return response()->json(['message' => 'Cet utilisateur n\'existe pas', 'status' => 404], 404);

            // Récupérer toutes les favoris de cet utilisateur
            $properties = $user->favoriteProperties;

            return response()->json(['data' => $properties, 'status' => 200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

    /**
     * Add / Delete Favories of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function togglefavoris(Request $request, $iduser, $idproperty) {
        try {

            // Check existing favorite
            $favoris = Favory::where('user_id', $iduser)
                                ->where('property_id', $idproperty)
                                ->first();

            if ($favoris) {

                DB::beginTransaction();
                    $favoris->delete();
                DB::commit();

                return response()->json(["message" => "Favori retiré avec succès", "status" => 200], 200);
            } else {

                DB::beginTransaction();
                    $favoris = Favory::create([
                        'property_id' => $idproperty,
                        'user_id' => $iduser,
                    ]);
                DB::commit();

                return response()->json(['message' => 'Favoris ajouté avec succès','data' => $favoris, 'status' => 201], 201);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

    
    /**
     * All Properties of User
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
    
            $properties->map(function ($query) {
                $query->media = $query->media($query->id);
                $query->user;
                $query->note = Note::where('property_id', $query->id)->get();
                $query->category;
                return $query;
            });
            
            return response()->json([
                'status' => 200,
                'data' => $properties,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }

    }

    /**
     * List last 10 properties of User
     *
     * @unauthenticated
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function lastproperties(Request $request) {
        try {
            $properties = Property::orderBy('id', 'DESC')->limit(10)->get();
            $properties->map(function ($query) {
                $query->media = $query->media($query->id);
                $query->user;
                $query->note = Note::where('property_id', $query->id)->get();
                $query->category;
                return $query;
            });
            return response()->json(['data' => $properties, 'status' => 200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

    /**
     * Détails property of User
     *
     * @unauthenticated
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function detailsproperties(Request $request, $id) {
        try {
            $property = Property::find($id);

            if(empty($property)) return response()->json(['message' => 'Cette propriété n\'existe pas', 'status' => 404], 404);
            $property['media'] = $property->media($id);
            $property->user;
            $property->note = Note::where('property_id', $property->id)->get();
            $property->category;
            return response()->json(['data' => $property, 'status' => 200], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

    /**
     * Ask visit of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function askvisit(Request $request, Property $property){
        
        try {
            
            $validation = Validator::make($request->all(), [
                'date_visite' => 'required',
                'amount' => 'required',
                'type' => 'required',
                'reference' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $visit = Visit::create([
                'date_visite' => $request->date_visite,
                'amount' => $request->amount,
                'type' => $request->type,
                'reference' => $request->reference,
                'user_id' => auth()->user()->id,
                'property_id' => $property->id,
            ]);

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }
    
    /**
     * Fedapay Webhook Visit of User
     * 
     * Fedapay Webhook for asking visit
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function askvisit_webhook(Request $request){ 

        $endpoint_secret = config('fedapay.webhook_visit');

        $payload = @file_get_contents('php://input'); 
        $sig_header = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'];
        $feda = null;

        try {
            $feda = \FedaPay\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            
            return response()->json([
                "message" => 'Error1',
                "status" => 400,
            ],400);
            exit();
        } catch(\FedaPay\Error\SignatureVerification $e) {
            // Invalid signature

            return response()->json([
                "message" => 'Error2',
                "status" => 400,
            ],400);
            exit();
        }

        $exist = Visit::where('reference',$feda->entity->reference)->first();
        if($exist)
            return response()->json(['Transaction bien traité et sauvegardé en base']);

        $req = $feda->entity->custom_metadata;
        // Handle the event
        if($feda->name == 'transaction.approved'){ 

            $visit = Visit::create([
                'date_visite' => $req->date_visite,
                'user_id' => $req->user_id,
                'property_id' => $req->property_id,
                'amount' => $feda->entity->amount,
                'type' => $feda->entity->payment_method->brand,
                'reference' => $feda->entity->reference,
                'transaction' => $feda,
            ]);

        }
        return response()->json([
            "message" => 'Successfull',
            "status" => 200,
        ]);

    }

    /**
     * Leave a note of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function note(Request $request, Property $property){
    
        try {
            
            $validation = Validator::make($request->all(), [
                'note' => 'required',
                'comment' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $note = Note::create([
                'note' => $request->note,
                'comment' => $request->comment,
                'property_id' => $property->id,
                'user_id' => auth()->user()->id,
            ]);

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

}
