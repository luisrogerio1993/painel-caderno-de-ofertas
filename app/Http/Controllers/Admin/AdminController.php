<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor;
use App\Models\Estabelecimentos;
use App\Models\Anuncios;
use App\Models\Configuracoes;
use App\Models\Categorias_estabelecimentos;
use App\Models\Tipos_anuncio;
use App\User;
use App\Models\Ajuda\Ajuda_perguntas_frequentes;
use App\Models\Log_delete_validade;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\Anuncios\AnuncioController;
use App\Http\Controllers\Admin\Users\UserController;

class AdminController extends Controller
{
    private $estabelecimentos;
    private $usuarios;
    private $anuncios;
    private $configuracoes;
    private $categoriasEstabelecimento;
    private $tiposAnuncio;
    
    public function __construct(
                                Estabelecimentos $estabelecimentosGet, 
                                User $usuariosGet, 
                                Anuncios $anunciosGet,
                                Configuracoes $configuracoesGet,
                                Categorias_estabelecimentos $categoriasEstabelecimentoGet,
                                Tipos_anuncio $tiposAnuncioGet
                                )
    {
        $this->estabelecimentos = $estabelecimentosGet;
        $this->usuarios = $usuariosGet;
        $this->anuncios = $anunciosGet;
        $this->configuracoes = $configuracoesGet;
        $this->categoriasEstabelecimento = $categoriasEstabelecimentoGet;
        $this->tiposAnuncio = $tiposAnuncioGet;
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
    
    public function contato() {
        $title = 'Ajuda';
        $email = config('constantes.EMAIL_CADERNO_DE_OFERTAS');
        return view('admin.painel.contato', compact('title', 'email'));
    }
    
    public function verificarEmail($email_token) {
        $user = User::where('email_token', '=', $email_token)->first();

        if (!$user) return redirect()->route('site.home');

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
        
        //verificar se já fez o pedido a menos de 5 minutos
        if(Session::has('tms')){
            $tempoMinimoEmMinutos = 0;
            if (Session::get('tms')->diffInMinutes(Carbon::now()) <= $tempoMinimoEmMinutos){
                $tempoRestante = $tempoMinimoEmMinutos-Session::get('tms')->diffInMinutes(Carbon::now());
                return redirect()->back()->with('messageReturn', ['status' => false, 'messages' => ['Você poderá solicitar novamente dentro de '.$tempoRestante.' minutos.',]]);
            }
        }
        
        
        $user = $this->usuarios->findOrFail($id)->toArray();
        
        //verificar se já está verificado
        if($user['email_verificado']){
            return redirect()->back()->with('messageReturn', ['status' => true, 'messages' => ['Seu e-mail já está verificado.',]]);
        }
        
        Mail::send('emails.verificar-email', $user, function($message) use ($user) {
            $message->to($user['email'], $user['name'])->subject(env('APP_NAME').' - Confirmar e-mail');
        });
        
        //contador para não ficar enviando e-mails consecutivos
        Session::put('tms', Carbon::now());
        
        return redirect()->back()->with('messageReturn', ['status' => true, 'messages' => ['E-mail reenviado com sucesso.',]]);
    }
    
    public function rotinaDiaria() {
        $this->verifyValidateAnunciosAndUser();
    }
    
    private function verifyValidateAnunciosAndUser() {
        //config
        $descLogDelete = '|Fora da Validade|';
        
        //Recuperar todos os anuncios
        $anuncios = $this->anuncios::all();
        
        //Recuperar todos os usuário
        $usuarios = $this->usuarios::all();
        
        foreach ($anuncios as $anuncio) {
            //Verificar se tem anuncios fora da validade
            if($anuncio->anuncio_valido_ate < Carbon::now()){
                //Deletar anuncio
                $anuncioController = new AnuncioController($this->anuncios,
                                                        $this->categoriasEstabelecimento,
                                                        $this->tiposAnuncio,
                                                        $this->estabelecimentos);
                $anuncioController->destroy($anuncio->id);
                
                //criar log do delete
                Log_delete_validade::create([
                    'item' => $anuncio->nome,
                    'descricao' => 'Anuncio - Val.: '.$anuncio->anuncio_valido_ate.' - '.$descLogDelete,
                ]);
            }
        }
        foreach ($usuarios as $usuario) {
            //Verificar se a data de ultimo nao é nula e ele tem sem acesso a mais de 1 ano
            //verificar se a data de ultimo acesso é nula e a conta foi criada a mais de 1 ano
            if( (($usuario->data_ultimo_acesso != null) && $usuario->data_ultimo_acesso < Carbon::now()->subYear()) || (($usuario->data_ultimo_acesso == null) && $usuario->created_at < Carbon::now()->subYear()) ){
                //Deletar usuario
                $userController = new UserController($this->usuarios);
                $userController->destroy($usuario->id);
                
                //criar log do delete
                Log_delete_validade::create([
                    'item' => $usuario->name,
                    'descricao' => $usuario->data_ultimo_acesso != null ? 'Usuario - Ultimo acesso.: '. $usuario->data_ultimo_acesso .'  - '.$descLogDelete : 'Usuario - Criado em.: '. $usuario->created_at .'  - '.$descLogDelete ,
                ]);
            }
        }
    }
}
