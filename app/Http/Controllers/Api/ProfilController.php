<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Service\MailService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
     * History Notification
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function history(){
        if(auth()->user()->role == 'visitor')
            $notifications = Notification::whereNull('user_id')->get();
        else
            $notifications = Notification::where('user_id',auth()->user()->id)->get();
    
        return response()->json([
            'status' => 200,
            'data' => $notifications,
        ], 200);
    }

    
    /**
     * Save Push Token
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function save_token(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'token_notify' => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }

            auth()->user()->update([
                'token_notify' => $request->token_notify,
            ]);

            return response()->json(['message' => "Success","status" => 200]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }


    /**
     * Update Image Profil
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update_image(Request $request){

        $validation = Validator::make($request->all(), [
            'image'  => 'file',

        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }

        if($request->hasFile('image')){
            $cover = $request->image;
            $extension = $cover->getClientOriginalName();
            $filename = time().'-'.$extension;
            $cover->move('uploads/profil', $filename);
            $image_url = 'uploads/profil/'.$filename;
        }
        
        auth()->user()->update([
            'image_url' => $image_url,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Successfull',
        ], 200);
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
            'birthday' => '',
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
            'birthday' => '',
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }
        
        User::where('id', auth()->user()->id)->first()->update([
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
        $user = User::where('id', auth()->user()->id)->first();

        if(Hash::check($request->older, $user->password))
            $user->password = Hash::make($request->password);
        else
            return response()->json([
                "status" => 400,
                "errors" => "Revoir l'ancien mot de passe"
            ], 400);

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Mot de passe modifié avec succès',
        ], 200);
    }

    /**
     * Became Announcer
     *     * 
     */
    public function became_announcer(Request $request, MailService $mailer){

        $validation = Validator::make($request->all(), [
            'adress' => 'required',
            'card' => 'required|max:5000',
            'logo' => 'required|max:5000'
        ]);
        if ($validation->fails()) {
            return response()->json([
                "status" => 400,
                "errors" => $validation->errors()
            ], 400);
        }

        DB::beginTransaction();

            if($request->file('card')){
                $card = $request->card;
                $extension = $card->getClientOriginalExtension();
                $extension = $card->getClientOriginalName();
                $filename = time().'-'.$extension;
                $card->move('uploads/card', $filename);
                $card_url = 'uploads/card/'.$filename;
            }

            if($request->file('logo')){
                $logo = $request->logo;
                $extension = $logo->getClientOriginalExtension();
                $extension = $logo->getClientOriginalName();
                $filename = time().'-'.$extension;
                $logo->move('uploads/logo', $filename);
                $logo_url = 'uploads/logo/'.$filename;
            }
            $user = User::where('id', auth()->user()->id)->first();
            $user->update([
                // 'role' => 'announcer',
                'adress' => $request->adress,
                'card_image' => $card_url,
                'logo' => $logo_url
            ]);
            // $user->tokens()->delete();

        DB::commit();

        
        $mailer->contactMail(null, "fabienamoussou20062001@gmail.com",'Demande pour devenir Annonceur', $user->name.' a fait une  demande afin pour de devenir annonceur <br> Merci de vous connecter pour plus d\'information', 'Demande pour devenir Annonceur');

        return response()->json([
            'status' => 200,
            'message' => 'Votre demande est en cours de traitement',
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
        $user = User::where('id', auth()->user()->id)->first();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur déconnecté avec succès'       
        ]);
    }
}
