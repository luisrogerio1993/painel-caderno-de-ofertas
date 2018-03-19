<?php

use Illuminate\Database\Seeder;
use App\Models\Produtos_loja;

class ProdutosLojaTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Produtos_loja::create([
            'nome' => 'Crédito',
            'descricao' => 'Créditos usados para pagamento no sistema de anúncios.',
            'valor' => 1.00,
        ]);
    }
}
