<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    /*
     * OVERRIDE
     */
    public function login(Request $request)
    {
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        
        if ($this->attemptLogin($request)) {
            //atualizar data_ultimo_acesso
            $user = User::find(Auth::user()->id);
            $user->data_ultimo_acesso = Carbon::now();
            $user->save();
            
            return $this->sendLoginResponse($request);
        }
        

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    
    /*
     * LOGIN SOCIAL
     */
    
     /**
    * Handle Social login request
    *
    * @return response
    */
   public function socialLogin($social)
   {
       return Socialite::driver($social)->redirect();
   }
   
   /**
    * Obtain the user information from Social Logged in.
    * @param $social
    * @return Response 
    */
   public function handleProviderCallback($social)
   {
       $userSocial = Socialite::driver($social)->user();
       $user = User::where(['email' => $userSocial->getEmail()])->first();
       if($user){
           Auth::login($user);
           return redirect()->route('admin.home');
       }else{
            $contaVinculada = $social == 'facebook' ? 1 : 2; //1 = FACEBOOK / 2 = GOOGLE
            $userCadastrado = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'password' => null,
                'cidade' => null,
                'uf' => null,
                'cep' => null,
                'conta_vinculada' => "{$contaVinculada}",
            ]);
            Auth::login($userCadastrado);
            
            return redirect()->route('admin.perfil.editar')->with('messageReturn', ['status' => true, 'messages' => ['Complete seu cadastro para usar nossa plataforma.',]]);
            /**/
        }
            
   }
}
