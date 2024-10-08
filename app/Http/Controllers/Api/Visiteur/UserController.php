<?php

namespace App\Http\Controllers\Api\Visiteur;

use App\Service\NotificationService;
use App\Http\Controllers\Controller;
use App\Models\Favory;
use App\Models\Property;
use App\Models\User;
// use App\Models\Visit;
use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function getDateForDayOfWeek($targetDay) {
        // Récupérer le jour de la semaine actuel (0 = dimanche, 6 = samedi)
        $currentDay = date('w');
    
        // Convertir les jours en nombres si nécessaire (par exemple, si $targetDay est une chaîne)
        $targetDay = strtolower($targetDay);
        $daysOfWeek = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
        $targetDayNumber = array_search($targetDay, $daysOfWeek);
    
        // Calculer la différence de jours
        $diff = $targetDayNumber - $currentDay;
    
        // Créer un objet DateTime représentant la date du jour
        $date = new \DateTime();
    
        // Ajouter ou soustraire le nombre de jours nécessaire
        $date->modify($diff . ' days');
    
        // Formater la date selon vos besoins
        return $date->format('Y-m-d'); // Format AAAA-MM-JJ
    }
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
                'day' => 'required',
                'hour' => 'required',
                'amount' => 'required',
                'type' => 'required',
                'reference' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $dateCible = $this->getDateForDayOfWeek($request->day);
            $dateString = $dateCible.' '.$request->hour;
            $date_visite = new \DateTime($dateString);

            $visit = Visit::create([
                'date_visite' => $date_visite,
                'amount' => $request->amount,
                'free' => ($property->user->free * $request->amount)/100,
                'type' => $request->type,
                'reference' => $request->reference,
                'user_id' => auth()->user()->id,
                'property_id' => $property->id,
            ]);

            $pushnotif = $this->sendNotificationVisit($property->user->id,'Réservation pour visite', auth()->user()->name.' a fait une résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device);
            if(!$pushnotif)
                return response()->json(["errors" => 'Push Error', "status" => 400], 400);

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

        $validation = Validator::make($request->all(), [
            'day' => '',
            'hour' => '',
            'amount' => '',
            'type' => '',
            'reference' => '',
            'property_id' => '',
            'user_id' => '',
        ]);
        $payload = @file_get_contents('php://input'); 
        $sig_header = $_SERVER['HTTP_X_FEDAPAY_SIGNATURE'];
        $feda = null;

        if ($validation->fails()) {
            return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
        }

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
            
            $dateCible = $this->getDateForDayOfWeek($req->day);
            $dateString = $dateCible.' '.$req->hour;
            $date_visite = new \DateTime($dateString);
            $property = Property::find($req->property_id);

            $visit = Visit::create([
                'date_visite' => $date_visite,
                'user_id' => $req->user_id,
                'property_id' => $req->property_id,
                'free' => ($property->user->free * $request->amount)/100,
                'amount' => $feda->entity->amount,
                'type' => $feda->entity->payment_method->brand,
                'reference' => $feda->entity->reference,
                'transaction' => $feda,
            ]);
            $property = Property::find($req->property_id);
            
            $pushnotif = $this->sendNotificationVisit($property->user->id,'Réservation pour visite', auth()->user()->name.' a fait une résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device);
            if(!$pushnotif)
                return response()->json(["errors" => 'Push Error', "status" => 400], 400);

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

    // Visit
    /**
     * All list Visit of user
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function visits(Property $property){
        
        $visits = Visit::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(20);
        
        return response()->json([
            'status' => 200,
            'data' => $visits
        ]);
    }

    /**
     * Mask visit by Visitor of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function confirm_client(Visit $visit){
        
        $visit->update([
            'confirm_client'=> true,
            'describ' => "J'ai pu faire ma visite.",
            'visited' => $visit->confirm_owner ? true : $visit->visited,
        ]);

        if($visit->visited)  
            $pushnotif = $this->sendNotificationVisit($visit->property->user->id,'Confirmation de Visite', $visit->user->name.' a confirmé que la réservation pour la visite de '.$visit->property->label.' a eu lieu. Veuillez consulter votre compte pour entrer en possession de vos fonds. Montant '.($visit->amount - $visit->free).' '.$visit->property->device);

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }

    /**
     * Signal a visit of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function signal(Request $request,Visit $visit){
        try {
            
            $validation = Validator::make($request->all(), [
                'describ' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $visit = Visit::create([
                'describ' => $request->describ,
            ]);

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Refund cash a visit of User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function refund(Request $request,Visit $visit){
        try {
            
            $visit = Visit::create([
                'describ' => 'Fonds remboursé au client.',
                'is_refund' => true,
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
