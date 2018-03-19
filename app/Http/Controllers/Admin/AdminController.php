<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;
use App\Models\Configuracoes;
use App\User;
use App\Models\Ajuda\Ajuda_perguntas_frequentes;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    private $estabelecimentos;
    private $usuarios;
    private $anuncios;
    private $configuracoes;
    
    public function __construct(
                                Estabelecimentos $estabelecimentosGet, 
                                User $usuariosGet, 
                                Anuncios $anunciosGet,
                                Configuracoes $configuracoesGet
                                )
    {
        $this->estabelecimentos = $estabelecimentosGet;
        $this->usuarios = $usuariosGet;
        $this->anuncios = $anunciosGet;
        $this->configuracoes = $configuracoesGet;
    }
    
    public function index(){
        $data = [
            /*
             * CONFIGURACOES
             */
            'configuracoes' => $this->configuracoes->all(),
            
            /*
             * ANUNCIANTE
             */
            'meusEstabelecimentosCadastrados' => $this->estabelecimentos->where('user_id', '=', Auth::user()->id)->count(),
            'meusAnunciosCadastrados' => 0,
    
            /*
             * ADMINISTRATIVO
             */
            'estabelecimentosCadastradosHoje' => $this->estabelecimentos->whereDate('created_at', '>=', Carbon::now()->toDateString())->count(),
            'estabelecimentosCadastrados' => $this->estabelecimentos::count(),
            'usuariosCadastradosHoje' => $this->usuarios->whereDate('created_at', '=', Carbon::now()->toDateString())->count(),
            'usuariosCadastrados' => $this->usuarios::count(),
            'anunciosCadastradosHoje' => $this->anuncios->whereDate('created_at', '=', Carbon::now()->toDateString())->count(),
            'anunciosCadastrados' => $this->anuncios::count(),
            'anunciantesCadastradosHoje' => $this->anuncios->whereDate('created_at', '=', Carbon::now()->toDateString())
                                                        ->distinct()
                                                        ->get(['user_id'])
                                                        ->count(),
            'anunciantesCadastrados' => $this->anuncios::distinct()->get(['user_id'])->count(),
        ];
            /*
             * ANUNCIANTES
             */
            $data['meusAnunciosCadastrados'] = Vendor::getTotalAnunciosUsuario(Auth::user()->id);
            
        $title = 'Início';
        
        return view('admin.home.index', compact('title', 'data'));
    }
    
    public function ajuda() {
        $perguntas = Ajuda_perguntas_frequentes::all();
        $title = 'Ajuda';
        
        return view('admin.painel.ajuda', compact('title', 'perguntas'));
    }
    
    public function verificarEmail($email_token) {
        $user = User::where('email_token', '=', $email_token)->first();

        if (!$user) return redirect()->route('site.home');
//        if ($user->)

        $user->email_verificado = true;
        $user->email_token = null;
        $user->save();
        
        Auth::login($user);

        return redirect()->route('admin.home')->with('messageReturn', ['status' => true, 'messages' => ['E-mail verificado com sucesso.',]]);
    }
    
    public function sendConfirmationEmail($id) {
        //verificar permissao
        if($id != Auth::user()->id && !Auth::user()->is_admin){
            return redirect()->back();
        }
        
        $user = $this->usuarios->findOrFail($id)->toArray();
        
        Mail::send('emails.verificar-email', $user, function($message) use ($user) {
            $message->to($user['email'], $user['name'])->subject(env('APP_NAME').' - Confirmar e-mail');
        });
        
        return redirect()->back()->with('messageReturn', ['status' => true, 'messages' => ['E-mail reenviado com sucesso.',]]);
    }
}
