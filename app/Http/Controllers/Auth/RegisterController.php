<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Mail;
use \Exception;



class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2097152|unique:users',
            'name' => 'required|string|max:150',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|max:100|confirmed',
//            'uf' => 'required|numeric|digits_between:1,2',
//            'cidade' => 'required|string|min:3|max:100',
            'cep' => 'required|numeric|digits:8',
//            'g-recaptcha-response' => 'required|recaptcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //salvar imagem
        $imageNome = null;
        if(isset($data['image'])){
            $imagemSalva = Vendor::salvarImagemUpload($data['image'], config('constantes.DESTINO_IMAGE_USUARIO'));
            if(!$imagemSalva['status']){
                return redirect()->back();
            }else{
                $imageNome = $imagemSalva['return'];
            }
        }
        
        //configurar UF e Cidade pelo CEP
        $resultCEP = Vendor::buscarCEP($data['cep']);
        if(!is_array($resultCEP)){ //error
            throw new Exception("CEP inválido", 1001); //1001 = CEP invalido
        }
        
        $data['image'] = $imageNome;
        $data['password'] = bcrypt($data['password']);
        $data['email_token'] = str_random(30);
        $data['cidade'] = $resultCEP['localidade'];
        $data['uf'] = $resultCEP['ufID'];
        
        $user = User::create($data);
        
        // Send confirmation code
        Mail::send('emails.verificar-email', $data, function($message) use ($data) {
            $message->to($data['email'], $data['name'])->subject(env('APP_NAME').' - Confirmar e-mail');
        });
    
        return $user;
    }
}