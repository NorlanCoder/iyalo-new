<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Users
     *
     * All Users of Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index(){
        
        $users = User::where('id','!=',auth()->user()->id)->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }


    /**
     * Add Admin of Admin
     *
     * Add only Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function create(Request $request){
        try {

            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required|unique:users',
                'birthday' => 'required',
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
                'role' => 'admin',
                'birthday' => $request->birthday,
                'token_notify' => random_int(100000, 999999),
                'password' => Hash::make($request->password),
            ]);

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
     * Update Admin of Admin
     *
     * Update only Admin
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function update(Request $request, User $user){

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'birthday' => 'required',
        ]);

        if($user->phone != $request->phone){
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
        if($user->email != $request->email){
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
        
        $user->update([
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
     * Active/Disactive User of Admin
     *
     * Active/Disactive User
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function action(User $user){
        
        $user->update([
            'status' => $user->status ? false : true,
        ]);

        return response()->json([
            'status' => 200,
            'data' => $user
        ]);
    }

    /**
     * List Withdraw of Admin
     *
     * History of Withdrawal
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function user_withdraw(User $user){
        
        $withdraws = Withdraw::where('user_id',$user->id)->orderBy('created_at','desc')->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $withdraws
        ]);
    }

}
