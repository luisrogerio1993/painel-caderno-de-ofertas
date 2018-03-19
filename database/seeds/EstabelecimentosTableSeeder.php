<?php

use Illuminate\Database\Seeder;
use App\Models\Estabelecimentos;

class EstabelecimentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        Estabelecimentos::create([
            'user_id' => 1,
            'nome' => 'Magazine Arcanjo',
            'categoria_estabelecimento' => 9,
            'descricao_estabelecimento' => 'Loja de brinquedos',
            'rua' => 'Rua sem saída',
            'numero' => 10,
            'complemento' => 'Prédio',
            'bairro' => 'Nova Um',
            'cpf' => '55515481984',
            'ddd_telefone_fixo' => rand(81, 99),
            'telefone_fixo' => '996493077',
            'ddd_telefone_celular' => rand(81, 99),
            'telefone_celular' => '996493055',
            'exibir_telefone_fixo_clientes' => 1,
            'exibir_telefone_celular_clientes' => 1,
            'telefone_celular_is_whatsapp' => 1,
            'estabelecimento_verificado' => 1,
        ]);
        
        $bairros = ['Bairro Dois', 'Bairro Três', 'Bairro Quatro'];
        $complementos = ['Casa', 'Prédio', 'Galpão'];
        $cpfs = ['52546336894', 
                '40568147596', 
                '19551886615', 
                '22614461601', 
                '45821428424', 
                '68626156960', 
                '33143744456', 
                '66888336034', 
                '46018663910', 
                '33239408570', 
                '20575449233',
                '34173631600',
                '37495415231',
                '30882775375',
                '38089357580',
                '01062529685',
                '33241348466',
                '10188152954',
                '66878416299',
                '26632584152',
                '24862854745',
                '42323788124',
                '44212880989',
                '76529898255',
                '14041593476',
                '16189847056',
                '35852468770',
                '67683004740',
            ];
        
        for ($i=1; $i<25; $i++) {
            Estabelecimentos::create([
                'user_id' => $i+1,
                'nome' => $faker->company,
                'categoria_estabelecimento' => rand(1, 36),
                'descricao_estabelecimento' => $faker->paragraph(1),
                'rua' => $faker->address,
                'numero' => rand(23, 500),
                'complemento' => array_random($complementos),
                'bairro' => array_random($bairros),
                'cpf' => $cpfs[$i],
                'ddd_telefone_fixo' => rand(81, 99),
                'telefone_fixo' => strval(rand(980000000, 989999999)),
                'ddd_telefone_celular' => rand(81, 99),
                'telefone_celular' => strval(rand(980000000, 989999999)),
                'exibir_telefone_fixo_clientes' => rand(0,1),
                'exibir_telefone_celular_clientes' => rand(0,1),
                'telefone_celular_is_whatsapp' => rand(0,1),
            ]);
        }
    }
}
