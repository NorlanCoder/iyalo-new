<?php

namespace App\Http\Controllers\Announcer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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

        $properties = Property::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10);

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
        
        return response()->json([
            'message' => 'Success',
            'data' => $property
        ], 200);
    }

    /**
     * Add Property
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
                'frequency'  => 'required',
                'city'  => 'required',
                'country'  => 'required',
                'district'  => 'required',
                'lat'  => 'required',
                'long'  => 'required',
                'description'  => 'required',
                'room'  => 'required',
                'bathroom'  => 'required',
                'lounge'  => 'required',
                'swingpool'  => 'required',
                'visite_price'  => 'required',
                'conditions'  => 'required',
                'device'  => 'required',

                'cover'  => 'required|max:10000',
                'images'  => 'required|max:10000',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            DB::beginTransaction();

                if($request->file('cover')){
                    $cover = $request->file('cover');
                    $extension = $cover->getClientOriginalExtension();
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $file->move('uploads', $filename);
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
                    'status'  => $request->status,
                    'visite_price'  => $request->visite_price,
                    'conditions'  => $request->conditions,
                    'device'  => $request->device,
                    'cover_url' =>$cover,
                ]);

                foreach ($request->file('images') as $file) {
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
            DB::commit();

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    
    }

    /**
     * Update Property
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
                'frequency'  => 'required',
                'city'  => 'required',
                'country'  => 'required',
                'district'  => 'required',
                'lat'  => 'required',
                'long'  => 'required',
                'description'  => 'required',
                'room'  => 'required',
                'bathroom'  => 'required',
                'lounge'  => 'required',
                'swingpool'  => 'required',
                'visite_price'  => 'required',
                'conditions'  => 'required',
                'device'  => 'required',

                'cover'  => 'max:10000',
                'images'  => 'max:10000',
            ]);
    
            if ($validation->fails()) {
                return response()->json([
                    "message" => $validation->errors(),
                    "status" => 400,
                ], 400);
            }

            DB::beginTransaction();

                if($request->file('cover')){
                    $cover = $request->file('cover');
                    $extension = $cover->getClientOriginalExtension();
                    $extension = $cover->getClientOriginalName();
                    $filename = time().'-'.$extension;
                    $file->move('uploads', $filename);
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
                    'status'  => $request->status,
                    'visite_price'  => $request->visite_price,
                    'conditions'  => $request->conditions,
                    'device'  => $request->device,
                    'cover_url' =>$cover,
                ]);

                if($request->file('images')){
                    $delete = Media::where('property_id',$property->id)->delete();
                    foreach ($request->file('images') as $file) {
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
                }

            DB::commit();

            return response()->json([
                "message" => 'Successfull',
                "status" => 200,
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    
    }

    /**
     * Active/Disactive  Property
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
}
