<?php

namespace App\Http\Controllers\Api\Visiteur;

use App\Service\NotificationService;
use App\Http\Controllers\Controller;
use App\Models\Favory;
use App\Models\Property;
use App\Models\User;
use App\Models\Visit;
use App\Models\Note;
use App\Models\Calendar;
use App\Models\Annonce;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Service\MailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Country;
use App\Models\State;
use App\Models\City;

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
     * List City
     * @unauthenticated
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function city(Request $request){
        $benin = Country::where('name', 'benin')->first();

        $states = State::where('country_id', $benin->id)->pluck('id');

        // Get Cities in Benin
        $cities = City::whereIn('state_id', $states)->orderBy('name','asc')->get();

        return response()->json(['data' => $cities, 'status' => 200], 200);
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

            $ids = Favory::where('user_id',$id)->pluck('property_id');
            $properties = Property::whereIn('id',$ids)->get();

            $properties->map(function ($query) {
                $query->media = $query->media($query->id);
                $query->user;
                $query->note = Note::where('property_id', $query->id)->get();
                $query->category;
                return $query;
            });

            // Récupérer toutes les favoris de cet utilisateur
            // $properties = $user->favoriteProperties;

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
                'room' => 'integer',
                'bathroom' => 'integer',
                'min_price' => 'integer',
                'max_price' => 'integer',
                'search' => '',
                'swingpool' => 'integer',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            // Log::info($request->room);

            $search = isset($_GET['search']) ? mb_strtolower($_GET['search']) : null;
            $room = isset($_GET['room']) ? $_GET['room'] : 0;
            $bathroom = isset($_GET['bathroom']) ? $_GET['bathroom'] : 0;
            $swingpool = isset($_GET['swingpool']) ? $_GET['swingpool'] : null;
            $minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : 0;
            $maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : 999999999;
            $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

            // Start the query
            $properties = Property::where('status', true);

            // Add search filters
            if ($search) {
                $properties = $properties->where(function ($query) use ($search) {
                    $query->where(DB::raw('lower(country)'), 'like', "%$search%")
                        ->orWhere(DB::raw('lower(city)'), 'like', "%$search%")
                        ->orWhere(DB::raw('lower(label)'), 'like', "%$search%");
                });
            }

            // Add additional filters
            $properties = $properties->where('room', '>=', $room)
                                    ->where('bathroom', '>=', $bathroom);

            if ($swingpool !== null) {
                $properties = $properties->where('swingpool', $swingpool);
            }

            $properties = $properties->whereBetween('price', [$minPrice, $maxPrice]);

            // Apply category filter if set
            if ($categoryId) {
                $properties = $properties->where('category_id', $categoryId);
            }

            // Add sorting and pagination
            $properties = $properties->orderBy('created_at', 'desc')->paginate(10);

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
     * Map Properties of User
     *
     * @unauthenticated
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function map_properties(Request $request){
        try {

            $validation = Validator::make($request->all(), [
                'distance' => 'required',
                'long' => 'required',
                'lat' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }


            $properties = Property::selectRaw('*, ( 6371 * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( long ) - radians(' . $request->long . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance')

                    ->having('distance', '<', $request->distance)
                    ->orderBy('created_at','desc')->paginate(10);


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
     * List Calendar by Property of User
     *
     * @unauthenticated
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function calendar(Property $property){

        $calendars = Calendar::where('property_id',$property->id)->paginate(10);

        return response()->json([
            'message' => 'Success',
            'data' => $calendars
        ], 200);
    }


    /**
     * Ask visit of User
     *
     * @unauthenticated
     *
     * Faire le paiement via Fedapay par Callback. Il faut mettre dans le customer_metadata les variables <b> user_id, proprety_id, day, hour </b>
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function askvisit(Request $request, MailService $mailer){

        try {
            // dd($request->id);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer sk_sandbox_xD-eXmwB90F2Ih6PWATQKnLb',
                'Content-Type' => 'application/json',
            ])->get('https://sandbox-api.fedapay.com/v1/transactions/'.$request->id);

            $data = $response->json()["v1/transaction"];

            // return $data;
            // Gestion de la réponse
            if (!$response->successful()) {
                return response()->json(['error' => 'Erreur lors de la récupération de la transaction'], 500);
            }
            $property = Property::find($data['custom_metadata']['property_id']);
            $user = User::find($data['custom_metadata']['user_id']);


            $dateCible = $this->getDateForDayOfWeek($data['custom_metadata']['day']);
            $dateString = $dateCible;
            $date_visite = new \DateTime($dateString);

            // Log::info($data['custom_metadata']['day']);

            $visit = Visit::create([
                'date_visite' => now(),
                'amount' => $data['amount'],
                'free' => ($property->user->free * $data['amount'])/100,
                'type' => 'fedapay',
                'reference' => $data['reference'],
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);

            // $pushnotif = new NotificationService();
            // $pushnotif->sendNotificationVisit($property->user->id,'Réservation pour visite', $user->name.' a fait une réservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device);

            // if(!$pushnotif)
            //     return response()->json(["errors" => 'Push Error', "status" => 400], 400);


            // Client
            $mailer->contactMail(null, $user->email,'Réservation pour visite', $user->name.' vous venez de faire une résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device.' le '.$visit->date_visite, 'Réservation pour visite de '.$property->label);

            // Proprio
            $mailer->contactMail(null, $property->user->email,'Programation de Visite', $user->name.' a fait une  résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device.' le '.$visit->date_visite.'<br> Merci de vous connecter pour plus d\'information', 'Réservation pour visite de '.$property->label.' le '.$visit->date_visite);

            return 'approuved';

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

    /**
     * Fedapay Webhook Visit of User
     *
     * @unauthenticated
     *
     * Fedapay Webhook for asking visit
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function askvisit_webhook(Request $request, MailService $mailer){

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
            // Client
            $mailer->contactMail(null, auth()->user()->email,'Réservation pour visite', auth()->user()->name.' vous venez de faire une résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device.' le '.$date_visite, 'Réservation pour visite de '.$property->label);

            // Proprio
            $mailer->contactMail(null, $property->user->email,'Programation de Visite', auth()->user()->name.' a fait une  résservation pour la visite de '.$property->label.' disponible pour '.$property->price.' '.$property->device.' le '.$date_visite.'<br> Merci de vous connecter pour plus d\'information', 'Réservation pour visite de '.$property->label.' le '.$date_visite);

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
    public function visits(){

        $visits = Visit::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(20);

        return response()->json([
            'status' => 200,
            'data' => $visits
        ]);
    }

    /**
     * Mask visit by Visitor of User
     *
     * Cette action permet de confirmer que le client a bien pu faire la visite
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

            $visit->describ = $request->describ;
            $visit->save();

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
    /**
     * All Annonces with (serach bar)
     *
     * Cette route est à utiliser pour la page de recherche généraliser avec en parametre <b> search </b>
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     *
     */
    public function annonces(Request $request) {
        try {

            $validation = Validator::make($request->all(), [
                'search' => 'string'
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors(), "status" => 400], 400);
            }

            $search = $request->search ?? '';

            $annonces = Annonce::where('active',true)
                ->where(function ($query) use ($search) {
                    $query->where(DB::raw('lower(title)'),'like','%'.strtolower($search).'%')
                        ->orwhere(DB::raw('lower(type)'),'like','%'.strtolower($search).'%')
                        ->orwhere(DB::raw('lower(description)'),'like','%'.strtolower($search).'%')
                        ->orwhere(DB::raw('lower(adresse)'),'like','%'.strtolower($search).'%');
            })->orderBy('created_at','desc')->paginate(20);

            return response()->json([
                'status' => 200,
                'data' => $annonces,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(), "status" => 500], 500);
        }
    }

}
