<?php

use Illuminate\Database\Seeder;
use App\Models\Categorias_estabelecimentos;

class CategoriasEstabelecimentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listaCategorias = [
            'Acessórios para Veículos',
            'Agro, Indústria e Comércio',
            'Alimentos e Bebidas',
            'Animais',
            'Antiguidades',
            'Arte e Artesanato',
            'Bebês',
            'Beleza e Cuidado Pessoal',
            'Brinquedos e Hobbies',
            'Calçados, Roupas e Bolsas',
            'Carros, Motos e Outros',
            'Câmbio',
            'Câmeras e Acessórios',
            'Casa, Móveis e Decoração',
            'Celulares e Telefones',
            'Coleções e Comics',
            'Conteúdo Adultos',
            'Eletrodomésticos',
            'Eletrônicos, Áudio e Vídeo',
            'Esportes e Fitness',
            'Esoterismo e Ocultismo',
            'Ferramentas e Construção',
            'Filmes e Seriados',
            'Games Eletrônicos',
            'Informática',
            'Ingressos',
            'Instrumentos Musicais',
            'Imóveis',
            'Joias e Relógios',
            'Jogos em Geral',
            'Livros e Papelaria',
            'Música',
            'Saúde',
            'Serviços',
            'Tabacaria',
            'Outro'
        ];

        foreach ($listaCategorias as $categoria) {
            Categorias_estabelecimentos::create([
                'nome' => $categoria
            ]);
        }
        
    }
}