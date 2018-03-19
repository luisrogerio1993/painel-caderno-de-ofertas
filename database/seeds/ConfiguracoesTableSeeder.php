<?php

use Illuminate\Database\Seeder;
use App\Models\Configuracoes;

class ConfiguracoesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuracoes::create([
            'valor_anuncio_padrao' => '0.1',
            'valor_anuncio_premium' => '0.15',
            'valor_anuncio_proximidade_fisica' => '0.3',
            'texto_aviso' => 'Atenção:
                             Apenas nessa segunda feira você terá desconto de 20% em compras acima de R$ 100. E 30% de desconto em compras acima de R$ 200.
                             Corra! Promoção válida apenas para esta segunda feita.',
        ]);
    }
}
