<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;
use App\Models\Lista_ufs;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Models\Password_resets;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'image', 
        'name', 
        'email', 
        'password', 
        'cidade', 
        'uf', 
        'cep', 
        'credito_disponivel',
        'conta_vinculada',
        'account_social_id',
        'email_token',
        'email_verificado',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    
    public function notifyAprovedOrder($userInArray)
    {
        Mail::send('emails.compra-aprovada', $userInArray, function($message) use ($userInArray) {
            $message->to($userInArray['email'], $userInArray['name'])->subject(env('APP_NAME').' - Pagamento confirmado');
        });
    }
    
    public function getEstabelecimentos() {
        return Estabelecimentos::where('user_id', '=', $this->id)->get();
    }
    
    public function getEstado() {
        return Lista_ufs::where('id', '=', $this->uf)->first();
    }
    
    public function getTotalEstabelecimentos() {
        return Estabelecimentos::where('user_id', '=', $this->id)->count();
    }
    
    public function getTotalAnuncios() {
        return Anuncios::where('user_id', '=', $this->id)->count();
    }
    
    public function isAnunciante() {
        $quantEstabelecimentos = Estabelecimentos::where('user_id', '=', $this->id)->count();
        if ( $quantEstabelecimentos > 0 && $this->temEstabelecimentoVerificado() ){
            return true;
        }
        
        return false;
    }
    
    public function addCreditos($quantidade) {
        $this->credito_disponivel += $quantidade;
        $this->save();
    }
    public function subCreditos($quantidade) {
        $this->credito_disponivel -= $quantidade;
        $this->save();
    }
    
    public function temEstabelecimentoVerificado() {
        $estabelecimentos = $this->getEstabelecimentos();
        foreach ($estabelecimentos as $estabelecimento) {
            if($estabelecimento->estabelecimento_verificado){
                return true;
            }
        }
        return false;
    }
    
    public function emailVerificado() {
        return $this->email_verificado;
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}