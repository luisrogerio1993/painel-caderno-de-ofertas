<?php

use Illuminate\Database\Seeder;
use App\Models\Lista_produtos_compra;

class ListaProdutosComprasTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        for($i = 1; $i < 6; $i++){
            Lista_produtos_compra::create([
                'compra_id' => $i,
                'produto_id' => 1,
                'valor_unitario' => 1,
                'quantidade' => rand(20, 500),
            ]);
        }
    }
}
