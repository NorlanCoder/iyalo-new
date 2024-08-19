<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Service\MailService;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register Mobile
     * 
     * @unauthenticated
     * 
     * Handles the register request and return token.
     *
     */

    public function register(Request $request, MailService $mailer){
        try {

            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required|unique:users',
                // 'birthday' => 'required',
                'password' => 'required|min:8',
                'confirm' => 'required|min:8|same:password'
            ]);
    
            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }

            DB::beginTransaction();
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                // 'birthday' => $request->birthday,
                'token_notify' => random_int(100000, 999999),
                'password' => Hash::make($request->password),
            ]);

            $mailer->activationMail($user->token_notify, $user->email);

            DB::commit();
    
            $data = [
                'message' => "You've been successfully register.",
                'user' => $user,
            ];
    
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Login
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     * 
     */
    public function login(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }
    
            $credentials = $request->only('email', 'password');
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    "message" => "Mauvais Mail ou mot de passe",
                ], 400);
            }

            $user = Auth::user();;
            $token = $request->user()->createToken('API-TOKEN');
            $data =  [
                'user' => $user,
                'token' => $token->plainTextToken,
            ];
            return response()->json([$data]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Forget Password
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     * 
     */
    public function forget(Request $request,MailService $mailer){
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
    
            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }

            $user = User::where('email',$request->email)->first();

            if(!$user)
                return response()->json(["errors" => 'This email don\'t exists'], 400);

            DB::beginTransaction();
                $user->update([
                    'token_notify' => random_int(100000, 999999),
                ]);

                $mailer->activationMail($user->token_notify, $user->email);
            DB::commit();

            return response()->json(["message" => 'Email is sending']);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Validate Token OPT
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     * 
     */

    public function validate_token(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email',
                'token' => 'required',
            ]);
    
            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }

            $exist = User::where([
                'email' => $request->email,
                'token_notify' => $request->token,
            ])->first();

            if(!$exist)
                return response()->json(["errors" => 'Invalid Token'], 400);

            return response()->json(["message" => 'Successful']);
            
        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    /**
     * Reset Password
     *
     * @unauthenticated
     * @return \Illuminate\Http\Response
     * 
     */
    public function reset(Request $request){
        try {
            $validation = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,id',
                'password' => 'required',
                'confirm' => 'required|same:password',
            ]);

            if ($validation->fails()) {
                return response()->json(["errors" => $validation->errors()], 400);
            }

            $user = User::where('email',$request->email)->first();

            $user->password = Hash::make($request->password);
            
            $user->save();

            return response()->json(["message" => 'Successful', "status" => 200]);

        } catch (\Exception $e) {
            return response()->json(["errors" => $e->getMessage(),"status" => 500], 500);
        }
    }

    
}
