<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Token;
use App\Service\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    //LOGIN
    public function login(){

        return view('auth.login');
    }

    public function check(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validation->fails()){
            Session::flash('danger', "Erreur dans le formulaire");
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ]) ;
        }

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'admin';

        $exist = User::where('email',$request->email)->first();
        if(!$exist)
            return back()->with(['danger' => 'Mauvais Pseudo ou mot de passe' ]);
        if(!$exist->status)
            return back()->with(['danger' => 'Votre compte a été bloqué']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Auth::login($user);
            
            return redirect()->route('admin.dashboard');
        }

        return back()->with(['danger' => 'Mauvais Pseudo ou mot de passe' ]);
    }
    
    public function forget(){

        return view('auth.forget');
    }

    public function exist(Request $request, MailService $mailer){
        $request->validate([
            'email' => 'required',
        ]);
            
        $user = User::where(['email' => $request->email])->first();

        if($user){
            $token = new Token(); 
            $token->token = bin2hex(random_bytes(8));
            $token->user_id = $user->id;
            $token->expire_at = new \DateTime("+1 day");
            $token->save();
            
            $url = asset(route('auth.reset')."?t=".$token->token."&u=".$user->id);

            //sendin email with reactivation email
            $mailer->passwordMail($url, $user->email);

            Session::put('forget_email', $user->email);

            return redirect()->route('auth.link-sended');
        }else
            return back()->with('fail','Cette adresse mail non valide');
    }

    public function linkSended(){
        return view('auth.resend',[
            'email' => Session::pull('forget_email')
        ]);
    }

    public function reset(Request $request){
        // dd($request->t);
        $token = Token::where([
            'token' => $request->t,
            'user_id' => $request->u
        ])
        ->orderBy('id', 'desc')
        ->first();

        $expired_date = new \DateTime($token->expire_at);
        $date = new \DateTime();

        if($date > $expired_date)
            dd('Error code expired');

        $user = User::where('id', $token->user_id)->first();

        return view('auth.reset',compact('user'));
    }

    public function update_pass(Request $request, $id){
        $request->validate([
            'password' => 'required|min:8|max:30',
            'confirm' => 'required|same:password|min:8|max:30',
        ]);
        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('auth.login')->with('success', "Mot de passe modifier avec succès !");
    
    }

    public function logout(){

        Auth::logout();

        return redirect()->route('auth.login');
    }

}

