<?php

use Illuminate\Database\Seeder;
use App\Models\Ajuda\Ajuda_perguntas_frequentes;

class PerguntasFrequentesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PerguntasRespostas= [
            'O que é necessário para avaliar um anúncio?' => 'Estar cadastrado em nossa plataforma e ter confirmado seu email após o cadastro.',
            'Eu como usuário pagarei alguma coisa?' => 'Não. Apenas anunciantes pagam uma pequena quantia pela divulgação dos seus anuncios.',
            'Quanto custa cada modalidade de anúncio?' => 'Os valores estão descritos na página de cadastro de anúncios.',
            'Como me torno um anúnciante?' => 'Cadastrando um estabelecimento, esse estabelecimento será verificado e se aprovado, será ativado em nossa plataforma e você será automáticamente um anunciante.',
            'Quem pode cadastrar um estabelecimento?' => 'Qualquer usuário com seu e-mail confirmado.',
            'Quem pode cadastrar um anúncio?' => 'Qualquer usuário com um estabelecimento aprovado.',
            'Quanto custa para cadastrar um estabelecimento ou um anúncio?' => 'Nada, os anunciantes só pagaram pelos cliques de visitantes nos seus anúncios.',
            'Posso aumentar o limite de estabelecimentos ou anúncios?' => 'Não, esse valores foram definidos para que tenha competitividade entre todos os anunciantes, desde a grande empresa até a barraquinha de frutas.',
            'Não consigo ativar minha conta. O que fazer?' => 'Caso não esteja recebendo o e-mail de ativação ou tenha problema ao fazer os passos descritos nele para verificar seu e-mail, fique tranquilo, entre em contato conosco.',
            'Não consigo acessar a página Info, por quê?' => 'Ela é exclusiva para anunciantes.',
            ];
        
        
        foreach ($PerguntasRespostas as $pergunta => $resposta){
            $ultimaPergunta = Ajuda_perguntas_frequentes::create([
                'pergunta' =>  $pergunta,
                'resposta' =>  $resposta,
            ]);
        }
    }
}
