<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Service\NotificationService;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\Visit;
use App\Models\Category;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request){

        $cash = Visit::where('visited',true)->where('is_refund',false)->sum('amount') - Visit::where('visited',true)->where('is_refund',false)->sum('free');
        $pending = Visit::where('visited',false)->where('is_refund',false)->sum('amount') - Visit::where('visited',false)->where('is_refund',false)->sum('free');

        $all_cash = $cash + $pending;

        $q = $request->q ? : '';
        $visits = Visit::where(function ($query) use ($q) {

            $users = User::where(DB::raw('lower(name)') ,'like','%'.strtolower($q).'%')->orwhere(DB::raw('lower(email)') ,'like','%'.strtolower($q).'%')->pluck('id');
            $categories = Category::where(DB::raw('lower(label)') ,'like','%'.strtolower($q).'%')->pluck('id');
            $properties = Property::where(DB::raw('lower(label)') ,'like','%'.strtolower($q).'%')->orwhereIn('category_id',$categories)->orwhereIn('user_id',$users)->pluck('id');

            $query->where(DB::raw('lower(type)'),'like','%'.strtolower($q).'%')
                ->orwhere(DB::raw('lower(reference)'),'like','%'.strtolower($q).'%')
                ->orwhere(DB::raw('lower(describ)'),'like','%'.strtolower($q).'%')
                ->orwhereIn('user_id', $users)
                ->orwhereIn('property_id', $properties);

        })->orderBy('created_at','desc')->paginate(10);

        return view('admin.file.visits.index',compact('visits','q','all_cash','cash','pending'));
    }

    public function refund(Visit $visit){
        
        $visit->update([
            'is_refund' => true,
        ]);

        return back()->with('success','Succès');

    }

    public function check(Visit $visit){
        
        $visit->update([
            'confirm_client' => true,
            'confirm_owner' => true,
            'visited' => true,
        ]);

        // if($visit->visited)
        //     $pushnotif = new NotificationService();
        //     $pushnotif->sendNotificationVisit($visit->property->user->id,'Confirmation de Visite', $visit->user->name.' a confirmé que la réservation pour la visite de '.$visit->property->label.' a eu lieu. Veuillez consulter votre compte pour entrer en possession de vos fonds. Montant '.($visit->amount - $visit->free).' '.$visit->property->device);

        return back()->with('success','Succès');

    }
}
