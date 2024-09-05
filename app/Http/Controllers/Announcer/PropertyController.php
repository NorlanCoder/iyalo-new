<?php

namespace App\Http\Controllers\Announcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Note;
use App\Models\Visit;
use App\Models\Calendar;
use App\Models\Property;
use App\Models\Media;

class PropertyController extends Controller
{
    /**
     * All Properties of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(){

        $properties = Property::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')
        ->paginate(10);
        // return $properties[0]->media(4);
        // $properties->each(function ($query) {
        //     $query['media'] = $query->media($query->id);
        //     return $query;
        // });
        $properties->map(function ($query) {
            $query->media = $query->media($query->id);
            $query->user;
            $query->note = Note::where('property_id', $query->id)->get();
            return $query;
        });

        return response()->json([
            'success' => true,
            'data' => $properties
        ], 200);
    }

    /**
     * Specific Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function show(Property $property){

        $property['media'] = $property->media($property->id);
        $property->user;
        $property->note = Note::where('property_id', $property->id)->get();
        $property->category;

        return response()->json([
            'message' => 'Success',
            'data' => $property
        ], 200);

    }

    /**
     * Add Property of Announcer
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function create(Request $request){
        try {

            $validation = Validator::make($request->all(), [
                'label'  => 'required',
                'category_id'  => 'required',
                'price'  => 'required',
                'frequency'  => '',
                'city'  => 'required',
                'country'  => 'required',
                'district'  => 'required',
                'lat'  => 'required',
                'long'  => 'required',
                'description'  => 'required',
                'room'  => 'required',
                'bathroom'  => 'required',
                'lounge'  => 'required',
                'swingpool'  => '',
                'visite_price'  => 'required',
                'conditions'  => 'required',
                'device'  => 'required',

                'cover'  => 'required|max:10000',
                'images.*' => 'required|file|mimes:jpeg,png,jpg,gif|max:10000',
            ]);

    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            DB::beginTransaction();

                if($request->cover){
                    $cover = $request->cover;
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $cover->move('uploads', $filename);
                    $cover_url = 'uploads/'.$filename;
                }

                $property = Property::create([
                    'label'  => $request->label,
                    'user_id'  => auth()->user()->id,
                    'category_id'  => $request->category_id,
                    'price'  => $request->price,
                    'frequency'  => $request->frequency,
                    'city'  => $request->city,
                    'country'  => $request->country,
                    'district'  => $request->district,
                    'lat'  => $request->lat,
                    'long'  => $request->long,
                    'description'  => $request->description,
                    'room'  => $request->room,
                    'bathroom'  => $request->bathroom,
                    'lounge'  => $request->lounge,
                    'swingpool'  => $request->swingpool,
                    'visite_price'  => $request->visite_price,
                    'conditions'  => $request->conditions,
                    'device'  => $request->device,
                    'cover_url' =>$cover_url,
                ]);


                foreach ($request->images as $file) {
                    $extension = $file->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $file->move('uploads', $filename);
                    $images = 'uploads/'.$filename;

                    $media = Media::create([
                        'lib' => 'properties',
                        'media_url' => $images,
                        'property_id' => $property->id,
                    ]);
                }

                // Send Notification
                $notification = Notification::create([
                    'title' => 'Nouvelle propriété disponible' ,
                    'body' => $property->label.' disponible pour '.$property->price.' '.$property->device.'. Les droits de visite élevés à '.$property->visite_price.' '.$property->device,
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
     * Update Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, Property $property){
        try {
            $validation = Validator::make($request->all(), [
                'label'  => 'required',
                'category_id'  => 'required',
                'price'  => 'required',
                'frequency'  => '',
                'city'  => 'required',
                'country'  => 'required',
                'district'  => 'required',
                'lat'  => 'required',
                'long'  => 'required',
                'description'  => 'required',
                'room'  => 'required',
                'bathroom'  => 'required',
                'lounge'  => 'required',
                'swingpool'  => '',
                'visite_price'  => 'required',
                'conditions'  => 'required',
                'device'  => 'required',

                'cover'  => 'max:10000',
                'images.*' => 'max:10000',

            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            DB::beginTransaction();

                if($request->hasFile('cover')){
                    $cover = $request->cover;
                    $extension = $cover->getClientOriginalExtension();
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $cover->move('uploads', $filename);
                    $cover_url = 'uploads/'.$filename;
                }else 
                    $cover_url = $property->cover_url;

                $property->update([
                    'label'  => $request->label,
                    'category_id'  => $request->category_id,
                    'price'  => $request->price,
                    'frequency'  => $request->frequency,
                    'city'  => $request->city,
                    'country'  => $request->country,
                    'district'  => $request->district,
                    'lat'  => $request->lat,
                    'long'  => $request->long,
                    'description'  => $request->description,
                    'room'  => $request->room,
                    'bathroom'  => $request->bathroom,
                    'lounge'  => $request->lounge,
                    'swingpool'  => $request->swingpool,
                    'visite_price'  => $request->visite_price,
                    'conditions'  => $request->conditions,
                    'device'  => $request->device,
                    'cover_url' =>$cover_url,
                ]);

                if($request->images){
                    $delete = Media::where('property_id',$property->id)->delete();
                    foreach ($request->images as $file) {
                        if($file->isvalid()){
                            $extension = $file->getClientOriginalName();
                            $filename = time().'-'.$extension;
                            $file->move('uploads', $filename);
                            $images = 'uploads/'.$filename;
                        }
                        else{
                            $images = $file;
                        }
                        $media = Media::create([
                            'lib' => 'properties',
                            'media_url' => $images,
                            'property_id' => $property->id,
                        ]);
                    }
                }
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
     * Active/Disactive Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function action(Property $property){
        
        $property->update([
            'status' => $property->status ? false : true,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Successfull',
        ], 200);

    }

     /**
     * List Calendar by Property of Announcer 
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

    // Calendar
     /**
     * Add Calendar by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function add_calendar(Request $request, Property $property){
        try {
            
            $validation = Validator::make($request->all(), [
                'day'  => 'required|array',
                'hour_start'  => 'required',
                'hour_end'  => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }
            $hour = [
                'start' => $request->hour_start,
                'end' => $request->hour_end,
            ];
            foreach ($request->day as $day) {
                $calendar = Calendar::create([
                    'day'  => $day,
                    'property_id'  => $property->id,
                    'hour'  => $hour,
                ]);
            }

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);
            
        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }
    
     /**
     * Update Calendar by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update_calendar(Request $request, Calendar $calendar){
        try {
            
            $validation = Validator::make($request->all(), [
                'day'  => 'required',
                'hour_start'  => 'required',
                'hour_end'  => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            $hour = [
                'start' => $request->hour_start,
                'end' => $request->hour_end,
            ];

            $calendar->update([
                'day'  => $request->day,
                'hour'  => $hour,
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
     * Delete Calendar by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function delete_calendar(Request $request, Calendar $calendar){
        
        $calendar->detete();

        return response()->json([
            'message' => 'Success',
        ], 200);
    }

    /**
     * List Note by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function notes(Property $property){
        
        $notes = Note::where('property_id',$property->id)->orderBy('created_at','desc')->paginate(10);
        
        return response()->json([
            'status' => 200,
            'data' => $notes
        ]);
    }

    // Visit
    /**
     * List Visit by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function visits(Property $property){
        
        $visits = Visit::where('property_id',$property->id)->orderBy('created_at','desc')->paginate(10);
        
        $all_cash = Visit::where('property_id',$property->id)->where('is_refund',false)->sum('amount') - Visit::where('property_id',$property->id)->sum('free');
        $pending = Visit::where('property_id',$property->id)->where('visited',false)->where('is_refund',false)->sum('amount') - Visit::where('property_id',$property->id)->where('visited',false)->where('is_refund',false)->sum('free');
        
        $cash = $all_cash - $pending;

        return response()->json([
            'status' => 200,
            'all_cash' => $all_cash,
            'pending' => $pending,
            'cash' => $cash,
            'data' => $visits
        ]);
    }

    /**
     * Mask visit by Property of Announcer
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function confirm_owner(Visit $visit){
        
        $visit->update([
            'confirm_owner'=> true,
            'visited' => $visit->confirm_client ? true : $visit->visited
        ]);

        if($visit->visited)  
            $pushnotif = $this->sendNotificationVisit($visit->property->user->id,'Confirmation de Visite', $visit->user->name.' a confirmé que la réservation pour la visite de '.$visit->property->label.' a eu lieu. Veuillez consulter votre compte pour entrer en possession de vos fonds. Montant '.($visit->amount - $visit->free).' '.$visit->property->device);

        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }

}